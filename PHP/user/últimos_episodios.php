<?php
require __DIR__ . '/../shared/episodios.php';
require_once __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/acessos.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

$porPagina = 10;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$offset = ($pagina - 1) * $porPagina;

$episodios = getUltimosEpisodiosPaginados($porPagina, $offset);

// Como queremos apenas os 20 últimos no total:
$totalEpisodios = 20;
$totalPaginas = ceil($totalEpisodios / $porPagina);
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
  $current_page = 'lancamento';
  include __DIR__ . '/navbar.php';
  ?>
  <main class="page-content">
    <div class="layout-anuncios">
      <!-- Coluna da esquerda (anúncios) -->
      <aside class="ads-lateral-esquerda">
        <div class="ad-item"><img src="../../img/ads/propaganda7.jpg" alt="Anúncio 7"></div>
        <div class="ad-item"><img src="../../img/ads/propaganda8.jpg" alt="Anúncio 8"></div>
      </aside>
      <section class="ultimas">
        <header>
          <h1 class="titulo-pagina">Últimos Episódios Atualizados</h1>
        </header>
        <?php if ($episodios): ?>
          <ul class="episodios-lista">
            <?php foreach ($episodios as $ep): ?>
              <li>
                <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>"
                  alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
                <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong>
                Episódio <?= htmlspecialchars($ep['numero']) ?>:
                <?= htmlspecialchars($ep['titulo']) ?>
                <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>Nenhum episódio encontrado.</p>
        <?php endif; ?>

        <!-- Paginação -->
        <div class="paginacao">
          <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>">&laquo; Anterior</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="<?= $i === $pagina ? 'ativo' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>

          <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>">Próxima &raquo;</a>
          <?php endif; ?>
        </div>
    </section>
      </section>
      <!-- Coluna da direita (anúncios) -->
      <aside class="ads-lateral-direita">
        <div class="ad-item"><img src="../../img/ads/propaganda9.jpg" alt="Anúncio 9"></div>
        <div class="ad-item"><img src="../../img/ads/propaganda10.jpg" alt="Anúncio 10"></div>
      </aside>
    </div>
  </main>
  <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>