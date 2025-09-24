<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/reacoes.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['erro' => 'Usuário não logado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$episodio_id = $_POST['episodio_id'] ?? null;
$reacao = $_POST['reacao'] ?? null;

if (!$episodio_id || !in_array($reacao, ['like', 'dislike'], true)) {
    echo json_encode(['erro' => 'Dados inválidos']);
    exit;
}

// Salva ou atualiza a reação
$reacaoAtual = salvarOuAtualizarReacao($user_id, $episodio_id, $reacao);

// Conta as reações do episódio
$contagens = contarReacoesEpisodio($episodio_id);

echo json_encode([
    'sucesso' => true,
    'likes' => $contagens['like'],
    'dislikes' => $contagens['dislike'],
    'reacao_atual' => $reacaoAtual['reacao'] ?? null
]);
exit;
