<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/animes.php';
require __DIR__ . '/../shared/generos.php';

// Buscar dados
$topAnimes = buscarTopAnimes($pdo, 5);
$generos   = buscarTodosGeneros($pdo);
$lancamentos = buscarLancamentos($pdo, 20);
$estreias = buscarEstreiasTemporada($pdo);
$busca = $_GET['busca'] ?? '';
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
    <a href="../../PHP/user/quiz.php">Quiz Animes</a>
    <a href="../../PHP/user/estreias_temporada.php">Estreias da Temporada</a>
    <a href="../../PHP/user/últimos_episodios.php">Lançamentos</a>
    <a href="../../PHP/user/noticias.php">Noticias</a>
    <a href="../../PHP/user/vendas.php">Lojinha</a>
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

  <!-- Botão de Busca -->
  <div class="busca-container">
    <button class="busca-btn" aria-label="Buscar" type="button">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
        <path d="M10 2a8 8 0 105.29 14.29l4.7 4.7a1 1 0 001.42-1.42l-4.7-4.7A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
      </svg>
    </button>

    <!-- Mini label de busca -->
    <form class="busca-form <?= !empty($_GET['busca']) ? 'show' : '' ?>" action="stream.php" method="GET">
      <input type="text" name="busca" placeholder="Digite o anime..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>" />
      <button type="submit">Ir</button>
    </form>
  </div>


  <header class="cabeca" role="banner">
  <div class="slogan">
    <img src="../../img/slogan2.png" alt="Slogan Anime Space" class="img-slogan" /> 
    <p><strong>Infinito é o universo dos animes</strong></p> 
  </div>

  <div class="slideshow-container">
    <?php foreach ($topAnimes as $index => $anime): ?>
      <div class="slide">
        <a href="../../PHP/user/episodes.php?id=<?= $anime['id'] ?>">
          <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" 
               alt="<?= htmlspecialchars($anime['nome']) ?>" class="slide-img" />
        </a>
        <h4 class="destaque"><?= ($index + 1) ?> . <?= htmlspecialchars($anime['nome']) ?></h4>
      </div>
    <?php endforeach; ?>
    <!-- botões -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
</header>


  <main>
    <section class="lancamentos" aria-labelledby="lancamentos-title">
      <h3 id="lancamentos-title">Lançamentos</h3>
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

  <footer class="rodape" role="contentinfo">
    <p>
      O <strong>Animes Space</strong> é um portal dedicado a recomendar os melhores animes, separados por gênero, nota e estilo. Descubra novos títulos,<br />
      veja o que está em alta e mergulhe no mundo dos animes!
      <a href="../../HTML/sobre.html"><strong>Sobre o site</strong></a>
    </p>
  </footer>

  <script>
    // Menu lateral
    function toggleMenu() {
      document.getElementById("menuLateral").classList.toggle("aberto");
    }

    // Slideshow
    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let slides = document.getElementsByClassName("slide");
      for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
      }
      slideIndex++;
      if (slideIndex > slides.length) {slideIndex = 1}    
      slides[slideIndex-1].style.display = "block";  
      setTimeout(showSlides, 10000); 
    }
    // Controle manual
    function plusSlides(n) {
      let slides = document.getElementsByClassName("slide");
      slideIndex += n;

      if (slideIndex > slides.length) { slideIndex = 1 }
      if (slideIndex < 1) { slideIndex = slides.length }

      for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }

      slides[slideIndex-1].style.display = "block";
    }

    // Busca interativa
    const buscaBtn = document.querySelector('.busca-btn');
    const buscaForm = document.querySelector('.busca-form');

    buscaBtn.addEventListener('click', () => {
      buscaForm.classList.toggle('show'); // alterna visibilidade
      if(buscaForm.classList.contains('show')) {
        buscaForm.querySelector('input').focus();
      }
    });
  </script>
</body>
</html>
