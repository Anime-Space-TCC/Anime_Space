<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/episodios.php';
require __DIR__ . '/../shared/quizzes.php';

$episodio_id = $_GET['episodio_id'] ?? null;

if (!$episodio_id) {
    echo "Episódio não informado.";
    exit;
}

// Episódio
$episodio = buscarEpisodioComAnime($episodio_id);
if (!$episodio) {
    echo "Episódio não encontrado.";
    exit;
}

// Perguntas do quiz
$perguntas = buscarQuizPorEpisodio($episodio_id);
if (!$perguntas) {
    echo "Nenhum quiz disponível para este episódio.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quiz - <?= htmlspecialchars($episodio['titulo']) ?></title>
    <link rel="stylesheet" href="../../CSS/styleEpi.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
<div class="quiz-container">
    <h2>Quiz - <?= htmlspecialchars($episodio['anime_nome']) ?>: <?= htmlspecialchars($episodio['titulo']) ?></h2>

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

    fetch('salvar_quiz.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({episodio_id: <?= $episodio_id ?>, respostas})
    }).then(res => res.json()).then(data => {
        alert(`Você acertou ${data.acertos} de ${data.total} perguntas!`);
    });
});
</script>
</body>
</html>
