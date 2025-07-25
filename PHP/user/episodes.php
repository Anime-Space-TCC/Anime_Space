<?php 
session_start();
require __DIR__ . '/../shared/conexao.php';

$id = $_GET['id'] ?? null;
$episode_id = $_GET['episode_id'] ?? null;

if (!$id) {
    echo "Anime não encontrado.";
    exit;
}

$anime = $pdo->prepare("SELECT nome, capa FROM animes WHERE id = ?");
$anime->execute([$id]);
$animeInfo = $anime->fetch();

if (!$animeInfo) {
    echo "Anime não encontrado.";
    exit;
}

// Buscar episódios com temporadas e as reações agregadas (likes e dislikes)
$episodios = $pdo->prepare("
    SELECT e.*, 
        COALESCE(SUM(CASE WHEN r.reacao = 'like' THEN 1 ELSE 0 END), 0) AS likes,
        COALESCE(SUM(CASE WHEN r.reacao = 'dislike' THEN 1 ELSE 0 END), 0) AS dislikes
    FROM episodios e
    LEFT JOIN episodio_reacoes r ON e.id = r.episodio_id
    WHERE e.anime_id = ?
    GROUP BY e.id
    ORDER BY e.temporada ASC, e.numero ASC
");
$episodios->execute([$id]);
$lista = $episodios->fetchAll();

$temporadas = [];
foreach ($lista as $ep) {
    $temporadas[$ep['temporada']][] = $ep;
}

$episodioSelecionado = null;
if ($episode_id) {
    $stmtEp = $pdo->prepare("SELECT * FROM episodios WHERE id = ? AND anime_id = ?");
    $stmtEp->execute([$episode_id, $id]);
    $episodioSelecionado = $stmtEp->fetch();
}

function extrairIdYoutube($url) {
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        if (preg_match('/v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function extrairIdGoogleDrive($url) {
    if (preg_match('/\/file\/d\/([^\/]+)\//', $url, $matches)) {
        return $matches[1];
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
        <section class="video-player" style="text-align: center;">
          <?php
          $videoUrl = $episodioSelecionado['video_url'];
          $youtubeId = extrairIdYoutube($videoUrl);
          $driveId = extrairIdGoogleDrive($videoUrl);
          ?>
          <h2>Assistindo: <?= htmlspecialchars($episodioSelecionado['titulo']) ?> (Temporada <?= $episodioSelecionado['temporada'] ?>, Episódio <?= $episodioSelecionado['numero'] ?>)</h2>

          <?php if ($youtubeId): ?>
            <iframe width="800" height="450"
              src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>"
              frameborder="0" allowfullscreen>
            </iframe>
          <?php elseif ($driveId): ?>
            <iframe src="https://drive.google.com/file/d/<?= htmlspecialchars($driveId) ?>/preview"
              width="800" height="450" allow="autoplay" frameborder="0" allowfullscreen>
            </iframe>
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
              <div class="card" data-episodio-id="<?= $ep['id'] ?>">
                <div class="card-left">
                    <?php if (!empty($ep['miniatura'])): ?>
                      <img src="../../img/<?= htmlspecialchars($ep['miniatura']) ?>" alt="Miniatura Episódio <?= htmlspecialchars($ep['numero']) ?>">
                    <?php else: ?>
                      <img src="../../img/logo.png" alt="Miniatura padrão">
                    <?php endif; ?>

                <div class="info-container">
                <div class="numero">Episódio <?= htmlspecialchars($ep['numero']) ?></div>

                <div class="texto-e-botao">
                <div class="titulo"><?= htmlspecialchars($ep['titulo']) ?></div>
                    <?php if (!empty($ep['descricao'])): ?>
                      <button class="btn-info" onclick="toggleDescricao(this)">+ Info</button>
                    <?php endif; ?>
                </div>
               </div>
              </div>

                <div class="card-right">
                  <div class="info-adicional">
                    <?php if (!empty($ep['duracao'])): ?>
                      <span>Duração: <?= htmlspecialchars($ep['duracao']) ?> min</span>
                    <?php endif; ?>
                    <?php if (!empty($ep['data_lancamento'])): ?>
                      <span> | Lançamento: <?= htmlspecialchars($ep['data_lancamento']) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="acoes">
                    <?php if (isset($_SESSION['user_id'])): ?>
                      <button class="reacao-btn btn-like" data-reacao="like">
                        👍 Curtir <span class="contador-like">(<?= $ep['likes'] ?>)</span>
                      </button>
                      <button class="reacao-btn btn-dislike" data-reacao="dislike">
                        👎 Não Curtir <span class="contador-dislike">(<?= $ep['dislikes'] ?>)</span>
                      </button>
                    <?php else: ?>
                      <span>👍 <?= $ep['likes'] ?> | 👎 <?= $ep['dislikes'] ?></span>
                    <?php endif; ?>
                  </div>
                  <a class="btn-assistir" href="?id=<?= $id ?>&episode_id=<?= $ep['id'] ?>">Assistir</a>
                  <?php if (!empty($ep['link_download'])): ?>
                    <a class="btn-download" href="<?= htmlspecialchars($ep['link_download']) ?>" target="_blank">Download</a>
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

      <?php if ($episodioSelecionado && isset($_SESSION['user_id'])): ?>
        <section class="comentarios">
          <h3>Comentários</h3>
          <form action="/TCC/Anime_Space/PHP/user/comentar.php" method="POST">
            <input type="hidden" name="episodio_id" value="<?= htmlspecialchars($episodioSelecionado['id']) ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>"> <!-- id do anime -->
            <textarea name="comentario" rows="4" placeholder="Escreva seu comentário..." required></textarea>
            <button type="submit">Enviar Comentário</button>
          </form>

          <?php
          $stmtComentarios = $pdo->prepare("SELECT c.comentario, c.data_comentario, u.username FROM comentarios c JOIN users u ON c.usuario_id = u.id WHERE c.episodio_id = ? ORDER BY c.data_comentario DESC");
          $stmtComentarios->execute([$episodioSelecionado['id']]);
          $comentarios = $stmtComentarios->fetchAll();

          foreach ($comentarios as $c): ?>
            <div class="comentario">
              <strong><?= htmlspecialchars($c['username']) ?>:</strong>
              <p><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
              <small><?= date('d/m/Y H:i', strtotime($c['data_comentario'])) ?></small>
            </div>
          <?php endforeach; ?>
        </section>
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

    // AJAX para enviar reação sem recarregar a página
    document.querySelectorAll('.reacao-btn').forEach(button => {
      button.addEventListener('click', () => {
        const card = button.closest('.card');
        const episodioId = card.getAttribute('data-episodio-id');
        const reacao = button.getAttribute('data-reacao');

        fetch('reagir.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `episodio_id=${encodeURIComponent(episodioId)}&reacao=${encodeURIComponent(reacao)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.sucesso) {
            card.querySelector('.contador-like').textContent = `(${data.likes})`;
            card.querySelector('.contador-dislike').textContent = `(${data.dislikes})`;
          } else {
            alert(data.erro || 'Erro ao processar reação.');
          }
        })
        .catch(() => alert('Erro ao enviar reação.'));
      });
    });
  </script>
</body>
</html>
