<?php
require_once __DIR__ . '/conexao.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE user_id = ?");
$stmt->execute([$userId]);

echo "ok";
