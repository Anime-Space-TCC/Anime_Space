<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title><?= $temporada['id'] ? "Editar" : "Nova" ?> Temporada</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
</head>
<body class="admin">
  <div class="admin-links">
    <h1><?= $temporada['id'] ? "Editar Temporada" : "Nova Temporada" ?></h1>
    <nav>
      <a href="admin_temporadas.php">⬅ Voltar</a>
    </nav>
  </div>

  <main>
    <form action="temporadas_save.php" method="post" class="admin-form">
      <input type="hidden" name="id" value="<?= htmlspecialchars($temporada['id']) ?>">

      <label>Anime:</label>
      <select name="anime_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($animes as $a): ?>
          <option value="<?= $a['id'] ?>" <?= $a['id']==$temporada['anime_id'] ? 'selected':'' ?>>
            <?= htmlspecialchars($a['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select><br>

      <label>Número da Temporada:</label>
      <input type="number" name="numero" value="<?= htmlspecialchars($temporada['numero']) ?>" required><br>

      <label>Nome:</label>
      <input type="text" name="nome" value="<?= htmlspecialchars($temporada['nome']) ?>" required><br>

      <label>Data de Lançamento:</label>
      <input type="date" name="data_lancamento" value="<?= htmlspecialchars($temporada['data_lancamento']) ?>"><br>

      <button type="submit" class="admin-btn">Salvar</button>
    </form>
  </main>
</body>
</html>
