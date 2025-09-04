<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$redirect = $_GET['redirect'] ?? 'admin'; // 'admin' (padrão) ou 'user'

if (!$id) {
    header('Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=ID%20inválido');
    exit();
}

// Busca o anime_id para possível uso (ajuste se precisar)
$stmt = $pdo->prepare("SELECT anime_id FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    header('Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=Quiz%20não%20encontrado');
    exit();
}

$animeId = (int)$quiz['anime_id'];

// Apaga
$del = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
$del->execute([$id]);

// Redireciona
if ($redirect === 'user' && $animeId) {
    // Ajuste o redirecionamento aqui se precisar passar anime_id para alguma página do usuário
    header("Location: ../../../PHP/user/quiz.php?anime_id={$animeId}&msg=Quiz%20excluído");
} else {
    header("Location: ../../../PHP/admin/quiz/admin_quiz.php?msg=Quiz%20excluído");
}
exit();
