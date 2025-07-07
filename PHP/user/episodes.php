<?php
require __DIR__ . '/../shared/conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Anime não encontrado.";
    exit;
}

$anime = $pdo->prepare("SELECT nome FROM animes WHERE id = ?");
$anime->execute([$id]);
$animeInfo = $anime->fetch();

if (!$animeInfo) {
    echo "Anime não encontrado.";
    exit;
}

$episodios = $pdo->prepare("SELECT * FROM episodios WHERE anime_id = ? ORDER BY numero ASC");
$episodios->execute([$id]);
$lista = $episodios->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="streaming">
  <header class="links">
    <h1><?= htmlspecialchars($animeInfo['nome']) ?> - Episódios</h1>
    <nav>
      <a href="../../HTML/home.html">Home</a>
      <a href="../../PHP/stream.php">Voltar para Streaming</a>
    </nav>
  </header>

  <main>
    <?php if ($lista): ?>
      <ul class="episodios-lista">
        <?php foreach ($lista as $ep): ?>
          <li>
            <strong>Episódio <?= htmlspecialchars($ep['numero']) ?>:</strong>
            <?= htmlspecialchars($ep['titulo']) ?> <br>
            <a href="<?= htmlspecialchars($ep['link']) ?>" target="_blank" rel="noopener noreferrer">Assistir</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Nenhum episódio disponível para este anime.</p>
    <?php endif; ?>
  </main>
</body>
</html>
