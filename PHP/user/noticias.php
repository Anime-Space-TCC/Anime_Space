<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

verificarLogin();

// Notícias
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Top 5
$topStmt = $pdo->query("SELECT * FROM noticias ORDER BY visualizacoes DESC LIMIT 5");
$topNoticias = $topStmt->fetchAll(PDO::FETCH_ASSOC);

// Últimas 3 (slide)
$slideStmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC LIMIT 3");
$slides = $slideStmt->fetchAll(PDO::FETCH_ASSOC);

// Paginação
$porPagina = 4;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $porPagina;
$totalNoticias = $pdo->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
$totalPaginas = ceil($totalNoticias / $porPagina);

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
      <h2>📜 História dos Animes</h2>
      <p>Desde as primeiras animações japonesas no início do século XX, 
        os animes evoluíram de curtas experimentais para obras mundialmente reconhecidas. 
        Séries como *Astro Boy (1963)* marcaram o início da indústria moderna, e décadas 
        seguintes trouxeram marcos como *Akira (1988)* e *Neon Genesis Evangelion (1995)*. 
        Hoje, os animes transcendem fronteiras e inspiram comunidades vibrantes ao redor do mundo.</p>
    </section>

    <!-- 🗞️ NOTÍCIAS DA COMUNIDADE -->
    <section class="noticias-section">
      <h2>🗞️ Notícias Recentes</h2>
      <div class="noticias-grid">
        <?php foreach ($noticias as $n): ?>
          <article class="noticia-card">
            <img src="../../img/<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>">
            <div class="noticia-info">
              <h3><?= htmlspecialchars($n['titulo']) ?></h3>
              <p><?= htmlspecialchars($n['resumo']) ?></p>
              <a href="<?= !empty($n['url_externa']) ? htmlspecialchars($n['url_externa']) : 'noticia.php?id=' . $n['id'] ?>" target="_blank" class="btn-leia">Leia mais</a>
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
      <h2>💬 Conecte-se com a Comunidade</h2>
      <p>Participe dos nossos grupos para trocar ideias, memes, notícias e indicações!</p>
      <div class="contato-links">
        <a href="#" class="contato-btn whatsapp">💚 Grupo do WhatsApp</a>
        <a href="#" class="contato-btn discord">💜 Servidor no Discord</a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>

  <script src="../../JS/noticias.js"></script>
  
</body>
</html>
