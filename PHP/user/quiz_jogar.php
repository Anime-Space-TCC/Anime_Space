<?php
require_once '../shared/auth.php';
require_once '../shared/conexao.php';
require_once '../shared/gamificacao.php';

verificarLogin();
$userId = $_SESSION['user_id'];

// ID do quiz
if (!isset($_GET['id'])) {
  header('Location: quizzes.php');
  exit;
}
$quizId = (int)$_GET['id'];

// Busca informações do quiz
$stmt = $pdo->prepare("SELECT q.*, a.nome AS anime_nome 
                       FROM quizzes q
                       JOIN animes a ON q.anime_id = a.id
                       WHERE q.id = ?");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
  echo "<p>Quiz não encontrado.</p>";
  exit;
}

// Verifica se o usuário tem nível suficiente
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

// Se o quiz estiver vazio
if (!$perguntas) {
  echo "<p>Este quiz ainda não possui perguntas.</p>";
  exit;
}
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
    <div class="quiz-wrapper">
      <div class="quiz-header">
        <h1><?= htmlspecialchars($quiz['titulo']) ?></h1>
        <p class="sub"><?= htmlspecialchars($quiz['anime_nome']) ?> • Nível mínimo <?= $quiz['nivel_minimo'] ?></p>
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

    <script>
      const perguntas = <?= json_encode($perguntas) ?>;
      let indice = 0;
      let pontuacao = 0;
      const box = document.getElementById("quiz-box");
      const btnProximo = document.getElementById("btn-proximo");
      const progressFill = document.getElementById("progress-fill");

      function carregarPergunta() {
        const p = perguntas[indice];
        progressFill.style.width = ((indice) / perguntas.length * 100) + "%";
        btnProximo.disabled = true;

        box.innerHTML = `
    <div class="pergunta">
      <h2>${p.pergunta}</h2>
      <div class="alternativas">
        ${["a","b","c","d"].map(letra => `
          <button class="opcao" data-resp="${p["alternativa_" + letra]}">
            ${p["alternativa_" + letra]}
          </button>
        `).join("")}
      </div>
    </div>
  `;

        document.querySelectorAll(".opcao").forEach(btn => {
          btn.addEventListener("click", e => selecionarResposta(e, p.resposta_correta));
        });
      }

      function selecionarResposta(e, correta) {
        document.querySelectorAll(".opcao").forEach(btn => btn.disabled = true);
        const escolhido = e.target.dataset.resp;

        if (escolhido === correta) {
          e.target.classList.add("correto");
          pontuacao++;
        } else {
          e.target.classList.add("errado");
          document.querySelectorAll(".opcao").forEach(btn => {
            if (btn.dataset.resp === correta) btn.classList.add("correto");
          });
        }
        btnProximo.disabled = false;
      }

      btnProximo.addEventListener("click", () => {
        indice++;
        if (indice < perguntas.length) {
          carregarPergunta();
        } else {
          mostrarResultado();
        }
      });

      function mostrarResultado() {
        progressFill.style.width = "100%";
        const total = perguntas.length;
        const acertos = pontuacao;
        const xpGanho = Math.round(acertos / total * 50); // ganho baseado em acertos

        box.innerHTML = `
    <div class="resultado">
      <h2>Resultado Final</h2>
      <p>Você acertou <strong>${acertos}</strong> de <strong>${total}</strong> perguntas!</p>
      <p>Ganhou <strong>${xpGanho} XP</strong> 🎖️</p>
      <a href="../shared/quiz_resultado.php?quiz_id=<?= $quizId ?>&xp=${xpGanho}" class="btn-final">
        Salvar Progresso
      </a>
    </div>
  `;
        btnProximo.style.display = "none";
      }

      carregarPergunta();
    </script>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>
</body>

</html>