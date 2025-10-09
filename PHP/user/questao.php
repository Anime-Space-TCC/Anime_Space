<?php
session_start();

$quiz = $_GET['quiz'] ?? 'Quiz 1';
$nivel = $_SESSION['nivel'] ?? 3;
$titulo = $_SESSION['titulo'] ?? "Aprendiz";

// === Definir perguntas por nível e quiz ===
$perguntas = [];

if ($nivel < 3) {
    // Níveis baixos -> múltipla escolha
    $perguntas = [
        ["pergunta" => "Qual é o poder do protagonista?", "opcoes" => ["Fogo", "Água", "Terra", "Vento"], "resposta" => "Fogo"],
        ["pergunta" => "Qual o nome do vilão?", "opcoes" => ["Kakarot", "Frieza", "Cell", "Vegeta"], "resposta" => "Frieza"]
    ];
} else {
    // Níveis altos -> Verdadeiro/Falso e escrita
    $perguntas = [
        ["pergunta" => "O protagonista já derrotou o chefe da vila sozinho?", "tipo" => "VF", "resposta" => "V"],
        ["pergunta" => "Descreva a técnica secreta usada no último episódio.", "tipo" => "texto", "resposta" => "Chidori"]
    ];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($quiz) ?></title>
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body>
<div class="caverna-bg">
    <h1><?= htmlspecialchars($quiz) ?></h1>

    <form method="post" action="resposta.php">
        <?php foreach ($perguntas as $i => $p): ?>
            <div class="pergunta">
                <p><?= $p['pergunta'] ?></p>

                <?php if (isset($p['opcoes'])): ?>
                    <?php foreach ($p['opcoes'] as $op): ?>
                        <label>
                            <input type="radio" name="q<?= $i ?>" value="<?= $op ?>"> <?= $op ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php elseif (($p['tipo'] ?? '') === 'VF'): ?>
                    <label><input type="radio" name="q<?= $i ?>" value="V"> Verdadeiro</label>
                    <label><input type="radio" name="q<?= $i ?>" value="F"> Falso</label>
                <?php else: ?>
                    <input type="text" name="q<?= $i ?>" placeholder="Resposta">
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
        <button type="submit">Enviar Respostas</button>
    </form>
</div>
</body>
</html>
