
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notícias de Animes</title>
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body class="quiz-page">
  <div class="quiz-container">
    <h1>🕳️ Quiz da Caverna</h1>
    <h3>Nível: <?= htmlspecialchars($nivel) ?> | Título: <?= htmlspecialchars($titulo) ?></h3>

    <!-- PAINEL DE PROGRESSO -->
    <div class="progresso-box">
      <h4>📊 Progresso no Quiz</h4>
      <p>Respondidos: <?= $totalRespondidos ?> / <?= $totalQuizzes ?></p>
      <p>✅ Acertos: <?= $totalAcertos ?> | ❌ Erros: <?= $totalErros ?></p>
      <div class="progresso-barra">
        <div class="progresso-preenchido" style="width: <?= $percentual ?>%;"></div>
      </div>
      <p class="xp-info">XP estimado ganho: <strong>+<?= $xpEstimado ?></strong></p>
    </div>

    <!-- GRADE DE QUIZZES -->
    <div class="quiz-grid">
      <?php foreach ($quizzes as $index => $quiz): ?>
        <?php
          $locked = $index > floor($nivel / 5); // desbloqueia 1 quiz a cada 5 níveis
          $respondido = $pdo->prepare("SELECT correta FROM questionarios WHERE user_id = ? AND pergunta_id = ?");
          $respondido->execute([$user_id, $quiz['id']]);
          $resp = $respondido->fetch();
          $status = $resp ? ($resp['correta'] ? 'acertou' : 'errou') : '';
        ?>
        <div class="quiz-card <?= $locked ? 'locked' : $status ?>">
          <?php if ($locked): ?>
            <div class="lock">🔒</div>
          <?php else: ?>
            <a href="questionario.php?quiz_id=<?= $quiz['id'] ?>">
              <div class="quiz-title">Quiz <?= $index + 1 ?></div>
              <?php if ($status === 'acertou'): ?><span class="result-tag acerto">✔</span><?php endif; ?>
              <?php if ($status === 'errou'): ?><span class="result-tag erro">✖</span><?php endif; ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
