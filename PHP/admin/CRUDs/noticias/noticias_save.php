<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$titulo = trim($_POST['titulo']);
$resumo = trim($_POST['resumo']);
$url_externa = trim($_POST['url_externa']);
$tags = trim($_POST['tags']);
$imagem_nome = null;

// Upload de imagem
if (!empty($_FILES['imagem']['name'])) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $imagem_nome = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/../../../uploads/' . $imagem_nome);
}

if ($id) {
    // Atualizar
    $sql = "UPDATE noticias SET titulo = ?, resumo = ?, url_externa = ?, tags = ?" . 
           ($imagem_nome ? ", imagem = ?" : "") . 
           " WHERE id = ?";
    $params = [$titulo, $resumo, $url_externa, $tags];
    if ($imagem_nome) $params[] = $imagem_nome;
    $params[] = $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} else {
    // Inserir
    $stmt = $pdo->prepare("INSERT INTO noticias (titulo, resumo, url_externa, tags, imagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $resumo, $url_externa, $tags, $imagem_nome]);
}

header('Location: admin_noticias.php');
exit();
