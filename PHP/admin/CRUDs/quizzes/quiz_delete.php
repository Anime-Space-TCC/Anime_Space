<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Obtém o ID do quiz
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do quiz não informado.");
}

// Verifica se o quiz existe antes de excluir
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz não encontrado.");
}

// Exclui o quiz 
$stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
$stmt->execute([$id]);

// Redireciona de volta para a listagem
header('Location: admin_quiz.php');
exit();
?>
