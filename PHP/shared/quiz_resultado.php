<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

verificarLogin();

$userId = $_SESSION['user_id'];
$quizId = $_GET['quiz_id'] ?? null;
$xp = $_GET['xp'] ?? 0;

if (!$quizId || !$xp) {
    header("Location: quizzes.php");
    exit;
}

// Registra tentativa
$stmt = $pdo->prepare("INSERT INTO quiz_resultados (quiz_id, user_id, pontuacao) VALUES (?, ?, ?)");
$stmt->execute([$quizId, $userId, $xp]);

// Ganha XP
adicionarXP($pdo, $userId, $xp, "quiz_concluido", $quizId);

header("Location: ../user/perfil.php?msg=quizok");
exit;
?>
