<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Busca todas as notícias
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notícias de Animes</title>
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../img/slogan3.png" type="image/png" />
</head>
<body class="noticias-page">
<header>
  <nav>
    <a href="../../PHP/user/index.php" class="home-btn">Home</a>
    <a href="../../PHP/user/profile.php">Perfil</a>
  </nav>
</header>

<main>
  <h1 style="text-align:center; margin:20px 0;">Últimas Notícias</h1>

  <div class="noticias">
    <?php foreach ($noticias as $n): ?>
      <article class="noticia">
        <img src="../../img/<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>">
        <h2><?= htmlspecialchars($n['titulo']) ?></h2>
        <p><?= htmlspecialchars($n['resumo']) ?></p>

        <?php if (!empty($n['url_externa'])): ?>
          <!-- Notícia externa -->
          <a href="<?= htmlspecialchars($n['url_externa']) ?>" target="_blank">Leia mais</a>
        <?php else: ?>
          <!-- Notícia interna -->
          <a href="noticia.php?id=<?= $n['id'] ?>">Leia mais</a>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</main>

<footer>
  <p>&copy; 2025 Anime News Brasil</p>
</footer>
</body>
</html>
