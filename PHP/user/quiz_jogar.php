<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

// ====================
// Verificação de login
// ====================
verificarLogin();
$userId = $_SESSION['user_id'];

// ID do quiz
if (!isset($_GET['id'])) {
    header('Location: quizzes.php');
    exit;
}
$quizId = (int) $_GET['id'];

// Busca informações do quiz + capa do anime
$stmt = $pdo->prepare("SELECT q.*, a.nome AS anime_nome, a.capa AS anime_capa
                       FROM quizzes q
                       JOIN animes a ON q.anime_id = a.id
                       WHERE q.id = ?");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    echo "<p>Quiz não encontrado.</p>";
    exit;
}

// Verifica nível do usuário
$stmt = $pdo->prepare("SELECT nivel FROM users WHERE id = ?");
$stmt->execute([$userId]);
$nivelUsuario = (int) $stmt->fetchColumn();

if ($nivelUsuario < $quiz['nivel_minimo']) {
    echo "<p>⚠️ Você precisa ser nível {$quiz['nivel_minimo']} para acessar este quiz!</p>";
    exit;
}

// Busca perguntas
$stmt = $pdo->prepare("SELECT * FROM quiz_perguntas WHERE quiz_id = ?");
$stmt->execute([$quizId]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$perguntas) {
    echo "<p>Este quiz ainda não possui perguntas.</p>";
    exit;
}

// Ranking dos melhores jogadores
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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quiz['titulo']) ?> - Quiz</title>
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

            <!-- COLUNA ESQUERDA (QUIZ) -->
            <div class="col-esquerda">
                <div class="quiz-wrapper">
                    <div class="quiz-header">

                        <?php if (!empty($quiz['anime_capa'])): ?>
                            <img src="../../img/<?= htmlspecialchars($quiz['anime_capa']) ?>" alt="Capa do Anime"
                                class="quiz-capa">
                        <?php endif; ?>

                        <div class="progress-bar">
                            <div id="progress-fill"></div>
                        </div>
                    </div>

                    <div class="quiz-conteudo">
                        <div id="quiz-box"></div>
                    </div>

                    <div class="quiz-footer">
                        <button id="btn-proximo" disabled>Próxima</button>
                    </div>

                </div>
            </div>

            <!-- COLUNA DIREITA (RANKING) -->
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

    </main>

    <script>
        const perguntas = <?= json_encode($perguntas) ?>;
        const quizId = <?= $quizId ?>;

        let indice = 0;
        let pontuacao = 0;

        const box = document.getElementById("quiz-box");
        const btnProximo = document.getElementById("btn-proximo");
        const progressFill = document.getElementById("progress-fill");
    </script>

    <script src="../../JS/quizzes.js"></script>

    <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>