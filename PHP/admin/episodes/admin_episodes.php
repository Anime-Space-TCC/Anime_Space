<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Episódios</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Episódios</h1>
    <nav>
      <a href="../../../PHP/user/index.php">Home</a> 
      <a href="episodes_form.php" class="admin-btn">Novo Episódio</a> 
      <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Anime</th>
          <th>Temporada</th>
          <th>Episódio</th>
          <th>Título</th>
          <th>Duração</th>
          <th>Lançamento</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($episodes as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['anime_nome']) ?></td>
            <td><?= htmlspecialchars($e['temporada_numero'] . " - " . $e['temporada_nome']) ?></td>
            <td><?= htmlspecialchars($e['numero']) ?></td>
            <td><?= htmlspecialchars($e['titulo']) ?></td>
            <td><?= htmlspecialchars($e['duracao']) ?></td>
            <td><?= htmlspecialchars($e['data_lancamento']) ?></td>
            <td>
              <a href="episodes_form.php?id=<?= $e['id'] ?>" class="admin-btn">✏️ Editar</a>
              <a href="episodes_delete.php?id=<?= $e['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este episódio?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7">Total: <?= count($episodes) ?> episódios cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
