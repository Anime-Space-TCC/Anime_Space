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

// Busca os animes favoritados do usuário
$sqlFavoritos = "SELECT a.id, a.nome, a.capa 
                 FROM favoritos f 
                 JOIN animes a ON a.id = f.anime_id
                 WHERE f.user_id = ?";
$stmt = $pdo->prepare($sqlFavoritos);
$stmt->execute([$userId]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Monta lista de quizzes
$animesComQuizzes = [];
foreach ($favoritos as $anime) {
  $sql = "SELECT * FROM quizzes WHERE anime_id = ? AND ativo = 1 ORDER BY nivel_minimo ASC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$anime['id']]);
  $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $anime['quizzes'] = $quizzes;
  $animesComQuizzes[] = $anime;
}
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
    <div class="quiz-container">
      <h1>Missões de Conhecimento 🎯</h1>

      <?php if (empty($animesComQuizzes)): ?>
        <p>Você precisa favoritar algum anime para desbloquear quizzes!</p>
      <?php else: ?>
        <?php foreach ($animesComQuizzes as $anime): ?>
          <div class="quiz-bloco">
            <div class="quiz-header">
              <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>">
              <h2><?= htmlspecialchars($anime['nome']) ?></h2>
            </div>

            <div class="quiz-linha">
              <?php if ($anime['quizzes']): ?>
                <?php foreach ($anime['quizzes'] as $quiz): ?>
                  <?php $liberado = $nivelUsuario >= $quiz['nivel_minimo']; ?>
                  <div class="quiz-card <?= $liberado ? 'liberado' : 'bloqueado' ?>">
                    <?php if ($liberado): ?>
                      <a href="quiz_jogar.php?id=<?= $quiz['id'] ?>">
                        <div class="numero"><?= $quiz['nivel_minimo'] ?></div>
                        <p><?= htmlspecialchars($quiz['titulo']) ?></p>
                      </a>
                    <?php else: ?>
                      <div class="cadeado">🔒</div>
                      <p>Nível <?= $quiz['nivel_minimo'] ?></p>
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
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>