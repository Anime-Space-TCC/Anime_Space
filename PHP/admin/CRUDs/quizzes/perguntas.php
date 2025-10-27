<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    die("ID do quiz não informado.");
}

// Busca o quiz
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    die("Quiz não encontrado.");
}

// Busca as perguntas do quiz
$stmt = $pdo->prepare("SELECT * FROM quiz_perguntas WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perguntas - <?= htmlspecialchars($quiz['titulo']) ?></title>
    <link rel="stylesheet" href="../../../../CSS/style.css">
</head>
<body>

<div class="admin-cruds">
    <div class="admin-links">
        <h1>Gerenciar Perguntas</h1>
        <h2><?= htmlspecialchars($quiz['titulo']) ?></h2>
        <nav>
            <a href="pergunta_form.php?quiz_id=<?= $quiz['id'] ?>" class="admin-btn">+ Nova Pergunta</a>
            <a href="admin_quiz.php" class="admin-btn">Voltar</a>
        </nav>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pergunta</th>
                <th>Respostas</th>
                <th>Correta</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($perguntas) > 0): ?>
                <?php foreach ($perguntas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['pergunta']) ?></td>
                        <td>
                            <?= htmlspecialchars($p['alternativa_a']) ?><br>
                            <?= htmlspecialchars($p['alternativa_b']) ?><br>
                            <?= htmlspecialchars($p['alternativa_c']) ?><br>
                            <?= htmlspecialchars($p['alternativa_d']) ?>
                        </td>
                        <td><?= strtoupper($p['resposta_correta']) ?></td>
                        <td>
                            <a href="pergunta_form.php?id=<?= $p['id'] ?>&quiz_id=<?= $quiz['id'] ?>" class="admin-btn">Editar</a>
                            <a href="pergunta_delete.php?id=<?= $p['id'] ?>&quiz_id=<?= $quiz['id'] ?>" class="admin-btn">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhuma pergunta cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
