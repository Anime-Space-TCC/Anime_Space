<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';
require_once '../shared/usuarios.php';
require_once '../shared/quizzes.php';

// Garante login
verificarLogin();
$userId = $_SESSION['user_id'];

// Busca o nível atual do usuário
$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$nivelUsuario = (int) $stmt->fetchColumn();

// Busca animes favoritados + quizzes de uma vez 
$sql = "
SELECT 
    a.id AS anime_id, a.nome AS anime_nome, a.capa AS anime_capa,
    q.id AS quiz_id, q.titulo, q.nivel_minimo, q.capa AS quiz_capa, q.total_perguntas
FROM favoritos f
JOIN animes a ON a.id = f.anime_id
LEFT JOIN quizzes q ON q.anime_id = a.id AND q.ativo = 1
WHERE f.user_id = ?
ORDER BY a.nome ASC, q.nivel_minimo ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Reorganiza por anime
$animes = [];
foreach ($linhas as $linha) {
  $id = $linha['anime_id'];
  if (!isset($animes[$id])) {
    $animes[$id] = [
      'nome' => $linha['anime_nome'],
      'capa' => $linha['anime_capa'],
      'quizzes' => []
    ];
  }
  if ($linha['quiz_id']) { 
    $animes[$id]['quizzes'][] = [
      'id' => $linha['quiz_id'],
      'titulo' => $linha['titulo'],
      'nivel_minimo' => $linha['nivel_minimo'],
      'capa' => $linha['quiz_capa'],
      'qtd' => $linha['total_perguntas']
    ];
  }
}

// Ranking dos melhores jogadores (top 20)
$sqlRanking = "
  SELECT 
      u.id, 
      u.username, 
      u.foto_perfil, 
      COALESCE(SUM(qr.pontuacao), 0) AS total_pontos
  FROM users u
  LEFT JOIN quiz_resultados qr ON qr.user_id = u.id
  GROUP BY u.id
  ORDER BY total_pontos DESC
  LIMIT 20
";
$stmt = $pdo->prepare($sqlRanking);
$stmt->execute();
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fotoPerfil = buscarFotoPerfil($pdo, $userId);
if (!$fotoPerfil) {
    $fotoPerfil = '/PHP/uploads/default.jpg';
}

// Paginação
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$offset = ($pagina - 1) * $porPagina;

$quizzes = getQuizzesPaginados($porPagina, $offset);

$totalQuizzes = $pdo->query("SELECT COUNT(*) FROM quizzes")->fetchColumn();
$totalPaginas = ceil($totalQuizzes / $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Quizzes - Anime Space</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body>
  <?php
  $current_page = 'quizzes';
  include __DIR__ . '/navbar.php';
  ?>

  <main class="page-content">
    <div class="quizzes-layout">

      <!-- COLUNA ESQUERDA - QUIZZES -->
      <div class="col-esquerda">
        <h1>Missões de Conhecimento 🎯</h1><br>

        <?php if (empty($animes)): ?>
          <p>Você precisa favoritar um anime para desbloquear quizzes!</p>
        <?php else: ?>
          <?php foreach ($animes as $anime): ?>
            <div class="quiz-bloco">
              <div class="quiz-header">
                <h2><?= htmlspecialchars($anime['nome']) ?></h2>
              </div>

              <!-- LISTA DE QUIZZES COM SCROLL HORIZONTAL -->
              <div class="quiz-linha">
                <?php if (!empty($anime['quizzes'])): ?>
                  <?php foreach ($anime['quizzes'] as $quiz): ?>
                    <?php $liberado = $nivelUsuario >= $quiz['nivel_minimo']; ?>

                    <div class="quiz-card <?= $liberado ? 'liberado' : 'bloqueado' ?>">

                      <?php if ($liberado): ?>
                        <a href="quiz_jogar.php?id=<?= $quiz['id'] ?>">
                          <img src="../../img/<?= htmlspecialchars($quiz['capa'] ?? 'padrao_quiz.jpg') ?>">
                          <div class="numero">Nv <?= $quiz['nivel_minimo'] ?></div>
                          <p><?= htmlspecialchars($quiz['titulo']) ?></p>
                        </a>

                      <?php else: ?>
                        <img src="../../img/<?= htmlspecialchars($quiz['capa'] ?? 'padrao_quiz.jpg') ?>">
                        <div class="numero">🔒</div>
                        <p>Requer nível <?= $quiz['nivel_minimo'] ?></p>
                      <?php endif; ?>

                    </div>
                  <?php endforeach; ?>

                <?php else: ?>
                  <p class="sem-quiz">Nenhum quiz criado para este anime.</p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- COLUNA DIREITA - RANKING -->
      <div class="col-direita">
        <h2>Ranking dos Sábios 🏆</h2>

        <ul class="ranking-lista">
          <?php foreach ($ranking as $i => $player): ?>
            <li class="ranking-item pos<?= $i + 1 ?>">
              <span class="posicao"><?= $i + 1 ?>°</span>
              <img class="avatar" 
                  src="../uploads/<?= htmlspecialchars($fotoPerfil['foto_perfil'] ?? 'default.jpg') ?>?v=<?= time() ?>" 
                  alt="Avatar">
              <span class="nome"><?= htmlspecialchars($player['username']) ?></span>
              <span class="pontos"><?= $player['total_pontos'] ?> pts</span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>

    <!-- Paginação -->
      <div class="paginacao">
        <?php if ($pagina > 1): ?>
          <a href="?pagina=<?= $pagina - 1 ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
          <a href="?pagina=<?= $i ?>" class="<?= $i === $pagina ? 'ativo' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($pagina < $totalPaginas): ?>
          <a href="?pagina=<?= $pagina + 1 ?>">Próxima &raquo;</a>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>
  <script src=""></script>
</body>

</html>