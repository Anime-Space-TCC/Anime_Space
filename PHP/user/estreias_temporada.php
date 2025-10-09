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
    $current_page = 'busca'; 
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
  </main>
  <?php include __DIR__ . '/rodape.php'; ?>
</body>
</html>
