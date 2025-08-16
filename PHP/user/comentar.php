<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para comentar.");
}

$user_id = $_SESSION['user_id'];

// Confirma se o user_id realmente existe no banco
$stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
if (!$stmt->fetch()) {
    die("Usuário inválido ou não encontrado.");
}

// Dados do formulário
$episodio_id = $_POST['episodio_id'] ?? null;
$id_anime    = $_POST['id'] ?? null;
$comentario  = trim($_POST['comentario'] ?? '');

// Validação
if (!$episodio_id || empty($comentario)) {
    exit("Comentário inválido.");
}

// Confirma se o episódio existe
$stmt = $pdo->prepare("SELECT id FROM episodios WHERE id = ?");
$stmt->execute([$episodio_id]);
if (!$stmt->fetch()) {
    exit("Episódio não encontrado.");
}

// Insere no banco
$stmt = $pdo->prepare("
    INSERT INTO comentarios (user_id, episodio_id, comentario, data_comentario)
    VALUES (?, ?, ?, NOW())
");
$stmt->execute([$user_id, $episodio_id, $comentario]);

// --- Caminho dinâmico para redirecionamento ---
$host = $_SERVER['HTTP_HOST']; // ex.: localhost
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // pasta atual de comentar.php
$base_url = "http://{$host}{$baseDir}/episodes.php";

// Redireciona para a página do episódio
if ($id_anime) {
    header("Location: {$base_url}?id=" . urlencode($id_anime) . "&episode_id=" . urlencode($episodio_id));
} else {
    header("Location: {$base_url}?episode_id=" . urlencode($episodio_id));
}
exit;
