<?php
require __DIR__ . '/../shared/conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Anime não encontrado.";
    exit;
}

// Busca o anime
$anime = $pdo->prepare("SELECT nome, capa FROM animes WHERE id = ?");
$anime->execute([$id]);
$animeInfo = $anime->fetch();

if (!$animeInfo) {
    echo "Anime não encontrado.";
    exit;
}

// Busca episódios com temporada
$episodios = $pdo->prepare("SELECT * FROM episodios WHERE anime_id = ? ORDER BY temporada ASC, numero ASC");
$episodios->execute([$id]);
$lista = $episodios->fetchAll();

// Agrupa por temporada
$temporadas = [];
foreach ($lista as $ep) {
    $temporadas[$ep['temporada']][] = $ep;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title>
  <link rel="stylesheet" href="../../CSS/episodeos.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
  <div class="episodio">
    <header>
      <div class="info-anime">
        <?php if (!empty($animeInfo['capa'])): ?>
          <img src="../../img/<?= htmlspecialchars($animeInfo['capa']) ?>" alt="Capa do Anime">
        <?php endif; ?>
        <h1><?= htmlspecialchars($animeInfo['nome']) ?> - Episódios</h1>
      </div>
      <nav>
        <a href="../../HTML/home.html" class="btn-nav">Home</a>
        <a href="../../PHP/user/stream.php" class="btn-nav">Voltar</a>
      </nav>
    </header>

    <main>
      <?php if ($lista): ?>
        <?php foreach ($temporadas as $numTemp => $episodios): ?>
          <h2>Temporada <?= $numTemp ?></h2>
          <div class="grid">
            <?php foreach ($episodios as $ep): ?>
              <div class="card">
                <?php if (!empty($ep['miniatura'])): ?>
                  <img src="../../img/<?= htmlspecialchars($ep['miniatura']) ?>" alt="Miniatura Episódio <?= htmlspecialchars($ep['numero']) ?>">
                <?php else: ?>
                  <img src="../../img/logo-miniatura.jpg" alt="Miniatura padrão">
                <?php endif; ?>

                <div class="card-info">
                  <div class="numero">Episódio <?= htmlspecialchars($ep['numero']) ?></div>
                  <div class="titulo"><?= htmlspecialchars($ep['titulo']) ?></div>

                  <?php if (!empty($ep['descricao'])): ?>
                    <button class="btn-info" onclick="toggleDescricao(this)">+ Info</button>
                    <div class="descricao"><?= nl2br(htmlspecialchars($ep['descricao'])) ?></div>
                  <?php endif; ?>

                  <div class="info-adicional">
                    <?php if (!empty($ep['duracao'])): ?>
                      <span>Duração: <?= htmlspecialchars($ep['duracao']) ?> min</span>
                    <?php endif; ?>
                    <?php if (!empty($ep['data_lancamento'])): ?>
                      <span> | Lançamento: <?= htmlspecialchars($ep['data_lancamento']) ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="acoes">
                  <div class="reacoes">
                    <button class="like" title="Gostei">👍</button>
                    <button class="dislike" title="Não Gostei">👎</button>
                  </div>
                  <a class="btn-assistir" href="<?= htmlspecialchars($ep['link']) ?>" target="_blank" rel="noopener noreferrer">Assistir</a>
                  <?php if (!empty($ep['link_download'])): ?>
                    <a class="btn-download" href="<?= htmlspecialchars($ep['link_download']) ?>" target="_blank" rel="noopener noreferrer" style="margin-left:10px;">Download</a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Nenhum episódio disponível para este anime.</p>
      <?php endif; ?>
    </main>
  </div>

  <script>
    function toggleDescricao(btn) {
      const card = btn.closest('.card');
      const descricao = card.querySelector('.descricao');
      if (descricao) {
        descricao.classList.toggle('active');
        btn.textContent = descricao.classList.contains('active') ? '- Info' : '+ Info';
      }
    }
  </script>
</body>
</html>
