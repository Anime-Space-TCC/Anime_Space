<?php
// =======================
// Inicialização de sessão
// =======================
session_start();
header('Content-Type: application/json');

// ===========================
// Verifica se é administrador
// ===========================
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso negado']);
    exit();
}

require __DIR__ . '/../../PHP/shared/conexao.php';

// =================================
// Busca os 10 acessos mais recentes
// =================================
$stmt = $pdo->prepare("
    SELECT 
        a.id, 
        u.username AS usuario, 
        a.ip, 
        a.pagina, 
        a.origem, 
        a.tipo, 
        a.data_acesso
    FROM acessos a
    LEFT JOIN users u ON u.id = a.user_id
    ORDER BY a.data_acesso DESC
    LIMIT 10
");
$stmt->execute();
$acessos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Limpa qualquer saída anterior e retorna JSON
ob_clean();
echo json_encode($acessos);
exit();
