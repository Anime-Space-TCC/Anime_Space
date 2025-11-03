<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

verificarLogin();
$userId = $_SESSION['user_id'];

$quizId = $_POST['quiz_id'] ?? null;
$xpGanho = (int)($_POST['xp'] ?? 0); // Recebe XP real

if (!$quizId || $xpGanho < 0) {
    header("Location: ../user/quizzes.php?erro=invalid");
    exit;
}

// Verifica quiz ativo e nível mínimo
$stmt = $pdo->prepare("SELECT nivel_minimo FROM quizzes WHERE id = ? AND ativo = 1");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) { header("Location: ../user/quizzes.php?erro=quiz_inexistente"); exit; }

$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user['nivel'] < $quiz['nivel_minimo']) {
    header("Location: ../user/quizzes.php?erro=nivel_insuficiente"); exit;
}

// Verifica se já existe tentativa
$stmt = $pdo->prepare("SELECT id FROM quiz_resultados WHERE quiz_id = ? AND user_id = ?");
$stmt->execute([$quizId, $userId]);
$existente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$existente) {
    // Só ganha XP na primeira tentativa
    $stmt = $pdo->prepare("INSERT INTO quiz_resultados (quiz_id, user_id, pontuacao) VALUES (?, ?, ?)");
    $stmt->execute([$quizId, $userId, $xpGanho]);
    adicionarXP($pdo, $userId, $xpGanho);
} else {
    // Apenas atualiza pontuação, não ganha XP adicional
    $stmt = $pdo->prepare("UPDATE quiz_resultados SET pontuacao = ?, data_tentativa = NOW() WHERE id = ?");
    $stmt->execute([$xpGanho, $existente['id']]);
}


// 1) Verifica se já fez o quiz
$stmt = $pdo->prepare("SELECT * FROM quiz_resultados WHERE quiz_id = ? AND user_id = ?");
$stmt->execute([$quizId, $userId]);
$fezAntes = $stmt->fetch(PDO::FETCH_ASSOC);

if ($fezAntes) {
    $xpGanho = 0; // já fez antes, não ganha XP
} else {
    $xpGanho = 50; // primeira vez
    if ($acertos == $total_perguntas) {
        $xpGanho += 50; // bônus por perfeição
    }
}

header("Location: ../user/quizzes.php");
exit;
