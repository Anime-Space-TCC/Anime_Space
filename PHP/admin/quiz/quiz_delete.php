<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Captura e valida ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=ID inválido');
    exit();
}

// Busca quiz antes de deletar (garante que existe)
$stmt = $pdo->prepare("SELECT anime_id FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    header('Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=Quiz não encontrado');
    exit();
}

// Remove quiz
$stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
$stmt->execute([$id]);

// Redireciona para lista de quizzes
header("Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=Quiz excluído");
exit();
