<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_POST['id'] ?? null;
$episodio_id = $_POST['episodio_id'];
$pergunta = $_POST['pergunta'];
$a = $_POST['alternativa_a'];
$b = $_POST['alternativa_b'];
$c = $_POST['alternativa_c'];
$d = $_POST['alternativa_d'];
$resposta = $_POST['resposta_correta'];

if ($id) {
    $stmt = $pdo->prepare("UPDATE quizzes SET episodio_id=?, pergunta=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, resposta_correta=? WHERE id=?");
    $stmt->execute([$episodio_id, $pergunta, $a, $b, $c, $d, $resposta, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO quizzes (episodio_id, pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, resposta_correta) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$episodio_id, $pergunta, $a, $b, $c, $d, $resposta]);
}

header('Location: ../../../PHP/user/quiz.php');
exit();
