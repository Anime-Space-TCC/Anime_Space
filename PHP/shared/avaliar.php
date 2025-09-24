<?php
require __DIR__ . '/../shared/conexao.php';

// Inicia a sessão apenas se não houver sessão ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["sucesso" => false, "erro" => "Você precisa estar logado para avaliar"]);
    exit;
}

$userId = $_SESSION['user_id'];
$animeId = $_POST['anime_id'] ?? null;
$avaliacao = $_POST['avaliacao'] ?? null;

// Validações
if (!$animeId || !is_numeric($avaliacao) || $avaliacao < 0 || $avaliacao > 10) {
    echo json_encode(["sucesso" => false, "erro" => "Avaliação inválida"]);
    exit;
}

// Converte para float
$nota = floatval($avaliacao);

// Salva ou atualiza avaliação
$stmt = $pdo->prepare("
    INSERT INTO avaliacoes (user_id, anime_id, nota) 
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE nota = ?
");
$stmt->execute([$userId, $animeId, $nota, $nota]);

echo json_encode(["sucesso" => true, "nota" => round($nota)]); // arredonda para exibir 10/10
