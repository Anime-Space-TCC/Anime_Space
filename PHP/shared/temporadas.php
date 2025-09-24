<?php
require __DIR__ . '/conexao.php'; 

// Obtém o ID do anime da requisição
$anime_id = $_GET['anime_id'] ?? null;
$result = [];

// Busca as temporadas do anime no banco de dados
if ($anime_id) {
    $stmt = $pdo->prepare("SELECT numero, nome FROM temporadas WHERE anime_id = ? ORDER BY numero");
    $stmt->execute([$anime_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Retorna o resultado em formato JSON
header('Content-Type: application/json');
echo json_encode($result);
