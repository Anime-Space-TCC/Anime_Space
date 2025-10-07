<?php
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/animes.php';
require __DIR__ . '/../shared/episodios.php';
require __DIR__ . '/../shared/comentarios.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Obtém o ID do anime e o ID do episódio a partir dos parâmetros da URL ($_GET).
$id = $_GET['id'] ?? null;
$episode_id = $_GET['episode_id'] ?? null;

// Se o ID do anime não for fornecido, encerra a execução exibindo uma mensagem de erro.
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

$usuarioId = $_SESSION['user_id'] ?? null;

// Verifica se anime está favoritado
$favoritado = false;
if ($usuarioId) {
    $stmt = $pdo->prepare("SELECT 1 FROM favoritos WHERE user_id = ? AND anime_id = ?");
    $stmt->execute([$usuarioId, $id]);
    $favoritado = (bool) $stmt->fetchColumn();
}
// Define a temporada atual do quiz
$quizTemporada = $episodioSelecionado['temporada'] ?? array_key_first($temporadas);

// Monta a URL do quiz somente se o anime estiver favoritado e houver temporada
$quizUrl = '';
if ($favoritado && $quizTemporada) {
    $quizUrl = "../../PHP/user/quiz.php?anime_id={$id}&temporada={$quizTemporada}";
}
// Avaliação do usuário
$avaliacaoUsuario = 0;
if ($usuarioId) {
    $stmt = $pdo->prepare("SELECT nota FROM avaliacoes WHERE user_id = ? AND anime_id = ?");
    $stmt->execute([$usuarioId, $id]);
    $avaliacaoUsuario = $stmt->fetchColumn() ?: 0;
}

// Define qual temporada começa aberta (a do episódio selecionado, senão a primeira)
$temporadaInicial = null;
if (!empty($temporadas)) {
    // Se existe episódio selecionado, abre a temporada dele
    if ($episodioSelecionado && isset($temporadas[$episodioSelecionado['temporada']])) {
        $temporadaInicial = $episodioSelecionado['temporada'];
    } else {
        // Senão, abre a primeira temporada da lista
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
  <div class="episodio">
    <header>
      <div class="info-anime">
        <?php if (!empty($animeInfo['capa'])): ?>
          <img src="../../img/<?= htmlspecialchars($animeInfo['capa']) ?>" alt="Capa do Anime">
        <?php endif; ?>
        <h1><?= htmlspecialchars($animeInfo['nome']) ?> - Episódios</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Botão Favorito -->
          <button type="button" id="btn-favorito" class="btn-favorito <?= $favoritado ? 'ativo' : '' ?>" data-anime-id="<?= $id ?>">
            <?= $favoritado ? '❤️' : '🤍' ?>
          </button>

          <!-- Avaliação de Estrelas -->
          <div class="avaliacao-estrelas" data-anime-id="<?= $id ?>">
            <div class="estrela-container">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" class="estrela <?= $i <= $avaliacaoUsuario ? 'ativa' : '' ?>" data-valor="<?= $i ?>">☆</button>
                <?php endfor; ?>
            </div>
            <div class="nota-display"><?= $avaliacaoUsuario ? $avaliacaoUsuario.'/10' : '' ?></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($animeInfo['sinopse'])): ?>
          <button type="button" class="btn-info" onclick="toggleSinopse()">▼</button>
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
            $youtubeId = extrairIdYoutube($videoUrl);
          ?>
          
          <h2><?= htmlspecialchars($episodioSelecionado['titulo']) ?> (Temporada <?= $episodioSelecionado['temporada'] ?>, Episódio <?= $episodioSelecionado['numero'] ?>)</h2>
          <?php if ($youtubeId): ?>
            <!-- YouTube Embed -->
            <iframe width="800" height="450" 
                    src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>" 
                    frameborder="0" allowfullscreen allow="autoplay"></iframe>
          <?php else: ?>

            <!-- Vídeo Local -->
            <video width="800" height="450" controls>
              <source src="../../videos/<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
              Seu navegador não suporta vídeo HTML5.
            </video>
          <?php endif; ?>
        </section>
      <?php endif; ?>

      <?php if ($lista): ?>

        <!-- Cabeçalho de Temporada + Quiz -->
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

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Botão Quiz sempre presente, mas oculto se não favoritado -->
                <a href="<?= htmlspecialchars($quizUrl ?: '#') ?>" 
                  class="btn-quiz <?= $favoritado ? '' : 'hidden' ?>" 
                  data-anime-id="<?= $id ?>">
                  Quiz da Temporada <?= $quizTemporada ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Blocos por temporada -->
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
                  
                  <!-- Lado esquerdo: miniatura e título -->
                  <div class="card-left">
                    <img src="../../img/<?= htmlspecialchars($ep['miniatura'] ?: 'logo.png') ?>" 
                        alt="Miniatura Episódio <?= htmlspecialchars($ep['numero']) ?>">

                    <div class="info-container">
                      <div class="episodio-numero">Episódio <?= htmlspecialchars($ep['numero']) ?></div>
                      <div class="titulo-e-descricao">
                        <div class="episodio-titulo"><?= htmlspecialchars($ep['titulo']) ?></div>
                        <?php if (!empty($ep['descricao'])): ?>
                          <button class="btn-toggle-descricao" onclick="toggleDescricao(this)">▼</button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <!-- Lado direito: info adicional, ações e botão assistir -->
                  <div class="card-right">
                    <div class="info-adicional">
                      <?php if (!empty($ep['duracao'])): ?>
                        <span>Duração: <?= htmlspecialchars($ep['duracao']) ?> min</span>
                      <?php endif; ?>
                      <?php if (!empty($ep['data_lancamento'])): ?>
                        <span> | Lançamento: <?= htmlspecialchars($ep['data_lancamento']) ?></span>
                      <?php endif; ?>
                    </div>

                    <!-- Ações de like/dislike -->
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

                    <!-- Botão assistir -->
                    <a class="btn-assistir" 
                      href="?id=<?= $id ?>&episode_id=<?= $ep['id'] ?><?= $filtroLinguagemSelecionada ? '&linguagem=' . urlencode($filtroLinguagemSelecionada) : '' ?>">
                      Assistir
                    </a>
                  </div>

                  <!-- Descrição escondida -->
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
// ========================
// Alterna sinopse do anime
// ========================
function toggleSinopse() {
  const sinopseContainer = document.getElementById('sinopse-container');
  const btn = document.querySelector('header .btn-info');
  if (sinopseContainer && btn) {
    sinopseContainer.classList.toggle('active');
    btn.textContent = sinopseContainer.classList.contains('active') ? '▲' : '▼';
  }
}

// ========================
// Alterna descrição de episódio
// ========================
function toggleDescricao(btn) {
    const card = btn.closest('.card');
    if (!card) return;

    const descricao = card.nextElementSibling;
    if (!descricao) return;

    descricao.classList.toggle('hidden');
    btn.textContent = descricao.classList.contains('hidden') ? '▼' : '▲';
}

// ========================
// Dropdown de temporadas
// ========================
const btnDropdown = document.getElementById('btnDropdown');
const dropdownList = document.getElementById('dropdownList');

if (btnDropdown && dropdownList) {
  const dropdownItems = dropdownList.querySelectorAll('li');

  btnDropdown.addEventListener('click', () => {
    dropdownList.classList.toggle('show');
  });

  dropdownItems.forEach(item => {
    item.addEventListener('click', () => {
      const temporada = item.dataset.temporada;
      btnDropdown.textContent = `Temporada ${temporada}`;

      document.querySelectorAll('.temporada-bloco').forEach(bloco => {
        bloco.style.display = (bloco.dataset.temporada === temporada) ? "" : "none";
      });

      // Atualiza link do Quiz para a temporada selecionada
      const quizBtn = document.querySelector(`.btn-quiz[data-anime-id="<?= $id ?>"]`);
      if (quizBtn) {
        quizBtn.href = `../../PHP/user/quiz.php?anime_id=<?= $id ?>&temporada=${temporada}`;
      }

      dropdownList.classList.remove('show');
    });
  });

  // Fecha dropdown ao clicar fora
  document.addEventListener('click', e => {
    if (!btnDropdown.contains(e.target) && !dropdownList.contains(e.target)) {
      dropdownList.classList.remove('show');
    }
  });
}

// ========================
// Reações (Curtir / Não Curtir)
// ========================
document.querySelectorAll('.reacao-btn').forEach(button => {
  button.addEventListener('click', () => {
    const card = button.closest('.card');
    if (!card) {
      console.error('Erro: card não encontrado para o botão clicado.');
      return;
    }

    const episodioId = card.dataset.episodioId;
    const reacao = button.dataset.reacao;

    // Checagem de dados
    if (!episodioId || !['like', 'dislike'].includes(reacao)) {
      console.error('Erro: dados inválidos', { episodioId, reacao });
      return;
    }

    fetch('../../PHP/shared/reagir.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `episodio_id=${encodeURIComponent(episodioId)}&reacao=${encodeURIComponent(reacao)}`
    })
    .then(res => res.json())
    .then(data => {
      console.log('Resposta da API:', data);

      if (data.sucesso) {
        const likeSpan = card.querySelector('.contador-like');
        const dislikeSpan = card.querySelector('.contador-dislike');

        if (likeSpan) likeSpan.textContent = data.likes ?? 0;
        if (dislikeSpan) dislikeSpan.textContent = data.dislikes ?? 0;

        // Atualiza visual dos botões (opcional)
        card.querySelectorAll('.reacao-btn').forEach(btn => btn.classList.remove('ativo'));
        button.classList.add('ativo');

      } else {
        alert(data.erro || 'Erro ao processar reação.');
      }
    })
    .catch(err => {
      console.error('Falha na requisição:', err);
      alert('Erro ao enviar reação.');
    });
  });
});

// ========================
// Favoritos 
// ========================
document.querySelectorAll(".btn-favorito").forEach(btnFav => {
  btnFav.addEventListener("click", (e) => {
    e.preventDefault();
    const animeId = btnFav.dataset.animeId;

    fetch("../shared/favoritar.php", {
      method: "POST",
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `anime_id=${encodeURIComponent(animeId)}`,
      credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
      if (data.sucesso) {
        // Atualiza o coração
        btnFav.textContent = data.favoritado ? "❤️" : "🤍";
        btnFav.classList.toggle("ativo", data.favoritado);

        const quizBtn = document.querySelector(`.btn-quiz[data-anime-id="${animeId}"]`);
        if (quizBtn && data.favoritado) {
          const temporadaAtual = btnDropdown ? btnDropdown.textContent.replace('Temporada ', '') : '1';
          quizBtn.href = `../../PHP/user/quiz.php?anime_id=${animeId}&temporada=${temporadaAtual}`;
        }

      } else {
        alert(data.erro || 'Erro desconhecido.');
      }
    })
    .catch(() => alert('Erro ao enviar favorito.'));
  });
});

// ========================
// Avaliação de estrelas
// ========================
document.querySelectorAll('.avaliacao-estrelas').forEach(container => {
  const animeId = container.dataset.animeId;
  const estrelas = container.querySelectorAll('.estrela');
  const notaBox = container.querySelector('.nota-display');

  const atualizarEstrelas = valor => {
    estrelas.forEach(e => e.classList.toggle('ativa', e.dataset.valor <= valor));
  };

  estrelas.forEach(estrela => {
    estrela.addEventListener('click', e => {
      e.preventDefault();
      const valorEstrela = Number(estrela.dataset.valor);
      const nota = valorEstrela * 2;

      fetch('../shared/avaliar.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `anime_id=${encodeURIComponent(animeId)}&avaliacao=${nota}`,
        credentials: 'same-origin'
      })
      .then(res => res.json())
      .then(data => {
        if (data.sucesso) {
          atualizarEstrelas(valorEstrela);
          notaBox.textContent = `Nota: ${data.nota}/10`;
        } else {
          alert(data.erro || 'Erro ao registrar avaliação.');
        }
      })
      .catch(() => alert('Erro ao enviar avaliação.'));
    });
  });
});
</script>
</body>
</html>
