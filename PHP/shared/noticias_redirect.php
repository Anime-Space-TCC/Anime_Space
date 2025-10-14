<?php
// Evita qualquer espaço ou caractere antes do PHP
require __DIR__ . '/../shared/conexao.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verifica se o ID é válido
if ($id > 0) {
    // Busca a URL da notícia
    $stmt = $pdo->prepare("SELECT url_externa FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($noticia && !empty($noticia['url_externa'])) {
        // Incrementa a contagem de visualizações
        $upd = $pdo->prepare("UPDATE noticias SET visualizacoes = visualizacoes + 1 WHERE id = ?");
        $upd->execute([$id]);

        // Sanitiza e garante que a URL tenha http/https
        $url = trim($noticia['url_externa']);
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url; // força https se não tiver protocolo
        }

        // Redireciona para o site oficial
        header("Location: $url");
        exit();
    }
}

// Caso não exista o ID ou a notícia
header("Location: /../../PHP/user/index.php");
exit();
