<?php
require __DIR__ . '/../shared/conexao.php';

// Filtros capturados via GET
$filtroGenero = $_GET['generos'] ?? '';
$filtroAno = $_GET['ano'] ?? '';
$busca = $_GET['busca'] ?? '';

// Gêneros e anos disponíveis
$generos = $pdo->query("SELECT nome FROM generos ORDER BY nome ASC")->fetchAll(PDO::FETCH_COLUMN);
$anos = $pdo->query("SELECT valor FROM ano ORDER BY valor DESC")->fetchAll(PDO::FETCH_COLUMN);

// Consulta dinâmica com filtros
$sql = "
  SELECT DISTINCT a.id, a.nome, a.capa, a.ano, a.nota,
    GROUP_CONCAT(g.nome SEPARATOR ', ') AS generos
  FROM animes a
  LEFT JOIN anime_generos ag ON a.id = ag.anime_id
  LEFT JOIN generos g ON ag.genero_id = g.id
  WHERE 1 = 1
";

$params = [];

if (!empty($filtroGenero)) {
  $sql .= " AND g.nome = :genero";
  $params[':genero'] = $filtroGenero;
}

if (!empty($filtroAno)) {
  $sql .= " AND a.ano = :ano";
  $params[':ano'] = $filtroAno;
}

if (!empty($busca)) {
  $sql .= " AND (a.nome LIKE :busca1 OR g.nome LIKE :busca2)";
  $params[':busca1'] = '%' . $busca . '%';
  $params[':busca2'] = '%' . $busca . '%';
}

$sql .= " GROUP BY a.id ORDER BY a.nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Streaming de Animes</title>
  <link rel="stylesheet" href="../../CSS/style0.css">
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
    <form method="GET" action="stream.php" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
      <div class="barra-pesquisa">
        <input type="text" name="busca" placeholder="Buscar anime por nome ou gênero..." value="<?= htmlspecialchars($busca) ?>">
      </div>

      <div class="filtros">
        <select name="generos">
          <option value="">Gênero</option>
          <?php foreach ($generos as $genero): ?>
            <option value="<?= htmlspecialchars($genero) ?>" <?= $filtroGenero === $genero ? 'selected' : '' ?>>
              <?= htmlspecialchars($genero) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <select name="ano">
          <option value="">Ano</option>
          <?php foreach ($anos as $ano): ?>
            <option value="<?= htmlspecialchars($ano) ?>" <?= $filtroAno === $ano ? 'selected' : '' ?>>
              <?= htmlspecialchars($ano) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
        <a href="stream.php" style="text-decoration: none;">
          <button type="button">Limpar</button>
        </a>
      </div>
    </form>
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

</body>
</html>
