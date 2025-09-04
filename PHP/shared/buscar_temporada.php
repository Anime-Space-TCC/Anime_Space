<?php
require __DIR__ . '/../shared/conexao.php';
$anime_id = isset($_GET['anime_id']) ? (int)$_GET['anime_id'] : 0;
if (!$anime_id) exit(json_encode([]));

$stmt = $pdo->prepare("SELECT numero, nome FROM temporadas WHERE anime_id = ? ORDER BY numero");
$stmt->execute([$anime_id]);
$temporadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($temporadas);