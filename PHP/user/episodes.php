<?php
require __DIR__ . '/../shared/conexao.php';

$id = $_GET['id'] ?? null;           // anime id
$episode_id = $_GET['episode_id'] ?? null;  // episódio selecionado para assistir

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

// Se episode_id foi passado, busca o episódio para exibir vídeo
$episodioSelecionado = null;
if ($episode_id) {
    $stmtEp = $pdo->prepare("SELECT * FROM episodios WHERE id = ? AND anime_id = ?");
    $stmtEp->execute([$episode_id, $id]);
    $episodioSelecionado = $stmtEp->fetch();
}

// Função para extrair id do YouTube do link
function extrairIdYoutube($url) {
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        // Youtube padrão
        if (preg_match('/v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }
        // Youtube curto youtu.be
        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title>
  <link rel="stylesheet" href="../../CSS/style2.css">
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

      <?php if ($episodioSelecionado): ?>
  <section class="video-player">
    <?php
    $videoUrl = $episodioSelecionado['link'];
    $youtubeId = extrairIdYoutube($videoUrl);
    ?>
    <h2>Assistindo: <?= htmlspecialchars($episodioSelecionado['titulo']) ?> (Temporada <?= $episodioSelecionado['temporada'] ?>, Episódio <?= $episodioSelecionado['numero'] ?>)</h2>
    <?php if ($youtubeId): ?>
      <iframe width="800" height="450"
        src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>"
        title="Vídeo do episódio <?= htmlspecialchars($episodioSelecionado['titulo']) ?>"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>
    <?php else: ?>
      <video width="800" height="450" controls>
        <source src="/TCC/Anime_Space/<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
        Seu navegador não suporta vídeo HTML5.
      </video>
    <?php endif; ?>
  </section>
<?php endif; ?>

      <?php if ($lista): ?>
        <?php foreach ($temporadas as $numTemp => $episodios): ?>
          <h2>Temporada <?= $numTemp ?></h2>
          <div class="grid">
            <?php foreach ($episodios as $ep): ?>
              <div class="card">
                <div class="card-left">
                  <?php if (!empty($ep['miniatura'])): ?>
                    <img src="../../img/<?= htmlspecialchars($ep['miniatura']) ?>" alt="Miniatura Episódio <?= htmlspecialchars($ep['numero']) ?>">
                  <?php else: ?>
                    <img src="../../img/logo.png" alt="Miniatura padrão">
                  <?php endif; ?>

                  <div>
                    <div class="numero">Episódio <?= htmlspecialchars($ep['numero']) ?></div>
                    <div class="titulo"><?= htmlspecialchars($ep['titulo']) ?></div>
                  </div>
                </div>

                <div class="card-center">
                  <?php if (!empty($ep['descricao'])): ?>
                    <button class="btn-info" onclick="toggleDescricao(this)">+ Info</button>
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

                <div class="card-right">
                  <div class="acoes">
                    <button class="like" title="Gostei">👍</button>
                    <button class="dislike" title="Não Gostei">👎</button>
                  </div>
                  <a class="btn-assistir" href="?id=<?= $id ?>&episode_id=<?= $ep['id'] ?>">Assistir</a>
                  <?php if (!empty($ep['link_download'])): ?>
                    <a class="btn-download" href="<?= htmlspecialchars($ep['link_download']) ?>" target="_blank" rel="noopener noreferrer">Download</a>
                  <?php endif; ?>
                </div>
              </div>
              <?php if (!empty($ep['descricao'])): ?>
                <div class="descricao"><?= nl2br(htmlspecialchars($ep['descricao'])) ?></div>
              <?php endif; ?>
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
      const descricao = card.nextElementSibling;
      if (descricao && descricao.classList.contains('descricao')) {
        descricao.classList.toggle('active');
        btn.textContent = descricao.classList.contains('active') ? '- Info' : '+ Info';
      }
    }
  </script>
</body>
</html>
