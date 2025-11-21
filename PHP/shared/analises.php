<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso negado']);
    exit;
}

try {
    // ===== Totais gerais em uma query =====
    $totais = $pdo->query("
        SELECT
            (SELECT COUNT(*) FROM users) AS usuarios,
            (SELECT COUNT(*) FROM animes) AS animes,
            (SELECT COUNT(*) FROM episodios) AS episodios,
            (SELECT COUNT(*) FROM produtos) AS produtos,
            (SELECT COUNT(*) FROM acessos) AS acessos
    ")->fetch(PDO::FETCH_ASSOC);

    // ===== Acessos últimos 7 dias =====
    $acessosPorDia = $pdo->query("
        SELECT DATE(data_acesso) AS dia, COUNT(*) AS total
        FROM acessos
        WHERE data_acesso >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY dia
        ORDER BY dia
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Últimos 10 acessos (somente info essencial) =====
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

    // ===== Usuários por nacionalidade (top 6) =====
    $usuariosNacionalidade = $pdo->query("
        SELECT 
            COALESCE(nacionalidade, 'Não informado') AS pais,
            COUNT(*) AS total
        FROM users
        GROUP BY pais
        ORDER BY total DESC
        LIMIT 6
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Top 10 Animes mais assistidos =====
    $topAnimes = $pdo->query("
        SELECT a.nome AS titulo, COUNT(*) AS total
        FROM historico h
        JOIN animes a ON h.anime_id = a.id
        GROUP BY h.anime_id
        ORDER BY total DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // ===== Saída JSON otimizada =====
    echo json_encode([
        'geral' => $totais,
        'acessos_por_dia' => $acessosPorDia,
        'acessos_recentes' => $acessosRecentes,
        'usuarios_idade' => $usuariosIdade,
        'usuarios_nacionalidade' => $usuariosNacionalidade,
        'top_animes' => $topAnimes
    ], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Falha ao carregar dados',
        'mensagem' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
