<?php
require __DIR__ . '/../shared/conexao.php';
session_start();

// Verifica permissão
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../HTML/login.html');
    exit();
}

// Obtém ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID inválido.");
}

// Deleta
$stmt = $pdo->prepare("DELETE FROM animes WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin_animes.php");
exit();
?>
