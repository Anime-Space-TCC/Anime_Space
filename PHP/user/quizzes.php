<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

// Garante login
verificarLogin();
$userId = $_SESSION['user_id'];

// Busca o nível atual do usuário
$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$nivelUsuario = (int) $stmt->fetchColumn();

// Busca animes favoritados + quizzes de uma vez (sem N+1 queries)
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
    if ($linha['quiz_id']) { // Só adiciona se existir quiz
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
  SELECT u.id, u.username, u.foto_perfil, SUM(qr.pontuacao) AS total_pontos
  FROM quiz_resultados qr
  JOIN users u ON qr.user_id = u.id
  GROUP BY u.id
  ORDER BY total_pontos DESC
  LIMIT 20
";
$stmt = $pdo->prepare($sqlRanking);
$stmt->execute();
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h1>Missões de Conhecimento 🎯</h1>

        <?php if (empty($animes)): ?>
          <p>Você precisa favoritar um anime para desbloquear quizzes!</p>
        <?php else: ?>
          <?php foreach ($animes as $anime): ?>
            <div class="quiz-bloco">
              <div class="quiz-header">
                <h2><?= htmlspecialchars($anime['nome']) ?></h2>
              </div>

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
            <li class="ranking-item pos<?= $i+1 ?>">
              <span class="posicao"><?= $i+1 ?>°</span>
              <img class="avatar" src="../uploads/<?= htmlspecialchars($player['foto_perfil'] ?? 'default.jpg') ?>">
              <span class="nome"><?= htmlspecialchars($player['username']) ?></span>
              <span class="pontos"><?= $player['total_pontos'] ?> pts</span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>

