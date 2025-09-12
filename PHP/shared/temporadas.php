<?php
require __DIR__ . '/conexao.php'; // conexão com o banco

$anime_id = $_GET['anime_id'] ?? null;
$result = [];

if ($anime_id) {
    $stmt = $pdo->prepare("SELECT numero, nome FROM temporadas WHERE anime_id = ? ORDER BY numero");
    $stmt->execute([$anime_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($result);
