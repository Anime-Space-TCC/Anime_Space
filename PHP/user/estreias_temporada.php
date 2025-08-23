<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/animes.php';

// Busca estreias
$estreias = buscarEstreiasTemporada($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Estreias da Temporada</title> 
  <link rel="stylesheet" href="../../CSS/style.css" /> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Estreias da Temporada</h1>
    <nav>
      <a href="../../PHP/user/stream.php">Catálogo</a> |
      <a href="../../PHP/user/index.php" class="home-btn" aria-label="Página Inicial" role="button" tabindex="0">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
          <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
        </svg>
      </a> |
      <a href="../../PHP/user/últimos_episodios.php">Lançamentos</a> 
    </nav>
  </header>

  <section class="temporada">
    <?php if ($estreias): ?>
      <ul class="episodios-lista">
        <?php foreach ($estreias as $ep): ?>
          <li>
            <?php if (!empty($ep['anime_capa'])): ?>
              <img src="../../img/<?= htmlspecialchars($ep['anime_capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" style="border-radius:6px;" />
            <?php else: ?>
              <span style="display:inline-block;width:100px;height:140px;background:#ccc;text-align:center;line-height:140px;border-radius:6px;">Sem Capa</span>
            <?php endif; ?>

            <div style="display:inline-block; margin-left:10px; vertical-align:top;">
              <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong><br>
              Temporada <?= $ep['temporada'] ?>, Episódio <?= $ep['numero'] ?>: <?= htmlspecialchars($ep['titulo']) ?><br>
              (Estreia em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)<br>
            </div>
            <a href="../../PHP/user/episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
          <hr>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Nenhuma estreia para esta temporada.</p>
    <?php endif; ?>
  </section>
</body>
</html>
