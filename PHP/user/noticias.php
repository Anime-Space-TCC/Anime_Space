<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/acessos.php';
require __DIR__ . '/../shared/noticias.php';

verificarLogin();

// Buscar Top 5 Populares
$topNoticias = buscarNoticiasPopulares($pdo, 5);

// Buscar todas
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Últimas 3 (slide)
$slides = array_slice($noticias, 0, 3);

// Paginação
$porPagina = 6;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $porPagina;
$totalNoticias = $pdo->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
$totalPaginas = ceil($totalNoticias / $porPagina);

// Buscar noticias para a pagina atual
$stmt = $pdo->prepare("SELECT * FROM noticias ORDER BY data_publicacao DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Comunidade Otaku - Notícias e História dos Animes</title>
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png" />
</head>
<body class="comunidade-page">
  
  <?php
    $current_page = 'noticias';
    include __DIR__ . '/navbar.php';
  ?>

  <main class="comunidade-container">
    <!-- 📰 CARROSSEL DE DESTAQUE -->
    <section class="carrossel-noticias">
      <div class="carrossel-slides">
        <?php foreach ($slides as $i => $s): ?>
          <div class="carrossel-slide <?= $i === 0 ? 'ativo' : '' ?>">
            <img src="../../img/<?= htmlspecialchars($s['imagem']) ?>" alt="<?= htmlspecialchars($s['titulo']) ?>">
            <div class="carrossel-info">
              <h2><?= htmlspecialchars($s['titulo']) ?></h2>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- 📜 HISTÓRIA DOS ANIMES -->
    <section class="historia-section">
      <h2>História dos Animes</h2>
      <p>Desde as primeiras animações japonesas no início do século XX, 
        os animes evoluíram de curtas experimentais para obras mundialmente reconhecidas. 
        Séries como *Astro Boy (1963)* marcaram o início da indústria moderna, e décadas 
        seguintes trouxeram marcos como *Akira (1988)* e *Neon Genesis Evangelion (1995)*. 
        Hoje, os animes transcendem fronteiras e inspiram comunidades vibrantes ao redor do mundo.</p>
    </section>

    <!-- 🗞️ NOTÍCIAS DA COMUNIDADE -->
    <section class="noticias-section">
      <h2>Notícias Recentes</h2>
      <div class="noticias-grid">
        <?php foreach ($noticias as $n): ?>
          <article class="noticia-card">
            <img src="../../img/<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>">
            <div class="noticia-info">
              <h3><?= htmlspecialchars($n['titulo']) ?></h3>
              <p><?= htmlspecialchars($n['resumo']) ?></p>
              <a href="../../PHP/shared/noticias_redirect.php?id=<?= $n['id'] ?>" target="_blank" class="btn-leia">Leia mais</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

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

    <div class="layout-populares-anuncios">
  
      <!-- Lateral esquerda: anúncios -->
      <div class="ads-lateral">
        <div class="ad-item">
          <img src="../../img/ads/propaganda7.jpg" alt="Propaganda 7">
        </div>
        <div class="ad-item">
          <img src="../../img/ads/propaganda8.jpg" alt="Propaganda 8">
        </div>
      </div>

      <div class="populares-contato">
        <!-- 🔥 TOP 5 POPULARES -->
        <aside class="populares-section">
          <h2>🔥 Mais Populares</h2>
          <?php foreach ($topNoticias as $t): ?>
            <div class="mini-noticia">
              <img src="../../img/<?= htmlspecialchars($t['imagem']) ?>" alt="<?= htmlspecialchars($t['titulo']) ?>">
              <p><?= htmlspecialchars($t['titulo']) ?></p>
            </div>
          <?php endforeach; ?>
        </aside>

        <!-- 💬 CONTATOS / COMUNIDADE -->
        <section class="contato-section">
          <h2>Conecte-se com a Comunidade</h2>
          <p>Participe dos nossos grupos para trocar ideias, memes, notícias e indicações!</p>
          <div class="contato-links">
            <a href="#" class="contato-btn whatsapp">
              <img src="../../img/icons/wat.jpg" alt=""> Grupo do WhatsApp</a>
            <a href="#" class="contato-btn discord">
              <img src="../../img/icons/discord.jpg" alt=""> Servidor no Discord</a>
          </div>
        </section>

      </div>

      <!-- Lateral direita: anúncios -->
      <div class="ads-lateral">
        <div class="ad-item">
          <img src="../../img/ads/propaganda9.jpg" alt="Propaganda 9">
        </div>
        <div class="ad-item">
          <img src="../../img/ads/propaganda10.jpg" alt="Propaganda 10">
        </div>
      </div>

     </div>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>

  <script src="../../JS/noticias.js"></script>
  
</body>
</html>
