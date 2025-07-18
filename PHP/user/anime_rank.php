<?php
require __DIR__ . '/../shared/conexao.php';

$animes = $pdo->query("SELECT id, nome, capa, nota FROM animes ORDER BY nota DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Ranking de Animes - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css" />
    <link rel="icon" href="../../img/slogan3.png" type="image/png" />
</head>
<body class="ranking" id="topo">
    <header class="links">
        <h1>Ranking de Animes</h1>
        <nav>
            <a href="../../HTML/home.html">Home</a>
            <a href="../../PHP/user/stream.php">Streaming</a>
            <a href="../../PHP/user/login.php">Login</a>
        </nav>
    </header>

    <main class="anime-catalogo">
    <?php if ($animes): ?>
        <?php foreach ($animes as $anime): ?>
            <article class="anime-item">
                <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img" loading="lazy" />
                <div class="info">
                    <h3><?= htmlspecialchars($anime['nome']) ?></h3>
                    <p class="nota-destaque">⭐ <?= number_format($anime['nota'], 1) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #ccc;">Nenhum anime cadastrado ainda.</p>
    <?php endif; ?>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 - Anime Space. <a href="../../HTML/sobre.html">Sobre</a></p>
    </footer>
</body>
</html>
