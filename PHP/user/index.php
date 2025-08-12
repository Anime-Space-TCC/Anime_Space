<?php
require __DIR__ . '/../shared/conexao.php';

// Buscar Top 5 animes ordenados pela nota, do maior para o menor
$stmt = $pdo->query("SELECT id, nome, capa, nota, descricao FROM animes ORDER BY nota DESC LIMIT 5");
$topAnimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar todos os gêneros
$stmt = $pdo->query("SELECT nome, id_destaque FROM generos ORDER BY nome");
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

  <!-- Botão para abrir/fechar o menu lateral -->
  <button class="menu-toggle" aria-label="Abrir menu" onclick="toggleMenu()">☰</button>

  <!-- Menu lateral principal -->
  <nav class="menu-lateral" id="menuLateral" aria-label="Menu principal">
    <a href="../../PHP/user/stream.php">Catálogo</a>
    <a href="../../PHP/user/estreias_temporada.php">Estreias da Temporada</a>
    <a href="../../PHP/user/últimos_episodios.php">Lançamentos</a>
    <a href="../../PHP/user/register.php">Cadastro</a>
  </nav>

  <!-- Botão para acesso ao perfil do usuário -->
  <a href="../../PHP/user/profile.php" class="perfil-btn" aria-label="Perfil do usuário" role="button" tabindex="0">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="24" height="24" style="vertical-align: middle;">
      <circle cx="12" cy="8" r="4" />
      <path d="M4 20c0-4 8-4 8-4s8 0 8 4v1H4v-1z" />
    </svg>
  </a>

  <!-- Cabeçalho principal da página -->
  <header class="cabeca" role="banner">
    <img src="../../img/slogan2.png" alt="Slogan Anime Space" class="img-slogan" /> 
    <p><strong>O universo dos animes ao seu alcance</strong></p> 
  </header>

  <main>
    <!-- Seção com os 5 animes principais do mês -->
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

    <!-- Seção com gêneros populares -->
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
  </main>

  <!-- Rodapé da página -->
  <footer class="rodape" role="contentinfo">
    <p>
      O <strong>Animes Space</strong> é um portal dedicado a recomendar os melhores animes, separados por gênero, nota e estilo. Descubra novos títulos,<br />
      veja o que está em alta e mergulhe no mundo dos animes!
      <a href="../../HTML/sobre.html"><strong>Sobre o site</strong></a>
    </p>
  </footer>

  <!-- Script para alternar a classe do menu lateral -->
  <script>
    function toggleMenu() {
      document.getElementById("menuLateral").classList.toggle("aberto");
    }
  </script>
</body>
</html>
