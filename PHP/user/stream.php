<?php
require __DIR__ . '/../shared/conexao.php';

$animes = $pdo->query("SELECT id, nome, genero, imagem, ano FROM animes ORDER BY nome ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Streaming de Animes</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="streaming" id="topo">

  <header class="links">
    <h1>Animes Disponíveis</h1>
    <nav>
      <a href="../../HTML/home.html">Home</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <!-- Barra de busca + Filtros na mesma linha -->
  <section class="busca-filtros">
    <div class="barra-pesquisa">
      <input type="text" id="searchInput" placeholder="Buscar anime por nome ou gênero...">
    </div>
    <div class="filtros">
      <select id="generoSelect">
        <option value="">Gênero</option>
        <option value="Ação">Ação</option>
        <option value="Comédia">Comédia</option>
        <option value="Romance">Romance</option>
        <option value="Terror">Terror</option>
      </select>
      <select id="anoSelect">
        <option value="">Ano</option>
        <option value="2025">2025</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
      </select>
      <button id="limparFiltros">Limpar</button>
    </div>
  </section>

  <main class="anime-catalogo">
    <?php if ($animes): ?>
      <?php foreach ($animes as $anime): ?>
        <article class="anime-item" data-genero="<?= strtolower($anime['genero']) ?>" data-ano="<?= $anime['ano'] ?>">
          <img src="../../img/<?= htmlspecialchars($anime['imagem']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img">
          <div class="info">
            <h3><?= htmlspecialchars($anime['nome']) ?></h3>
            <p>Gêneros: <?= htmlspecialchars($anime['genero']) ?></p>
            <p>Ano: <?= htmlspecialchars($anime['ano']) ?></p>
            <a href="episodes.php?id=<?= $anime['id'] ?>">▶️ Ver Episódios</a>
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

  <script>
    const searchInput = document.getElementById("searchInput");
    const generoSelect = document.getElementById("generoSelect");
    const anoSelect = document.getElementById("anoSelect");
    const limparBtn = document.getElementById("limparFiltros");

    function filtrar() {
      const texto = searchInput.value.toLowerCase();
      const genero = generoSelect.value.toLowerCase();
      const ano = anoSelect.value;

      document.querySelectorAll(".anime-item").forEach(item => {
        const itemTexto = item.textContent.toLowerCase();
        const itemGenero = item.dataset.genero;
        const itemAno = item.dataset.ano;

        const nomeOuGeneroCombina = itemTexto.includes(texto);
        const generoCombina = genero === "" || itemGenero.includes(genero);
        const anoCombina = ano === "" || itemAno === ano;

        item.style.display = (nomeOuGeneroCombina && generoCombina && anoCombina) ? "flex" : "none";
      });
    }

    searchInput.addEventListener("input", filtrar);
    generoSelect.addEventListener("change", filtrar);
    anoSelect.addEventListener("change", filtrar);
    limparBtn.addEventListener("click", () => {
      searchInput.value = "";
      generoSelect.value = "";
      anoSelect.value = "";
      filtrar();
    });
  </script>

</body>
</html>
