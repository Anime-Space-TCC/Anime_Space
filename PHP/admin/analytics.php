<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
error_reporting(0); // evita notices que quebram JSON

require __DIR__ . '/../../PHP/shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['erro'=>'Acesso negado']);
    exit;
}

try {
    // Estatísticas gerais
    $totalUsuarios = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalAnimes = (int) $pdo->query("SELECT COUNT(*) FROM animes")->fetchColumn();
    $totalEpisodios = (int) $pdo->query("SELECT COUNT(*) FROM episodios")->fetchColumn();
    $totalProdutos = (int) $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    $totalAcessos = (int) $pdo->query("SELECT COUNT(*) FROM acessos")->fetchColumn();

    // Usuários últimos 6 meses
    $usuariosPorMes = $pdo->query("
        SELECT DATE_FORMAT(data_criacao,'%Y-%m') AS mes, COUNT(*) AS total
        FROM users
        WHERE data_criacao >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY mes
        ORDER BY mes
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Acessos últimos 7 dias
    $acessosPorDia = $pdo->query("
        SELECT DATE(data_acesso) AS dia, COUNT(*) AS total
        FROM acessos
        WHERE data_acesso >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY dia
        ORDER BY dia
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Últimos 10 acessos
    $acessosRecentes = $pdo->query("
        SELECT a.id, u.username AS usuario, a.ip, a.pagina, a.origem, a.tipo, a.data_acesso
        FROM acessos a
        LEFT JOIN users u ON a.user_id = u.id
        ORDER BY a.data_acesso DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'geral' => [
            'usuarios' => $totalUsuarios,
            'animes' => $totalAnimes,
            'episodios' => $totalEpisodios,
            'produtos' => $totalProdutos,
            'acessos' => $totalAcessos
        ],
        'usuarios_por_mes' => $usuariosPorMes,
        'acessos_por_dia' => $acessosPorDia,
        'acessos_recentes' => $acessosRecentes
    ], JSON_UNESCAPED_UNICODE);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro'=>'Falha ao carregar dados', 'mensagem'=>$e->getMessage()]);
}
