<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Dados recebidos do formulário
$id         = $_POST['id'] ?? null;
$anime_id   = $_POST['anime_id'] ?? null;
$temporada  = $_POST['temporada'] ?? null;
$pergunta   = trim($_POST['pergunta'] ?? '');
$a          = trim($_POST['alternativa_a'] ?? '');
$b          = trim($_POST['alternativa_b'] ?? '');
$c          = trim($_POST['alternativa_c'] ?? '');
$d          = trim($_POST['alternativa_d'] ?? '');
$resposta   = $_POST['resposta_correta'] ?? 'A';

if ($id) {
    // Atualiza quiz existente
    $stmt = $pdo->prepare("
        UPDATE quizzes 
        SET anime_id=?, temporada=?, pergunta=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, resposta_correta=? 
        WHERE id=?
    ");
    $stmt->execute([$anime_id, $temporada, $pergunta, $a, $b, $c, $d, $resposta, $id]);
} else {
    // Insere novo quiz
    $stmt = $pdo->prepare("
        INSERT INTO quizzes (anime_id, temporada, pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, resposta_correta) 
        VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$anime_id, $temporada, $pergunta, $a, $b, $c, $d, $resposta]);
}

// Redireciona de volta para o admin
header('Location: ../../../PHP/admin/quiz/admin_quiz.php');
exit();
