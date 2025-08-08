<?php
require __DIR__ . '/../shared/conexao.php'; // Importa a conexão com o banco de dados

// Busca os 10 animes com maior nota, selecionando id, nome, capa e nota
$animes = $pdo->query("SELECT id, nome, capa, nota FROM animes ORDER BY nota DESC LIMIT 10")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" /> 
    <title>Ranking de Animes - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css"> 
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
        <?php if ($animes): ?> <!-- Verifica se existem animes no resultado -->
            <?php foreach ($animes as $pos => $anime): ?> <!-- Itera sobre os animes -->
                <div class="anime-rank-item" data-rank="<?= $pos + 1 ?>"> <!-- Container do item com posição -->
                    <div class="anime-bola">
                        <!-- Imagem da capa do anime -->
                        <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" loading="lazy" />
                        <!-- Exibe a posição do anime no ranking -->
                        <span class="rank-pos"><?= $pos + 1 ?></span>
                    </div>
                    <div class="anime-info">
                        <!-- Nome do anime -->
                        <h3><?= htmlspecialchars($anime['nome']) ?></h3>
                        <!-- Nota formatada com uma casa decimal -->
                        <p>⭐ <?= number_format($anime['nota'], 1) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?> <!-- Caso não existam animes cadastrados -->
            <p style="color:#ccc; text-align:center; width: 100%;">Nenhum anime cadastrado ainda.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 - Anime Space. <a href="../../HTML/sobre.html">Sobre</a></p> <!-- Rodapé com link sobre -->
    </footer>
</body>
</html>
