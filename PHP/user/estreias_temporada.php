<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/animes.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

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
  <?php
    $current_page = 'temporada'; 
    include __DIR__ . '/navbar.php'; 
  ?>
  <main class="page-content">
    <header>
      <h1 class="titulo-pagina">Estreias da Temporada</h1>
    </header>
    <section class="temporada">
      <?php if ($estreias): ?>
        <ul class="episodios-lista">
          <?php foreach ($estreias as $ep): ?>
            <li>
              <?php if (!empty($ep['anime_capa'])): ?>
                <img src="../../img/<?= htmlspecialchars($ep['anime_capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" style="border-radius:6px;" />
              <?php else: ?>
                <span>Sem Capa</span>
              <?php endif; ?>
              <div>
                <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong>: Temporada <?= $ep['temporada'] ?>
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
  </main>
  <?php include __DIR__ . '/rodape.php'; ?>
</body>
</html>
