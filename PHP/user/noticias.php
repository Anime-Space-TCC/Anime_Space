<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// ===== Buscar notícias =====
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Notícias populares (Top 5) =====
$topStmt = $pdo->query("SELECT * FROM noticias ORDER BY visualizacoes DESC LIMIT 5");
$topNoticias = $topStmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Últimas 3 para o slide =====
$slideStmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC LIMIT 3");
$slides = $slideStmt->fetchAll(PDO::FETCH_ASSOC);

// === PAGINAÇÃO ===
$porPagina = 4; // número de notícias por página
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $porPagina;

// Conta o total de notícias
$totalNoticias = $pdo->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
$totalPaginas = ceil($totalNoticias / $porPagina);

// Busca as notícias da página atual
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
  <title>Notícias de Animes</title>
  <link rel="stylesheet" href="../../CSS/noticias.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>

<body class="noticias-page">
<header>
  <nav>
    <a href="../../PHP/user/index.php" class="home-btn" aria-label="Página Inicial" role="button" tabindex="0">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
            <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
        </svg>
    </a>
  </nav>
</header>

<main>
  <!-- Lado esquerdo: área 1 e 2 -->
  <div class="sidebar-container">
    <!-- Área 1 - Cards de notícias -->
    <div class="noticias">
      <?php foreach ($noticias as $n): ?>
        <article class="noticia">
          <img src="../../img/<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>">
          <h2><?= htmlspecialchars($n['titulo']) ?></h2>
          <p><?= htmlspecialchars($n['resumo']) ?></p>
          <?php if (!empty($n['url_externa'])): ?>
            <a href="<?= htmlspecialchars($n['url_externa']) ?>" target="_blank">Leia mais</a>
          <?php else: ?>
            <a href="noticia.php?id=<?= $n['id'] ?>">Leia mais</a>
          <?php endif; ?>
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

    <!-- Área 2 - Mais Populares -->
    <div class="top-noticias">
      <h3>Mais Populares</h3>
      <?php foreach ($topNoticias as $t): ?>
        <div class="mini-noticia">
          <img src="../../img/<?= htmlspecialchars($t['imagem']) ?>" alt="<?= htmlspecialchars($t['titulo']) ?>">
          <p><?= htmlspecialchars($t['titulo']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Lado direito: área 3 e 4 -->
  <div class="conteudo-principal">
    <!-- Área 4 - Barra de pesquisa -->
    <div class="barra-pesquisa">
      <form action="buscar.php" method="get">
        <input type="text" name="q" placeholder="Buscar notícias...">
      </form>
    </div>

    <!-- Área 3 - Slide de notícias -->
    <div class="slideshow">
      <?php foreach ($slides as $i => $s): ?>
        <div class="slide <?= $i === 0 ? 'active' : '' ?>">
          <img src="../../img/<?= htmlspecialchars($s['imagem']) ?>" alt="<?= htmlspecialchars($s['titulo']) ?>">
          <div class="slide-text"><?= htmlspecialchars($s['titulo']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<footer>
  <p>&copy; 2025 Anime News Brasil</p>
</footer>

<script>
  // Troca automática dos slides
  let slideIndex = 0;
  const slides = document.querySelectorAll(".slide");

  setInterval(() => {
    slides[slideIndex].classList.remove("active");
    slideIndex = (slideIndex + 1) % slides.length;
    slides[slideIndex].classList.add("active");
  }, 4000);
</script>
</body>
</html>