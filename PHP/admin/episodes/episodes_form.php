<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title><?= $episode['id'] ? "Editar" : "Novo" ?> Episódio</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
</head>
<body class="admin">
  <div class="admin-links">
    <h1><?= $episode['id'] ? "Editar Episódio" : "Novo Episódio" ?></h1>
    <nav>
      <a href="admin_episodes.php">⬅ Voltar</a>
    </nav>
  </div>

  <main>
    <form action="episodes_save.php" method="post" class="admin-form">
      <input type="hidden" name="id" value="<?= htmlspecialchars($episode['id']) ?>">

      <label>Temporada:</label>
      <select name="temporada_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($temporadas as $t): ?>
          <option value="<?= $t['id'] ?>" <?= $t['id']==$episode['temporada_id'] ? 'selected':'' ?>>
            <?= htmlspecialchars($t['anime_nome'] . " - Temp " . $t['numero'] . " (" . $t['nome'] . ")") ?>
          </option>
        <?php endforeach; ?>
      </select><br>

      <label>Número do Episódio:</label>
      <input type="number" name="numero" value="<?= htmlspecialchars($episode['numero']) ?>" required><br>

      <label>Título:</label>
      <input type="text" name="titulo" value="<?= htmlspecialchars($episode['titulo']) ?>" required><br>

      <label>Duração:</label>
      <input type="text" name="duracao" value="<?= htmlspecialchars($episode['duracao']) ?>"><br>

      <label>Data de Lançamento:</label>
      <input type="date" name="data_lancamento" value="<?= htmlspecialchars($episode['data_lancamento']) ?>"><br>

      <button type="submit" class="admin-btn">Salvar</button>
    </form>
  </main>
</body>
</html>
