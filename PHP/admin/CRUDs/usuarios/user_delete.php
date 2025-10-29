<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    exit("Acesso negado");
}

$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
}

header("Location: admin_user.php");
exit();
