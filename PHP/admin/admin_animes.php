<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

// Verifica se é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../HTML/login.html');
    exit();
}

$animes = $pdo->query("SELECT * FROM animes ORDER BY nota DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Animes</title>
  <link rel="stylesheet" href="../CSS/style.css" />
</head>
<body class="recomendacao">
  <div class="links">
    <h1>Gerenciar Animes</h1>
    <nav>
      <a href="../HTML/home.html">Home</a>
      <a href="anime_form.php">Novo Anime</a>
      <a href="logout.php">Sair</a>
    </nav>
  </div>
  <main>
    <table class="anime-table">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Gêneros</th>
          <th>Nota</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($animes as $a): ?>
          <tr>
            <td><img src="../img/<?= htmlspecialchars($a['imagem']) ?>" alt="<?= htmlspecialchars($a['nome']) ?>" width="100"></td>
            <td><?= htmlspecialchars($a['nome']) ?></td>
            <td><?= htmlspecialchars($a['generos']) ?></td>
            <td class="destaque"><?= number_format($a['nota'], 1) ?></td>
            <td>
              <a href="anime_form.php?id=<?= $a['id'] ?>">✏️ Editar</a> |
              <a href="anime_delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Excluir este anime?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">Total: <?= count($animes) ?> animes cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
