<?php
session_start();

require __DIR__ . '/conexao.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/validators.php';
require __DIR__ . '/comentarios.php';

// 1. Verifica login
if (!usuarioLogado()) {
    die("Você precisa estar logado para comentar.");
}
$user_id = obterUsuarioAtualId();

// 2. Confirma se usuário existe
if (!existeUsuario($pdo, $user_id)) {
    die("Usuário inválido ou não encontrado.");
}

// 3. Dados do formulário
$episodio_id = $_POST['episodio_id'] ?? 0; // 0 = comentário geral do anime
$id_anime    = $_POST['id'] ?? null;
$comentario  = trim($_POST['comentario'] ?? '');

// 4. Valida comentário
if (empty($comentario)) {
    exit("Comentário inválido.");
}

// 5. Se for comentário de episódio, verifica se existe
if ($episodio_id != 0 && !existeEpisodio($pdo, $episodio_id)) {
    exit("Episódio não encontrado.");
}

// 6. Insere comentário
inserirComentario($pdo, $user_id, $episodio_id, $comentario);

// 7. Redireciona para a página de episódios
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
