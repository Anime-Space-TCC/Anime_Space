<?php
// Inicia a sessão apenas se não houver sessão ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../shared/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Retorna sucesso=false apenas para ações de favoritar
    echo json_encode(["sucesso" => false, "erro" => "Você precisa estar logado para favoritar"]);
    exit;
}

$userId = $_SESSION['user_id'];
$animeId = $_POST['anime_id'] ?? null;

if (!$animeId) {
    echo json_encode(["sucesso" => false, "erro" => "Anime inválido"]);
    exit;
}

// Verifica se já está favoritado
$stmt = $pdo->prepare("SELECT * FROM favoritos WHERE user_id = ? AND anime_id = ?");
$stmt->execute([$userId, $animeId]);
$existe = $stmt->fetch();

if ($existe) {
    // Remove favorito
    $pdo->prepare("DELETE FROM favoritos WHERE user_id = ? AND anime_id = ?")
        ->execute([$userId, $animeId]);
    echo json_encode(["sucesso" => true, "favoritado" => false]);
} else {
    // Adiciona favorito
    $pdo->prepare("INSERT INTO favoritos (user_id, anime_id) VALUES (?, ?)")
        ->execute([$userId, $animeId]);
    echo json_encode(["sucesso" => true, "favoritado" => true]);
}
