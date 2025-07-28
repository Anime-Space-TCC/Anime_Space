<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para comentar.");
}

$user_id = $_SESSION['user_id'];
$episodio_id = $_POST['episodio_id'] ?? null;
$id_anime = $_POST['id'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if (!$episodio_id || empty($comentario)) {
    exit("Comentário inválido.");
}

$stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, episodio_id, comentario, data_comentario) VALUES (?, ?, ?, NOW())");
$stmt->execute([$user_id, $episodio_id, $comentario]);

// Redireciona para a página correta com o nome certo do arquivo
if ($id_anime) {
    header("Location: /TCC/Anime_Space/PHP/user/episodes.php?id=" . urlencode($id_anime) . "&episode_id=" . urlencode($episodio_id));
} else {
    header("Location: /TCC/Anime_Space/PHP/user/episodes.php?episode_id=" . urlencode($episodio_id));
}
exit;
