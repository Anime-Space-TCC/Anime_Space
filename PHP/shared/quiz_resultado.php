<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

verificarLogin();
$userId = $_SESSION['user_id'];

$quizId = $_POST['quiz_id'] ?? null;
$xpGanho = (int)($_POST['xp'] ?? 0);
$pontuacao = (int)($_POST['pontuacao'] ?? 0);

if (!$quizId || $pontuacao < 0 || $xpGanho < 0) {
    header("Location: ../user/quizzes.php?erro=invalid");
    exit;
}

// Verifica quiz válido
$stmt = $pdo->prepare("SELECT nivel_minimo FROM quizzes WHERE id = ? AND ativo = 1");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) exit;

// Verifica nível
$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user['nivel'] < $quiz['nivel_minimo']) exit;

// Verifica tentativa anterior
$stmt = $pdo->prepare("SELECT id FROM quiz_resultados WHERE quiz_id = ? AND user_id = ?");
$stmt->execute([$quizId, $userId]);
$existente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$existente) {
    // Primeira vez → registra e dá XP
    $stmt = $pdo->prepare("INSERT INTO quiz_resultados (quiz_id, user_id, pontuacao) VALUES (?, ?, ?)");
    $stmt->execute([$quizId, $userId, $pontuacao]);
    adicionarXP($pdo, $userId, $xpGanho);
} else {
    // Apenas atualiza pontos
    $stmt = $pdo->prepare("UPDATE quiz_resultados SET pontuacao = ?, data_tentativa = NOW() WHERE id = ?");
    $stmt->execute([$pontuacao, $existente['id']]);
}

header("Location: ../user/quizzes.php");
exit;
