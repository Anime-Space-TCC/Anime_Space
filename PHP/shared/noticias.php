<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../shared/conexao.php';

// Controle de visualizações
if (!isset($_SESSION['visualizou_noticias'])) {
    $_SESSION['visualizou_noticias'] = [];
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if (!in_array($id, $_SESSION['visualizou_noticias'])) {
        $pdo->query("UPDATE noticias SET visualizacoes = visualizacoes + 1 WHERE id = $id");
        $_SESSION['visualizou_noticias'][] = $id;
    }

    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
}

function buscarNoticiasPopulares(PDO $pdo, int $limite = 5): array {
    $stmt = $pdo->prepare("
        SELECT id, titulo, imagem, visualizacoes
        FROM noticias
        ORDER BY visualizacoes DESC, data_publicacao DESC
        LIMIT :limite
    ");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
