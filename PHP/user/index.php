<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/animes.php';
require __DIR__ . '/../shared/generos.php';

// Buscar dados
$topAnimes = buscarTopAnimes($pdo, 5);
$generos   = buscarTodosGeneros($pdo);
$lancamentos = buscarLancamentos($pdo, 20);
$estreias = buscarEstreiasTemporada($pdo);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Anime Space!</title> 
  <link rel="stylesheet" href="../../CSS/style.css" /> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body class="home">

  <button class="menu-toggle" aria-label="Abrir menu" onclick="toggleMenu()">☰</button>

  <nav class="menu-lateral" id="menuLateral" aria-label="Menu principal">
    <a href="../../PHP/user/stream.php">Catálogo</a>
    <a href="../../PHP/user/estreias_temporada.php">Estreias da Temporada</a>
    <a href="../../PHP/user/últimos_episodios.php">Lançamentos</a>
    <a href="../../PHP/user/noticias.php">Noticias</a>
    <a href="../../PHP/user/register.php">Cadastro</a>
  </nav>

  <a href="../../PHP/user/profile.php" class="perfil-btn" aria-label="Perfil do usuário" role="button" tabindex="0">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="24" height="24" style="vertical-align: middle;">
      <circle cx="12" cy="8" r="4" />
      <path d="M4 20c0-4 8-4 8-4s8 0 8 4v1H4v-1z" />
    </svg>
  </a>

  <a href="suporte.php" class="btn-suporte" aria-label="Suporte" role="button" tabindex="0">
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
      <path d="M12 2C6.48 2 2 6.03 2 11c0 2.38 1.04 4.52 2.72 6.08L4 22l5.18-2.29C10.02 20.57 11 20.78 12 20.78c5.52 0 10-4.03 10-9s-4.48-9-10-9z"/>
    </svg>
  </a>

  <header class="cabeca" role="banner">
    <img src="../../img/slogan2.png" alt="Slogan Anime Space" class="img-slogan" /> 
    <p><strong>O universo dos animes ao seu alcance</strong></p> 
  </header>

  <main>
    <section class="top-animes" aria-labelledby="top-animes-title">
      <h3 id="top-animes-title">Top 5 do site</h3> 
      <ol class="lista-top">
        <?php foreach ($topAnimes as $index => $anime): ?>
          <li>
            <a href="../../PHP/user/episodes.php?id=<?= $anime['id'] ?>">
              <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="img" />
            </a>
            <h4 class="destaque"><?= ($index + 1) ?> . <?= htmlspecialchars($anime['nome']) ?></h4>
            <div class="sinopse">
              <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img" />
              <p><?= htmlspecialchars($anime['descricao']) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
    </section>

    <section class="genres" aria-labelledby="genres-title">
      <h3 id="genres-title">Gêneros Populares</h3>
        <ul>
          <?php foreach ($generos as $genero): ?>
            <li class="<?= $genero['id_destaque'] == 1 ? 'destaque' : '' ?>">
               <a href="../../PHP/user/stream.php?generos=<?= urlencode($genero['nome']) ?>">
               <?= htmlspecialchars($genero['nome']) ?>
               </a>
            </li>
          <?php endforeach; ?>
        </ul>
    </section>

    <section class="lancamentos" aria-labelledby="lancamentos-title">
      <h3 id="lancamentos-title">Lançamentos Recentes</h3>
      <div class="grid-lancamentos">
        <?php foreach ($lancamentos as $anime): ?>
          <div class="card-anime">
            <a href="../../PHP/user/episodes.php?id=<?= $anime['id'] ?>">
              <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" 
                   alt="<?= htmlspecialchars($anime['nome']) ?>" class="capa-anime" />
              <p class="nome-anime"><?= htmlspecialchars($anime['nome']) ?></p>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="rodape" role="contentinfo">
    <p>
      O <strong>Animes Space</strong> é um portal dedicado a recomendar os melhores animes, separados por gênero, nota e estilo. Descubra novos títulos,<br />
      veja o que está em alta e mergulhe no mundo dos animes!
      <a href="../../HTML/sobre.html"><strong>Sobre o site</strong></a>
    </p>
  </footer>

  <script>
    function toggleMenu() {
      document.getElementById("menuLateral").classList.toggle("aberto");
    }
  </script>
</body>
</html>
