<?php
session_start();

// === Lógica PHP de desbloqueio e nível ===
$nivel = $_SESSION['nivel'] ?? 3; // exemplo de nível do usuário
$titulo = $_SESSION['titulo'] ?? "Aprendiz"; // título do usuário
$favoritado = $_SESSION['favoritado'] ?? true; // anime favoritado

// Lista de quizzes
$quizzes = [
    ["nome" => "Quiz 1", "nivel" => 1],
    ["nome" => "Quiz 2", "nivel" => 2],
    ["nome" => "Quiz 3", "nivel" => 3],
    ["nome" => "Quiz 4", "nivel" => 4],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quizzes de Animes</title>
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body ">
<?php
    $current_page = 'busca'; 
    include __DIR__ . '/navbar.php'; 
?>
    <main class="page-content">
        <div class="quiz-page">
            <h1 class="titulo-pagina">Quizzes de Animes</h1>
            <div class="anime-quiz-card">
                <img src="imagens/anime_exemplo.jpg" alt="Anime Favoritado" class="anime-img">

                <div class="quizzes">
                    <?php foreach ($quizzes as $q): 
                        $desbloqueado = $favoritado && $nivel >= $q['nivel'];
                    ?>
                        <div class="quiz-box <?= $desbloqueado ? 'desbloqueado' : 'bloqueado' ?>">
                            <?php if ($desbloqueado): ?>
                                <a href="questao.php?quiz=<?= urlencode($q['nome']) ?>"><?= $q['nome'] ?></a>
                            <?php else: ?>
                                <span class="cadeado">🔒</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php include __DIR__ . '/rodape.php'; ?>
    </main>
</body>
</html>
