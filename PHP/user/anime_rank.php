<?php
require __DIR__ . '/../shared/conexao.php';

// Buscar os 10 animes com maior nota, pegando nome, capa e nota
$animes = $pdo->query("SELECT id, nome, capa, nota FROM animes ORDER BY nota DESC LIMIT 10")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Ranking de Animes - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style4.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="ranking">
    <header>
        <h1>Ranking de Animes</h1>
        <nav>
            <a href="../../HTML/home.html">Home</a>
            <a href="../../PHP/user/stream.php">Catálogo</a>
            <a href="../../PHP/user/login.php">Login</a>
        </nav>
    </header>

    <main class="ranking-container">
        <?php if ($animes): ?>
            <?php foreach ($animes as $pos => $anime): ?>
                <div class="anime-rank-item" data-rank="<?= $pos + 1 ?>">
                    <div class="anime-bola">
                        <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" loading="lazy" />
                        <span class="rank-pos"><?= $pos + 1 ?></span>
                    </div>
                    <div class="anime-info">
                        <h3><?= htmlspecialchars($anime['nome']) ?></h3>
                        <p>⭐ <?= number_format($anime['nota'], 1) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#ccc; text-align:center; width: 100%;">Nenhum anime cadastrado ainda.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 - Anime Space. <a href="../../HTML/sobre.html">Sobre</a></p>
    </footer>
</body>
</html>
