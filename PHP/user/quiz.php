<?php 
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/quizzes.php';
require __DIR__ . '/../shared/animes.php'; // helper para buscar anime
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Pega o anime_id do GET e garante que seja inteiro
$anime_id = isset($_GET['anime_id']) ? (int)$_GET['anime_id'] : 0;

if ($anime_id <= 0) {
    echo "Anime não informado.";
    exit;
}

// Buscar anime pelo ID
$anime = buscarAnimePorId($anime_id);
if (!$anime) {
    echo "Anime não encontrado.";
    exit;
}

// Perguntas do quiz (por anime, não episódio)
$perguntas = buscarQuizPorAnime($anime_id);
if (!$perguntas) {
    echo "Nenhum quiz disponível para este anime.";
    exit;
}
// Verifica se uma temporada foi especificada
$temporada = isset($_GET['temporada']) ? (int)$_GET['temporada'] : null;
$perguntas = buscarQuizPorAnimeETemporada($anime_id, $temporada);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quiz - <?= htmlspecialchars($anime['nome']) ?></title>
    <link rel="stylesheet" href="../../CSS/styleEpi.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
<div class="quiz-container">
    <h2>Quiz - <?= htmlspecialchars($anime['nome']) ?></h2>

    <form id="quizForm">
        <?php foreach ($perguntas as $index => $q): ?>
            <div class="quiz-question" data-resposta="<?= htmlspecialchars($q['resposta_correta']) ?>">
                <p><strong>Pergunta <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['pergunta']) ?></p>
                <label><input type="radio" name="q<?= $q['id'] ?>" value="A"> <?= htmlspecialchars($q['alternativa_a']) ?></label><br>
                <label><input type="radio" name="q<?= $q['id'] ?>" value="B"> <?= htmlspecialchars($q['alternativa_b']) ?></label><br>
                <label><input type="radio" name="q<?= $q['id'] ?>" value="C"> <?= htmlspecialchars($q['alternativa_c']) ?></label><br>
                <label><input type="radio" name="q<?= $q['id'] ?>" value="D"> <?= htmlspecialchars($q['alternativa_d']) ?></label>
            </div>
        <?php endforeach; ?>
        <button type="button" id="submitQuiz">Enviar Respostas</button>
    </form>

    <div id="quizResultado" style="display:none;">
        <h3>Resultado:</h3>
        <p id="score"></p>
    </div>
</div>

<script>
document.getElementById('submitQuiz').addEventListener('click', function() {
    const perguntas = document.querySelectorAll('.quiz-question');
    let acertos = 0;
    let respostas = [];

    perguntas.forEach(q => {
        const correta = q.getAttribute('data-resposta');
        const selecionada = q.querySelector('input[type="radio"]:checked');
        if (selecionada && selecionada.value === correta) {
            acertos++;
        }

        const idPergunta = q.querySelector('input[type="radio"]').name.replace('q', '');
        respostas.push({
            pergunta_id: idPergunta,
            resposta_usuario: selecionada ? selecionada.value : null,
            correta: selecionada && selecionada.value === correta ? 1 : 0
        });
    });

    const total = perguntas.length;
    document.getElementById('score').textContent = `Você acertou ${acertos} de ${total} perguntas.`;
    document.getElementById('quizResultado').style.display = 'block';

    // Desabilita o botão para evitar múltiplos envios
    this.disabled = true;

    fetch('salvar_quiz.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({anime_id: <?= $anime_id ?>, respostas})
    }).then(res => res.json()).then(data => {
        if(data.status === 'duplicado') {
            alert("Você já respondeu este quiz anteriormente!");
        } else if(data.status === 'salvo') {
            alert(`Respostas salvas! Você acertou ${data.acertos} de ${data.total} perguntas.`);
        } else {
            alert("Ocorreu um erro ao salvar suas respostas.");
        }
    }).catch(() => {
        alert("Erro na comunicação com o servidor.");
    });
});
</script>
</body>
</html>
