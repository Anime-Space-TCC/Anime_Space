<?php
require __DIR__ . '/../shared/conexao.php';

// Captura o gênero via GET
$filtroGenero = isset($_GET['generos']) ? $_GET['generos'] : '';

// Busca os gêneros únicos da tabela generos
$generos = $pdo->query("SELECT nome FROM generos ORDER BY nome ASC")->fetchAll(PDO::FETCH_COLUMN);

// Busca os anos únicos da tabela ano (corrigido aqui)
$anos = $pdo->query("SELECT valor FROM ano ORDER BY valor DESC")->fetchAll(PDO::FETCH_COLUMN);

// Consulta dos animes com filtro por gênero, se fornecido
if ($filtroGenero) {
  // Consulta animes que possuem o gênero filtrado, usando JOIN
  $stmt = $pdo->prepare("
    SELECT DISTINCT a.id, a.nome, a.capa, a.ano, a.nota,
      GROUP_CONCAT(g.nome SEPARATOR ', ') AS generos
    FROM animes a
    INNER JOIN anime_generos ag ON a.id = ag.anime_id
    INNER JOIN generos g ON ag.genero_id = g.id
    WHERE g.nome = :genero
    GROUP BY a.id
    ORDER BY a.nome ASC
  ");
  $stmt->bindValue(':genero', $filtroGenero);
  $stmt->execute();
  $animes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // Consulta todos os animes com seus gêneros concatenados
  $stmt = $pdo->query("
    SELECT a.id, a.nome, a.capa, a.ano, a.nota,
      GROUP_CONCAT(g.nome SEPARATOR ', ') AS generos
    FROM animes a
    LEFT JOIN anime_generos ag ON a.id = ag.anime_id
    LEFT JOIN generos g ON ag.genero_id = g.id
    GROUP BY a.id
    ORDER BY a.nome ASC
  ");
  $animes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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

  <section class="busca-filtros">
    <div class="barra-pesquisa">
      <input type="text" id="searchInput" placeholder="Buscar anime por nome ou gênero...">
    </div>
    <div class="filtros">
      <select id="generoSelect" name="generos">
        <option value="">Gênero</option>
        <?php foreach ($generos as $genero): ?>
          <option value="<?= htmlspecialchars($genero) ?>" <?= $filtroGenero === $genero ? 'selected' : '' ?>>
            <?= htmlspecialchars($genero) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select id="anoSelect" name="ano">
        <option value="">Ano</option>
        <?php foreach ($anos as $ano): ?>
          <option value="<?= htmlspecialchars($ano) ?>"><?= htmlspecialchars($ano) ?></option>
        <?php endforeach; ?>
      </select>
      <button id="limparFiltros">Limpar</button>
    </div>
  </section>

  <main class="anime-catalogo">
    <?php if ($animes): ?>
      <?php foreach ($animes as $anime): ?>
        <article class="anime-item" data-genero="<?= strtolower($anime['generos']) ?>" data-ano="<?= $anime['ano'] ?>">
          <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img">
          <div class="info">
            <h3><?= htmlspecialchars($anime['nome']) ?></h3>
            <p>Gêneros: <?= htmlspecialchars($anime['generos']) ?></p>
            <p>Ano: <?= htmlspecialchars($anime['ano']) ?></p>
            <p>Nota: ⭐ <?= htmlspecialchars($anime['nota']) ?></p>
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

    if (generoSelect.value) filtrar();
  </script>

</body>
</html>
