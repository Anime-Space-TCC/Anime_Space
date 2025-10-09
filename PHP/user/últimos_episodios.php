<?php
require __DIR__ . '/../shared/episodios.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Busca os 20 episódios mais recentes
$episodios = getUltimosEpisodios(20);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Lançamentos</title> 
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body>
  <?php
    $current_page = 'busca'; 
    include __DIR__ . '/navbar.php'; 
  ?>
  <main class="page-content">
    <header>
      <h1 class="titulo-pagina">Últimos Episódios Atualizados</h1>
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
  </main>
  <?php include __DIR__ . '/rodape.php'; ?>
</body>
</html>
