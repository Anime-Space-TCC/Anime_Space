<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/reacoes.php';
require_once __DIR__ . '/../shared/gamificacao.php';

header('Content-Type: application/json');

// ============================
// Debug temporário (opcional)
// ============================

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['erro' => 'Usuário não logado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$episodio_id = $_POST['episodio_id'] ?? null;
$reacao = $_POST['reacao'] ?? null;

// Validação dos dados
if (!$episodio_id || !in_array($reacao, ['like', 'dislike'], true)) {
    echo json_encode(['erro' => 'Dados inválidos']);
    exit;
}

// Verifica se o usuário já tinha reagido antes
$stmt = $pdo->prepare("SELECT reacao FROM episodio_reacoes WHERE user_id = ? AND episodio_id = ?");
$stmt->execute([$user_id, $episodio_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);
$reacaoAnterior = $dados['reacao'] ?? null;

// Salva ou atualiza a reação
$reacaoAtual = salvarOuAtualizarReacao($pdo, $user_id, $episodio_id, $reacao);

// Se for a primeira vez reagindo, dá XP
if (!$reacaoAnterior) {
    adicionarXP($pdo, $user_id, 10);
}

// Conta as reações do episódio
$contagens = contarReacoesEpisodio($pdo, $episodio_id);

// Garantir que sempre existam índices
$contagens['like'] = $contagens['like'] ?? 0;
$contagens['dislike'] = $contagens['dislike'] ?? 0;

echo json_encode([
    'sucesso' => true,
    'likes' => $contagens['like'],
    'dislikes' => $contagens['dislike'],
    'reacao_atual' => $reacaoAtual['reacao'] ?? $reacao
]);
exit;
