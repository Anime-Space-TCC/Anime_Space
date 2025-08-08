<?php
session_start(); // Inicia a sessão para usar variáveis de sessão
require_once __DIR__ . '/../shared/conexao.php'; // Importa a conexão com o banco de dados

header('Content-Type: application/json'); // Define o tipo de resposta como JSON

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['erro' => 'Usuário não logado']); // Retorna erro em JSON
    exit; // Encerra o script
}

$user_id = $_SESSION['user_id']; // Pega o ID do usuário logado
$episodio_id = $_POST['episodio_id'] ?? null; // Recebe o ID do episódio via POST
$reacao = $_POST['reacao'] ?? null; // Recebe a reação ('like' ou 'dislike')

// Validação básica dos dados recebidos
if (!$episodio_id || !in_array($reacao, ['like', 'dislike'], true)) {
    echo json_encode(['erro' => 'Dados inválidos']); // Retorna erro em JSON
    exit; // Encerra o script
}

// Verifica se já existe uma reação do usuário para este episódio
$stmt = $pdo->prepare("SELECT * FROM episodio_reacoes WHERE user_id = ? AND episodio_id = ?");
$stmt->execute([$user_id, $episodio_id]);
$existente = $stmt->fetch();

// Se já existe reação salva
if ($existente) {
    if ($existente['reacao'] === $reacao) {
        // Se o usuário clicou novamente na mesma reação, remove a reação
        $stmt = $pdo->prepare("DELETE FROM episodio_reacoes WHERE id = ?");
        $stmt->execute([$existente['id']]);
    } else {
        // Caso contrário, atualiza para a nova reação
        $stmt = $pdo->prepare("UPDATE episodio_reacoes SET reacao = ? WHERE id = ?");
        $stmt->execute([$reacao, $existente['id']]);
    }
} else {
    // Se não havia reação anterior, insere uma nova
    $stmt = $pdo->prepare("INSERT INTO episodio_reacoes (user_id, episodio_id, reacao) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $episodio_id, $reacao]);
}

// Busca as contagens atualizadas de likes e dislikes para o episódio
$stmt = $pdo->prepare("SELECT reacao, COUNT(*) as total FROM episodio_reacoes WHERE episodio_id = ? GROUP BY reacao");
$stmt->execute([$episodio_id]);

$contagens = ['like' => 0, 'dislike' => 0]; // Inicializa contadores

// Preenche as contagens conforme resultados da consulta
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
    if (isset($contagens[$linha['reacao']])) {
        $contagens[$linha['reacao']] = (int)$linha['total'];
    }
}

// Retorna o resultado em JSON com as contagens atualizadas
echo json_encode([
    'sucesso' => true,
    'likes' => $contagens['like'],
    'dislikes' => $contagens['dislike']
]);
exit; // Finaliza o script
