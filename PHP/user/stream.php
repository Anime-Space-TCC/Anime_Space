<?php
require __DIR__ . '/../shared/catalogo.php';
require_once __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/acessos.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Filtros recebidos
$filtroGenero = $_GET['generos'] ?? '';
$filtroAno = $_GET['ano'] ?? '';
$filtroLinguagem = $_GET['linguagem'] ?? '';

// Dados para dropdowns
$generos = getGeneros();
$anos = getAnos();
$linguagens = getLinguagens();

// Resultado da busca
$busca = $_GET['busca'] ?? '';
$animes = getAnimesFiltrados($filtroGenero, $filtroAno, $filtroLinguagem, $busca);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Streaming de Animes</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body class="streaming">

  <?php
  $current_page = 'stream';
  include __DIR__ . '/navbar.php';
  ?>
  <main class="page-content">
    <header class="titulo-pagina">
      <h1>Animes Disponíveis</h1> <!-- Título principal -->
    </header>
    <section class="busca-filtros">
      <form method="GET" action="stream.php" class="busca-filtros-form">
        <div class="barra-pesquisa">
          <!-- Campo de busca por nome ou gênero -->
          <input type="text" name="busca" placeholder="Buscar anime por nome ou gênero..." value="<?= htmlspecialchars($busca) ?>">
        </div>

        <div class="filtros">
          <!-- Dropdown para seleção de gênero -->
          <select name="generos">
            <option value="">Gênero</option>
            <?php foreach ($generos as $genero): ?>
              <option value="<?= htmlspecialchars($genero) ?>" <?= $filtroGenero === $genero ? 'selected' : '' ?>>
                <?= htmlspecialchars($genero) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <!-- Dropdown para seleção de ano -->
          <select name="ano">
            <option value="">Ano</option>
            <?php foreach ($anos as $ano): ?>
              <option value="<?= htmlspecialchars($ano) ?>" <?= $filtroAno === $ano ? 'selected' : '' ?>>
                <?= htmlspecialchars($ano) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <!-- Dropdown para seleção de lingua -->
          <select name="linguagem">
            <option value="">Tradução</option>
            <?php foreach ($linguagens as $linguagem): ?>
              <option value="<?= htmlspecialchars($linguagem) ?>" <?= $filtroLinguagem === $linguagem ? 'selected' : '' ?>>
                <?= htmlspecialchars($linguagem) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <button type="submit">Filtrar</button> <!-- Botão para aplicar filtros -->
          <a href="stream.php" style="text-decoration: none;" type="button">
            <button type="button">Limpar</button> <!-- Botão para limpar filtros -->
          </a>
        </div>
      </form>
    </section>

    <section class="anime-catalogo">
      <?php if ($animes): ?>
        <?php foreach ($animes as $anime): ?>
          <article class="anime-item">
            <a href="episodes.php?id=<?= $anime['id'] ?>" class="anime-item">
              <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img">
              <span class="anime-nota">⭐ <?= htmlspecialchars($anime['nota']) ?></span>
              <div class="info">
                <h3><?= htmlspecialchars($anime['nome']) ?></h3>
              </div>
            </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color: #ccc;">Nenhum anime cadastrado ainda.</p> <!-- Mensagem caso não haja animes -->
      <?php endif; ?>
    </section>
  </main>
  <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>