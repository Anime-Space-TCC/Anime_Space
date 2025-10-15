<?php
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/animes.php';
require __DIR__ . '/../shared/episodios.php';
require __DIR__ . '/../shared/comentarios.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

$id = $_GET['id'] ?? null;
$episode_id = $_GET['episode_id'] ?? null;

if (!$id) die("Anime não encontrado.");

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

$usuarioId = $_SESSION['user_id'] ?? null;

// Verifica se anime está favoritado
$favoritado = false;
if ($usuarioId) {
  $stmt = $pdo->prepare("SELECT 1 FROM favoritos WHERE user_id = ? AND anime_id = ?");
  $stmt->execute([$usuarioId, $id]);
  $favoritado = (bool) $stmt->fetchColumn();
}

// Avaliação do usuário
$avaliacaoUsuario = 0;
if ($usuarioId) {
  $stmt = $pdo->prepare("SELECT nota FROM avaliacoes WHERE user_id = ? AND anime_id = ?");
  $stmt->execute([$usuarioId, $id]);
  $avaliacaoUsuario = $stmt->fetchColumn() ?: 0;
}

// Define qual temporada começa aberta
$temporadaInicial = null;
if (!empty($temporadas)) {
  if ($episodioSelecionado && isset($temporadas[$episodioSelecionado['temporada']])) {
    $temporadaInicial = $episodioSelecionado['temporada'];
  } else {
    $temporadaInicial = array_key_first($temporadas);
  }
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
  <?php
  $current_page = 'episodeos';
  include __DIR__ . '/navbar.php';
  ?>
  <main class="page-content">
    <div class="episodio">
      <header>
        <div class="info-anime">
          <?php if (!empty($animeInfo['capa'])): ?>
            <img src="../../img/<?= htmlspecialchars($animeInfo['capa']) ?>" alt="Capa do Anime">
          <?php endif; ?>

          <div class="anime-titulo-meta">
            <div class="titulo-linha">
              <h1><?= htmlspecialchars($animeInfo['nome']) ?></h1>
              <div class="meta-nota"><?= htmlspecialchars($animeInfo['nota']) ?></div>
              <?php if (isset($_SESSION['user_id'])): ?>
                <button type="button" class="btn-favorito <?= $favoritado ? 'ativo' : '' ?>" data-anime-id="<?= $id ?>">
                  <?= $favoritado ? '❤️' : '🤍' ?>
                </button>
              <?php endif; ?>
              <?php if (!empty($animeInfo['sinopse'])): ?>
                <button type="button" class="btn-info" onclick="toggleSinopse()">▼</button>
              <?php endif; ?>
            </div>

            <?php if (!empty($animeInfo['generos'])): ?>
              <div class="generos-linha">
                <?php foreach ($animeInfo['generos'] as $genero): ?>
                  <a href="../user/stream.php?id=<?= $genero['id'] ?>" class="meta-btn">
                    <?= htmlspecialchars($genero['nome']) ?>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </header>

      <div class="sinopse-container" id="sinopse-container">
        <p><?= nl2br(htmlspecialchars($animeInfo['sinopse'])) ?></p>
      </div>

      <section>
        <?php if ($episodioSelecionado): ?>
          <section class="video-player" style="text-align: center;">
            <?php
            $videoUrl = $episodioSelecionado['video_url'];
            $youtubeId = extrairIdYoutube($videoUrl);
            ?>

            <h2><?= htmlspecialchars($episodioSelecionado['titulo']) ?> (Temporada <?= $episodioSelecionado['temporada'] ?>, Episódio <?= $episodioSelecionado['numero'] ?>)</h2>
            <?php if ($youtubeId): ?>
              <iframe width="800" height="450"
                src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>"
                frameborder="0" allowfullscreen allow="autoplay"></iframe>
            <?php else: ?>
              <video width="800" height="450" controls>
                <source src="../../videos/<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                Seu navegador não suporta vídeo HTML5.
              </video>
            <?php endif; ?>
          </section>
        <?php endif; ?>

        <?php if ($lista): ?>
          <div class="header-temporada">
            <?php if (count($temporadas) > 1): ?>
              <div class="dropdown-temporadas">
                <button class="btn-dropdown" id="btnDropdown">
                  Temporada <?= $temporadaInicial ?>
                </button>
                <ul class="dropdown-list" id="dropdownList">
                  <?php foreach (array_keys($temporadas) as $numTemp): ?>
                    <li data-temporada="<?= $numTemp ?>">Temporada <?= $numTemp ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php else: ?>
              <?php $unicaTemp = array_key_first($temporadas); ?>
              <h2 class="titulo-temporada-unica">Temporada <?= $unicaTemp ?></h2>
            <?php endif; ?>
          </div>

          <?php foreach ($temporadas as $numTemp => $episodios): ?>
            <div class="temporada-bloco" data-temporada="<?= $numTemp ?>" style="<?= ($temporadaInicial == $numTemp) ? '' : 'display:none;' ?>">

              <div class="filtro-linguagem">
                <a href="?id=<?= $id ?>&linguagem=dublado" class="btn-ling <?= $filtroLinguagemSelecionada === 'dublado' ? 'ativo' : '' ?>">Dublado</a>
                <a href="?id=<?= $id ?>&linguagem=legendado" class="btn-ling <?= $filtroLinguagemSelecionada === 'legendado' ? 'ativo' : '' ?>">Legendado</a>
                <a href="?id=<?= $id ?>" class="btn-ling <?= $filtroLinguagemSelecionada === '' ? 'ativo' : '' ?>">Todos</a>
              </div>

              <div class="grid">
                <?php foreach ($episodios as $ep): ?>
                  <div class="card" data-episodio-id="<?= $ep['id'] ?>">

                    <div class="card-left">
                      <img src="../../img/<?= htmlspecialchars($ep['miniatura'] ?: 'logo.png') ?>"
                        alt="Miniatura Episódio <?= htmlspecialchars($ep['numero']) ?>">

                      <div class="info-container">
                        <div class="episodio-numero">Episódio <?= htmlspecialchars($ep['numero']) ?></div>
                        <div class="titulo-e-descricao">
                          <div class="episodio-titulo"><?= htmlspecialchars($ep['titulo']) ?></div>
                          <?php if (!empty($ep['descricao'])): ?>
                            <button class="btn-toggle-descricao" onclick="toggleDescricao(this)">►</button>
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
                            👍 <span class="contador-like"><?= $ep['likes'] ?></span>
                          </button>
                          <button class="reacao-btn btn-dislike" data-reacao="dislike">
                            👎 <span class="contador-dislike"><?= $ep['dislikes'] ?></span>
                          </button>
                        <?php else: ?>
                          <span>👍 <?= $ep['likes'] ?> | 👎 <?= $ep['dislikes'] ?></span>
                        <?php endif; ?>
                      </div>

                      <a class="btn-assistir"
                        href="?id=<?= $id ?>&episode_id=<?= $ep['id'] ?><?= $filtroLinguagemSelecionada ? '&linguagem=' . urlencode($filtroLinguagemSelecionada) : '' ?>">
                        Assistir
                      </a>
                    </div>

                    <?php if (!empty($ep['descricao'])): ?>
                      <div class="episodio-descricao hidden"><?= nl2br(htmlspecialchars($ep['descricao'])) ?></div>
                    <?php endif; ?>

                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>

        <?php else: ?>
          <p>Nenhum episódio disponível para este anime.</p>
        <?php endif; ?>

          <?php if (isset($_SESSION['user_id'])): ?>
            <section class="avaliacao-final">
              <h3>Sua Avaliação</h3>
              <div class="avaliacao-estrelas" data-anime-id="<?= $id ?>">
                <div class="estrela-container">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" class="estrela <?= $i <= $avaliacaoUsuario ? 'ativa' : '' ?>" data-valor="<?= $i ?>">☆</button>
                  <?php endfor; ?>
                </div>
                <div class="nota-display"><?= $avaliacaoUsuario ? $avaliacaoUsuario . '/10' : '' ?></div>
              </div>
            </section>
          <?php endif; ?>

        <?php if ($episodioSelecionado && isset($_SESSION['user_id'])): ?>
          <section class="comentarios">
            <h3>Comentários</h3>
            <?php
            $host = $_SERVER['HTTP_HOST'];
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            $formAction = dirname($_SERVER['SCRIPT_NAME'], 2) . '/shared/comentar.php';
            ?>
            <form action="<?= htmlspecialchars($formAction) ?>" method="POST">
              <input type="hidden" name="episodio_id" value="<?= htmlspecialchars($episodioSelecionado['id']) ?>">
              <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
              <textarea name="comentario" rows="4" placeholder="Escreva seu comentário..." required></textarea>
              <button type="submit">Enviar Comentário</button>
            </form>

            <?php foreach ($comentarios as $c): ?>
              <div class="comentario">
                <strong><?= htmlspecialchars($c['username']) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
                <small><?= date('d/m/Y H:i', strtotime($c['data_comentario'])) ?></small>
              </div>
            <?php endforeach; ?>
          </section>
        <?php endif; ?>
      </section>
    </div>
    <?php include __DIR__ . '/rodape.php'; ?>
  </main>
  <script src="../../JS/togge.js"></script>
  <script src="../../JS/temporadas.js"></script>
  <script src="../../JS/reacao.js"></script>
  <script src="../../JS/favoritar.js"></script>
  <script src="../../JS/avaliar.js"></script>
</body>
</html>
