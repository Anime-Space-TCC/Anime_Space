<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

verificarLogin();
$userId = $_SESSION['user_id'];

$quizId = $_POST['quiz_id'] ?? null;
$xpGanho = (int) ($_POST['xp'] ?? 0);
$pontuacao = (int) ($_POST['pontuacao'] ?? 0);

if (!$quizId || $pontuacao < 0 || $xpGanho < 0) {
    header("Location: ../user/quizzes.php?erro=invalid");
    exit;
}

// Verifica se o quiz existe e está ativo
$stmt = $pdo->prepare("SELECT nivel_minimo FROM quizzes WHERE id = ? AND ativo = 1");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    // Caso não exista um quiz válido
    header("Location: ../user/quizzes.php?erro=quiz_inexistente");
    exit;
}

// Verifica o nível do usuário
$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user['nivel'] < $quiz['nivel_minimo']) {
    // Se o nível do usuário for abaixo do mínimo para o quiz
    header("Location: ../user/quizzes.php?erro=nivel_insuficiente");
    exit;
}

// Verifica se já existe um registro da tentativa deste quiz
$stmt = $pdo->prepare("SELECT id FROM quiz_resultados WHERE quiz_id = ? AND user_id = ?");
$stmt->execute([$quizId, $userId]);
$existente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$existente) {
    // Primeira tentativa → registra a pontuação e dá XP
    $stmt = $pdo->prepare("INSERT INTO quiz_resultados (quiz_id, user_id, pontuacao) VALUES (?, ?, ?)");
    $stmt->execute([$quizId, $userId, $pontuacao]);

    // Adiciona XP ao usuário
    adicionarXP($pdo, $userId, $xpGanho);
} else {
    // Atualiza a pontuação se o usuário já tentou o quiz anteriormente
    $stmt = $pdo->prepare("UPDATE quiz_resultados SET pontuacao = ?, data_tentativa = NOW() WHERE id = ?");
    $stmt->execute([$pontuacao, $existente['id']]);
}

// Redireciona o usuário de volta para a página de quizzes
header("Location: ../user/quizzes.php");
exit;
?>