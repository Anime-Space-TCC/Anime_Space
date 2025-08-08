<?php
// Inicia a sessão para acessar as variáveis de sessão do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/../shared/conexao.php';

// Verifica se o usuário está logado. Se não estiver, interrompe o script com uma mensagem.
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para comentar.");
}

// Recupera o ID do usuário a partir da sessão
$user_id = $_SESSION['user_id'];

// Recupera os dados enviados pelo formulário via POST
$episodio_id = $_POST['episodio_id'] ?? null; // ID do episódio ao qual o comentário será associado
$id_anime = $_POST['id'] ?? null;             // ID do anime (usado no redirecionamento após o comentário)
$comentario = trim($_POST['comentario'] ?? ''); // Comentário inserido, com espaços removidos das extremidades

// Verifica se o ID do episódio existe e se o comentário não está vazio
if (!$episodio_id || empty($comentario)) {
    exit("Comentário inválido."); // Encerra o script com uma mensagem de erro
}

// Prepara a inserção do comentário no banco de dados com a data atual (NOW())
$stmt = $pdo->prepare("INSERT INTO comentarios (user_id, episodio_id, comentario, data_comentario) VALUES (?, ?, ?, NOW())");
$stmt->execute([$user_id, $episodio_id, $comentario]); // Executa a inserção passando os parâmetros

// Redireciona de volta para a página do episódio correspondente, com os parâmetros necessários
if ($id_anime) {
    // Se o ID do anime foi enviado, redireciona com ele também
    header("Location: /TCC/Anime_Space/PHP/user/episodes.php?id=" . urlencode($id_anime) . "&episode_id=" . urlencode($episodio_id));
} else {
    // Caso contrário, redireciona apenas com o ID do episódio
    header("Location: /TCC/Anime_Space/PHP/user/episodes.php?episode_id=" . urlencode($episodio_id));
}

// Encerra o script após o redirecionamento
exit;
