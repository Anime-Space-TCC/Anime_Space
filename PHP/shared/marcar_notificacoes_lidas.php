<?php
require_once __DIR__ . '/conexao.php';
// =======================
// Inicialização de sessão
// =======================
session_start();

// =======================================
// Marcar todas as notificações como lidas
// =======================================
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

// =====================================
// Registra notificações lidas no banco
// =====================================
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE user_id = ?");
$stmt->execute([$userId]);

echo "ok";
