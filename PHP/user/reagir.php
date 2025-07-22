<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';

header('Content-Type: application/json');

// Verifica se usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['erro' => 'Usuário não logado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$episodio_id = $_POST['episodio_id'] ?? null;
$reacao = $_POST['reacao'] ?? null;

// Validação básica
if (!$episodio_id || !in_array($reacao, ['like', 'dislike'], true)) {
    echo json_encode(['erro' => 'Dados inválidos']);
    exit;
}

// Verifica se já existe reação do usuário para este episódio
$stmt = $pdo->prepare("SELECT * FROM episodio_reacoes WHERE user_id = ? AND episodio_id = ?");
$stmt->execute([$user_id, $episodio_id]);
$existente = $stmt->fetch();

if ($existente) {
    if ($existente['reacao'] === $reacao) {
        // Se o usuário clicou novamente na mesma reação, remove a reação
        $stmt = $pdo->prepare("DELETE FROM episodio_reacoes WHERE id = ?");
        $stmt->execute([$existente['id']]);
    } else {
        // Atualiza para a nova reação
        $stmt = $pdo->prepare("UPDATE episodio_reacoes SET reacao = ? WHERE id = ?");
        $stmt->execute([$reacao, $existente['id']]);
    }
} else {
    // Insere nova reação
    $stmt = $pdo->prepare("INSERT INTO episodio_reacoes (user_id, episodio_id, reacao) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $episodio_id, $reacao]);
}

// Busca as contagens atualizadas de likes e dislikes
$stmt = $pdo->prepare("SELECT reacao, COUNT(*) as total FROM episodio_reacoes WHERE episodio_id = ? GROUP BY reacao");
$stmt->execute([$episodio_id]);

$contagens = ['like' => 0, 'dislike' => 0];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
    if (isset($contagens[$linha['reacao']])) {
        $contagens[$linha['reacao']] = (int)$linha['total'];
    }
}

echo json_encode([
    'sucesso' => true,
    'likes' => $contagens['like'],
    'dislikes' => $contagens['dislike']
]);
exit;
