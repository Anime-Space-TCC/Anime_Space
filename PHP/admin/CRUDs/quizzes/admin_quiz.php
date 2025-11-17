<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../user/login.php');
    exit();
}

// Verifica se há pesquisa
$busca = $_GET['buscarQuiz'] ?? '';

if (!empty($busca)) {
    $stmt = $pdo->prepare("
        SELECT q.*, a.nome AS anime_nome
        FROM quizzes q
        INNER JOIN animes a ON q.anime_id = a.id
        WHERE q.titulo LIKE :busca1 OR a.nome LIKE :busca2
        ORDER BY q.data_criacao DESC
    ");
    $stmt->execute([
        ':busca1' => "%$busca%",
        ':busca2' => "%$busca%"
    ]);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    $stmt = $pdo->prepare("
        SELECT q.*, a.nome AS anime_nome
        FROM quizzes q
        INNER JOIN animes a ON q.anime_id = a.id
        ORDER BY q.data_criacao DESC
    ");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Quizzes</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin-cruds">
  <div class="admin-links">
    <h1>Gerenciar Quizzes</h1>
    <form method="GET" class="admin-busca">
      <input type="text" name="buscarQuiz" placeholder="Buscar quiz..." value="<?= htmlspecialchars($_GET['buscarQuiz'] ?? '') ?>">
      <button type="submit">Buscar</button>
      <?php if (!empty($_GET['buscarQuiz'])): ?>
        <a href="admin_quiz.php" class="limpar-btn">Limpar</a>
      <?php endif; ?>
    </form>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a>
      <a href="../../../../PHP/admin/CRUDs/quizzes/quiz_form.php" class="admin-btn">Novo Quiz</a>
      <a href="../../../../PHP/admin/index.php" class="admin-btn">Voltar</a>
    </nav>
  </div>

  <main>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Capa</th>
          <th>Título</th>
          <th>Anime</th>
          <th>Nível Mínimo</th>
          <th>Perguntas</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($quizzes as $q): ?>
          <tr>
            <td>
              <?php if (!empty($q['capa'])): ?>
                <img src="../../../../img/<?= htmlspecialchars($q['capa']) ?>" alt="<?= htmlspecialchars($q['titulo']) ?>" width="100">
              <?php else: ?>
                <span>Sem capa</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($q['titulo']) ?></td>
            <td><?= htmlspecialchars($q['anime_nome']) ?></td>
            <td><?= (int)$q['nivel_minimo'] ?></td>
            <td><?= (int)$q['total_perguntas'] ?></td>
            <td><?= $q['ativo'] ? 'Ativo' : 'Inativo' ?></td>
            <td>
              <a href="../../../../PHP/admin/CRUDs/quizzes/quiz_form.php?id=<?= $q['id'] ?>" class="admin-btn">Editar</a>
              <a href="../../../../PHP/admin/CRUDs/quizzes/perguntas.php?quiz_id=<?= $q['id'] ?>" class="admin-btn">Perguntas</a>
              <a href="../../../../PHP/admin/CRUDs/quizzes/quiz_delete.php?id=<?= $q['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este quiz?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7">TOTAL: <?= count($quizzes) ?> quizzes cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
