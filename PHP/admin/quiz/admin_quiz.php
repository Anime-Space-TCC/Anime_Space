<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os quizzes, incluindo o nome do anime
$quizzes = $pdo->query("
    SELECT q.id, q.temporada, q.pergunta, q.alternativa_a, q.alternativa_b, q.alternativa_c, q.alternativa_d, q.resposta_correta,
           a.nome AS anime_nome
    FROM quizzes q
    JOIN animes a ON q.anime_id = a.id
    ORDER BY a.nome, q.id
")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quizzes</title>
    <link rel="stylesheet" href="../../../CSS/style.css?v=2">
    <link rel="icon" href="../../../img/slogan3.png" type="image/png">
</head>
<body class="admin">
    <div class="admin-links">
        <h1>Gerenciar Quizzes</h1>
        <nav>
            <a href="../../../PHP/user/index.php" class="admin-btn">Home</a>
            <a href="../../../PHP/admin/quiz/quiz_form.php" class="admin-btn">Novo Quiz</a>
            <a href="../../../PHP/admin/index.php" class="admin-btn">Voltar</a> 
            <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main>
        <table class="admin-anime-table">
    <thead>
        <tr>
            <th>Anime</th>
            <th>Tempodada</th>
            <th>Pergunta</th>
            <th>Resposta</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $q): ?>
        <tr>
            <td><?= htmlspecialchars($q['anime_nome']) ?></td>
            <td><?= htmlspecialchars($q['temporada']) ?></td>
            <td><?= htmlspecialchars($q['pergunta']) ?></td>
            <td class="destaque"><?= htmlspecialchars($q['resposta_correta']) ?></td>
            <td>
                <a href="../../../PHP/admin/quiz/quiz_form.php?id=<?= $q['id'] ?>" class="admin-btn">✏️ Editar</a>
                <a href="../../../PHP/admin/quiz/quiz_delete.php?id=<?= $q['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este quiz?')">🗑️ Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">Total: <?= count($quizzes) ?> quizzes cadastrados</td>
        </tr>
    </tfoot>
</table>
    </main>
</body>
</html>
