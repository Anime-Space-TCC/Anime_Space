<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_POST['id'] ?? null;
$anime_id = $_POST['anime_id'] ?? null;
$temporada = $_POST['temporada'] ?? null;
$pergunta = $_POST['pergunta'] ?? '';
$a = $_POST['alternativa_a'] ?? '';
$b = $_POST['alternativa_b'] ?? '';
$c = $_POST['alternativa_c'] ?? '';
$d = $_POST['alternativa_d'] ?? '';
$resposta = $_POST['resposta_correta'] ?? '';

if ($id) {
    $stmt = $pdo->prepare("UPDATE quizzes SET anime_id=?, temporada=?, pergunta=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, resposta_correta=? WHERE id=?");
    $stmt->execute([$anime_id, $temporada, $pergunta, $a, $b, $c, $d, $resposta, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO quizzes (anime_id, temporada, pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, resposta_correta) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$anime_id, $temporada, $pergunta, $a, $b, $c, $d, $resposta]);
}

header('Location: ../../../PHP/user/quiz.php');
exit();
