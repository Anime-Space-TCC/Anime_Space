<?php
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/validators.php';
require __DIR__ . '/../shared/comentarios.php';

// 1. Verifica login
if (!usuarioLogado()) {
    die("Você precisa estar logado para comentar.");
}
// Obtém o ID do usuário atualmente autenticado no sistema.
$user_id = obterUsuarioAtualId();

// 2. Confirma se usuário existe
if (!existeUsuario($pdo, $user_id)) {
    die("Usuário inválido ou não encontrado.");
}

// 3. Dados do formulário
$episodio_id = $_POST['episodio_id'] ?? null;
$id_anime    = $_POST['id'] ?? null;
$comentario  = trim($_POST['comentario'] ?? '');

// 4. Valida dados
if (!$episodio_id || empty($comentario)) {
    exit("Comentário inválido.");
}
if (!existeEpisodio($pdo, $episodio_id)) {
    exit("Episódio não encontrado.");
}

// 5. Insere comentário
inserirComentario($pdo, $user_id, $episodio_id, $comentario);

// 6. Redireciona
$host = $_SERVER['HTTP_HOST'];
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$base_url = "http://{$host}{$baseDir}/episodes.php";

if ($id_anime) {
    header("Location: {$base_url}?id=" . urlencode($id_anime) . "&episode_id=" . urlencode($episodio_id));
} else {
    header("Location: {$base_url}?episode_id=" . urlencode($episodio_id));
}
exit;
