<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
error_reporting(0); // Evita notices que quebram o JSON

require __DIR__ . '/../conexao.php'; 

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso negado']);
    exit;
}

try {
    // ===== Estatísticas gerais =====
    $totalUsuarios   = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalAnimes     = (int) $pdo->query("SELECT COUNT(*) FROM animes")->fetchColumn();
    $totalEpisodios  = (int) $pdo->query("SELECT COUNT(*) FROM episodios")->fetchColumn();
    $totalProdutos   = (int) $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    $totalAcessos    = (int) $pdo->query("SELECT COUNT(*) FROM acessos")->fetchColumn();

    // ===== Acessos últimos 7 dias =====
    $acessosPorDia = $pdo->query("
        SELECT DATE(data_acesso) AS dia, COUNT(*) AS total
        FROM acessos
        WHERE data_acesso >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY dia
        ORDER BY dia
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Últimos 10 acessos =====
    $acessosRecentes = $pdo->query("
        SELECT 
            a.id, 
            COALESCE(u.username, 'Visitante') AS usuario,
            a.pagina,
            a.origem,
            a.data_acesso
        FROM acessos a
        LEFT JOIN users u ON a.user_id = u.id
        ORDER BY a.data_acesso DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Usuários por faixa etária =====
    $usuariosIdade = $pdo->query("
        SELECT 
            CASE 
                WHEN idade < 18 THEN '0-17'
                WHEN idade BETWEEN 18 AND 25 THEN '18-25'
                WHEN idade BETWEEN 26 AND 35 THEN '26-35'
                ELSE '36+'
            END AS faixa,
            COUNT(*) AS total
        FROM users
        GROUP BY faixa
        ORDER BY faixa
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Usuários por nacionalidade =====
    $usuariosNacionalidade = $pdo->query("
        SELECT 
            COALESCE(nacionalidade, 'Não informado') AS pais,
            COUNT(*) AS total
        FROM users
        GROUP BY pais
        ORDER BY total DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Saída JSON =====
    echo json_encode([
        'geral' => [
            'usuarios'   => $totalUsuarios,
            'animes'     => $totalAnimes,
            'episodios'  => $totalEpisodios,
            'produtos'   => $totalProdutos,
            'acessos'    => $totalAcessos
        ],
        'acessos_por_dia'         => $acessosPorDia,
        'acessos_recentes'        => $acessosRecentes,
        'usuarios_idade'          => $usuariosIdade,
        'usuarios_nacionalidade'  => $usuariosNacionalidade
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Falha ao carregar dados',
        'mensagem' => $e->getMessage()
    ]);
}