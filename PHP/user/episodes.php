<?php
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/animes.php';
require __DIR__ . '/../shared/episodios.php';
require __DIR__ . '/../shared/comentarios.php';
require __DIR__ . '/../shared/utils.php';

$id = $_GET['id'] ?? null;
$episode_id = $_GET['episode_id'] ?? null;

if (!$id) {
    die("Anime não encontrado.");
}

// Busca anime
$animeInfo = buscarAnimePorId($pdo, $id);
if (!$animeInfo) die("Anime não encontrado.");

// Busca episódios
$lista = buscarEpisodiosComReacoes($pdo, $id);

// Filtra linguagem se selecionada
$filtroLinguagemSelecionada = $_GET['linguagem'] ?? '';
if ($filtroLinguagemSelecionada) {
    $lista = filtrarPorLinguagem($lista, $filtroLinguagemSelecionada);
}

// Organiza por temporada
$temporadas = organizarPorTemporada($lista);

// Episódio selecionado
$episodioSelecionado = null;
if ($episode_id) {
    $episodioSelecionado = buscarEpisodioSelecionado($pdo, $episode_id, $id);
}

// Busca comentários apenas se usuário logado
$comentarios = [];
if ($episodioSelecionado && isset($_SESSION['user_id'])) {
    $stmtComentarios = $pdo->prepare("
        SELECT c.comentario, c.data_comentario, u.username
        FROM comentarios c
        JOIN users u ON c.user_id = u.id
        WHERE c.episodio_id = ?
        ORDER BY c.data_comentario DESC
    ");
    $stmtComentarios->execute([$episodioSelecionado['id']]);
    $comentarios = $stmtComentarios->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title>
  <link rel="stylesheet" href="../../CSS/styleEpi.css">
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
        <?php if (!empty($animeInfo['sinopse'])): ?>
            <button class="btn-info" onclick="toggleSinopse()">
              ▼
            </button>
        <?php endif; ?>
    </div>
    <nav>
        <a href="../../PHP/user/index.php" class="sinopse-btn" aria-label="Página Inicial" role="button" tabindex="0"
           style="display: inline-flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
                <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
            </svg>
        </a>
        <a href="../../PHP/user/stream.php" class="btn-nav">Voltar</a>
    </nav>
    </header>
    <div class="sinopse-container" id="sinopse-container">
    <p><?= nl2br(htmlspecialchars($animeInfo['sinopse'])) ?></p>
    </div>
    <main>
      <?php if ($episodioSelecionado): ?>
        <section class="video-player" style="text-align: center;">
          <?php
          $videoUrl = $episodioSelecionado['video_url'];
          $driveId = extrairIdGoogleDrive($videoUrl);
          ?>
          <h2><?= htmlspecialchars($episodioSelecionado['titulo']) ?> (Temporada <?= $episodioSelecionado['temporada'] ?>, Episódio <?= $episodioSelecionado['numero'] ?>)</h2>

          <?php if ($driveId): ?>
            <!-- Google Drive - apenas para assistir -->
            <iframe src="https://drive.google.com/file/d/<?= htmlspecialchars($driveId) ?>/preview"
              width="800" height="450" allow="autoplay" frameborder="0" allowfullscreen>
            </iframe>
          <?php else: ?>
            <!-- Vídeo Local - compatível com quiz -->
            <video width="800" height="450" controls>
              <source src="../../videos/<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
              Seu navegador não suporta vídeo HTML5.
            </video>
          <?php endif; ?>
        </section>
      <?php endif; ?>

      <?php if ($lista): ?>
        <?php foreach ($temporadas as $numTemp => $episodios): ?>
          <h2>Temporada <?= $numTemp ?></h2>
          <div class="filtro-linguagem">
            <a href="?id=<?= $id ?>&linguagem=dublado" class="btn-ling <?= $filtroLinguagemSelecionada === 'dublado' ? 'ativo' : '' ?>">Dublado</a>
            <a href="?id=<?= $id ?>&linguagem=legendado" class="btn-ling <?= $filtroLinguagemSelecionada === 'legendado' ? 'ativo' : '' ?>">Legendado</a>
            <a href="?id=<?= $id ?>" class="btn-ling <?= $filtroLinguagemSelecionada === '' ? 'ativo' : '' ?>">Todos</a>
          </div>
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
                        <?php if (!empty($ep['descricao'])): ?>
                          <button class="btn-info" onclick="toggleDescricao(this)">
                            ▼
                          </button>
                        <?php endif; ?>
                        <div class="titulo"><?= htmlspecialchars($ep['titulo']) ?></div>
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
                  <a class="btn-assistir" href="?id=<?= $id ?>&episode_id=<?= $ep['id'] ?><?= $filtroLinguagemSelecionada ? '&linguagem=' . urlencode($filtroLinguagemSelecionada) : '' ?>">Assistir</a>
                  <a class="btn-quiz hidden" href="quiz.php?episodio_id=<?= $ep['id'] ?>">
                    🎉 Quiz do Episódio
                  </a>
                </div>
              </div>

              <?php if (!empty($ep['descricao'])): ?>
                <div class="descricao hidden"><?= nl2br(htmlspecialchars($ep['descricao'])) ?></div>
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
            <?php
            $host = $_SERVER['HTTP_HOST'];
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); 
            $formAction = "http://{$host}{$baseDir}/comentar.php";
            ?>
            <form action="<?= htmlspecialchars($formAction) ?>" method="POST">
                <input type="hidden" name="episodio_id" value="<?= htmlspecialchars($episodioSelecionado['id']) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <textarea name="comentario" rows="4" placeholder="Escreva seu comentário..." required></textarea>
                <button type="submit">Enviar Comentário</button>
            </form>

            <?php
            $stmtComentarios = $pdo->prepare("
                SELECT c.comentario, c.data_comentario, u.username
                FROM comentarios c
                JOIN users u ON c.user_id = u.id
                WHERE c.episodio_id = ?
                ORDER BY c.data_comentario DESC
            ");
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
  if (!descricao || !descricao.classList.contains('descricao')) return;

  const isAtiva = descricao.classList.contains('active');

  // Fecha todas as descrições
  document.querySelectorAll('.descricao.active').forEach(desc => {
    desc.classList.remove('active');
    const otherBtn = desc.previousElementSibling.querySelector('.btn-info');
    if (otherBtn) otherBtn.textContent = '▼';
  });

  // Se não estava ativa, abre a clicada
  if (!isAtiva) {
    descricao.classList.add('active');
    btn.textContent = '▲';
  }
}

function toggleSinopse() {
    const sinopseContainer = document.getElementById('sinopse-container');
    const btn = document.querySelector('header .btn-info');
    
    if (sinopseContainer && btn) {
        sinopseContainer.classList.toggle('active');
        
        if (sinopseContainer.classList.contains('active')) {
            btn.textContent = '▲';
        } else {
            btn.textContent = '▼';
        }
    }
}

// AJAX para curtir/descurtir
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
    card.querySelector('.contador-like').textContent = data.likes;
    card.querySelector('.contador-dislike').textContent = data.dislikes;

    const quizButton = card.querySelector('.btn-quiz');
    if (quizButton) {
      if (data.reacao_atual === 'like') {
        quizButton.classList.add('show');
      } else {
        quizButton.classList.remove('show');
      }
    }

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
