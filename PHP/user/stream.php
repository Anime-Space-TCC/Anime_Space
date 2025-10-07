<?php 
require __DIR__ . '/../shared/catalogo.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Filtros recebidos
$filtroGenero = $_GET['generos'] ?? '';
$filtroAno = $_GET['ano'] ?? '';
$filtroLinguagem = $_GET['linguagem'] ?? '';
$busca = $_GET['busca'] ?? '';

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
<body class="streaming" id="topo">

  <header class="links">
    <h1>Animes Disponíveis</h1> <!-- Título principal -->
    <nav>
      <a href="../../PHP/user/index.php" class="sinopse-btn" aria-label="Página Inicial" role="button" tabindex="0"
           style="display: inline-flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
                <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
            </svg>
        </a>
    </nav>
  </header>

  <section class="busca-filtros">
    <form method="GET" action="stream.php" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
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
        <a href="stream.php" style="text-decoration: none;">
          <button type="button">Limpar</button> <!-- Botão para limpar filtros -->
        </a>
      </div>
    </form>
  </section>

  <main class="anime-catalogo">
    <?php if ($animes): ?>
      <?php foreach ($animes as $anime): ?>
        <article class="anime-item" data-genero="<?= strtolower($anime['generos']) ?>" data-ano="<?= $anime['ano'] ?>">
          <!-- Imagem da capa do anime -->
          <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>" class="mini-img">
          <div class="info">
            <h3><?= htmlspecialchars($anime['nome']) ?></h3> <!-- Nome do anime -->
            <p>Gêneros: <?= htmlspecialchars($anime['generos']) ?></p> <!-- Gêneros concatenados -->
            <p>Ano: <?= htmlspecialchars($anime['ano']) ?></p> <!-- Ano de lançamento -->
            <p>Nota: ⭐ <?= htmlspecialchars($anime['nota']) ?></p> <!-- Nota do anime -->
            <a href="episodes.php?id=<?= $anime['id'] ?>">▶️ Ver Episódios</a> <!-- Link para episódios -->
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="color: #ccc;">Nenhum anime cadastrado ainda.</p> <!-- Mensagem caso não haja animes -->
    <?php endif; ?>
  </main>

  <footer class="rodape">
    <p>&copy; 2025 - Anime Space. <a href="../../HTML/sobre.html">Sobre</a></p> <!-- Rodapé -->
  </footer>
</body>
</html>
