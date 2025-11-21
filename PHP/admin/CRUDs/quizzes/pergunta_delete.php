<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$quiz_id = $_GET['quiz_id'] ?? null;

if (!$id || !$quiz_id)
    die("ID da pergunta ou quiz não informado.");

// Verifica se a pergunta existe
$stmt = $pdo->prepare("SELECT * FROM quiz_perguntas WHERE id=? AND quiz_id=?");
$stmt->execute([$id, $quiz_id]);
$pergunta = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pergunta)
    die("Pergunta não encontrada.");

// Deleta a pergunta
$stmt = $pdo->prepare("DELETE FROM quiz_perguntas WHERE id=? AND quiz_id=?");
$stmt->execute([$id, $quiz_id]);

// Atualiza total de perguntas
$stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_perguntas WHERE quiz_id=?");
$stmt->execute([$quiz_id]);
$total = $stmt->fetchColumn();
$pdo->prepare("UPDATE quizzes SET total_perguntas=? WHERE id=?")->execute([$total, $quiz_id]);

header("Location: perguntas.php?quiz_id=$quiz_id");
exit();
?>