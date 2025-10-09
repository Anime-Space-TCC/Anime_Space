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

<?php
  $current_page = 'home'; 
  include __DIR__ . '/navbar.php'; 
?>
  <main class="page-content">
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


      <section>
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
      </section>
      <?php include __DIR__ . '/rodape.php'; ?>
  </main>

  <script>
    const buscaBtn = document.getElementById('buscaBtn');
    const buscaContainer = buscaBtn.parentElement;

    buscaBtn.addEventListener('click', () => {
      // Alterna a classe 'active' ao clicar
      buscaContainer.classList.toggle('active');

      // Foca no input quando aparecer
      if (buscaContainer.classList.contains('active')) {
        buscaContainer.querySelector('input').focus();
      }
    });

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
  </script>
</body>
</html>
