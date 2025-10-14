<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    // Deleta a imagem
    $stmt = $pdo->prepare("SELECT imagem FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($noticia && $noticia['imagem']) {
        @unlink(__DIR__ . '/../../../uploads/' . $noticia['imagem']);
    }

    // Deleta a notícia
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: admin_noticias.php');
exit();
