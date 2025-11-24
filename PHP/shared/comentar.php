<?php
// =======================
// Inicialização de sessão
// =======================
session_start();

require __DIR__ . '/conexao.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/validators.php';
require __DIR__ . '/comentarios.php';

// =====================
// Validações e inserção
// =====================
if (!usuarioLogado()) {
    die("Você precisa estar logado para comentar.");
}
$user_id = obterUsuarioAtualId();

// ================================
// Validações de usuario existentes
// ================================
if (!existeUsuario($pdo, $user_id)) {
    die("Usuário inválido ou não encontrado.");
}

// ====================
// Dados do comentariio
// ====================
$episodio_id = $_POST['episodio_id'] ?? 0; 
$id_anime = $_POST['id'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

// ==================
// Valida comentário
// ==================
if (empty($comentario)) {
    exit("Comentário inválido.");
}

// =================================================
// Se for comentário de episódio, verifica se existe
// =================================================
if ($episodio_id != 0 && !existeEpisodio($pdo, $episodio_id)) {
    exit("Episódio não encontrado.");
}

// Insere comentário
inserirComentario($pdo, $user_id, $episodio_id, $comentario);

// Redireciona para a página de episódios
$redirectUrl = "../../PHP/user/episodes.php";

if ($id_anime) {
    $redirectUrl .= "?id=" . urlencode($id_anime);
    if ($episodio_id != 0) {
        $redirectUrl .= "&episode_id=" . urlencode($episodio_id);
    }
} elseif ($episodio_id != 0) {
    $redirectUrl .= "?episode_id=" . urlencode($episodio_id);
}

header("Location: {$redirectUrl}");
exit;
