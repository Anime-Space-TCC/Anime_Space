<?php
require __DIR__ . '/../shared/episodios.php';

// Busca os 20 episódios mais recentes
$episodios = getUltimosEpisodios(20);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Últimos Episódios Atualizados</title> 
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Últimos Episódios Atualizados</h1>
    <nav>
      <a href="../../PHP/user/stream.php">Catálogo</a> |
      <a href="../../PHP/user/index.php" class="home-btn" aria-label="Página Inicial" role="button" tabindex="0">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
          <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
        </svg>
      </a> |
      <a href="../../PHP/user/estreias_temporada.php">Estreias da Temporada</a> 
    </nav>
  </header>

  <section class="ultimas">
    <?php if ($episodios): ?>
      <ul class="episodios-lista">
        <?php foreach ($episodios as $ep): ?>
          <li>
            <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>" 
                 alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
            <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong> 
            Temporada <?= htmlspecialchars($ep['temporada']) ?>, 
            Episódio <?= htmlspecialchars($ep['numero']) ?>: 
            <?= htmlspecialchars($ep['titulo']) ?> 
            (Lançado em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)
            <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Nenhum episódio encontrado.</p>
    <?php endif; ?>
  </section>
</body>
</html>
