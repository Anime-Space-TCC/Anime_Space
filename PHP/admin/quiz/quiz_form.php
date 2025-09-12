<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Recebe o ID do quiz (se for edição)
$id = $_GET['id'] ?? null;

// Dados padrão do quiz
$quiz = [
    'pergunta' => '',
    'alternativa_a' => '',
    'alternativa_b' => '',
    'alternativa_c' => '',
    'alternativa_d' => '',
    'resposta_correta' => 'A',
    'anime_id' => '',
    'temporada' => ''
];

// Busca todos os animes para relacionar o quiz
$stmt = $pdo->prepare("SELECT id, nome FROM animes ORDER BY nome");
$stmt->execute();
$animes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se for edição, busca os dados do quiz
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->execute([$id]);
    $quizFromDb = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quizFromDb) {
        $quiz = array_merge($quiz, $quizFromDb);
    } else {
        die("Quiz não encontrado.");
    }
}

// Detecta anime selecionado (POST > GET > edição)
$animeSelecionadoId = $_POST['anime_id'] ?? ($quiz['anime_id'] ?? null);

// Busca temporadas do anime selecionado
$temporadas = [];
if ($animeSelecionadoId) {
    $stmt = $pdo->prepare("SELECT numero, nome FROM temporadas WHERE anime_id = ? ORDER BY numero");
    $stmt->execute([$animeSelecionadoId]);
    $temporadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Define temporada selecionada (POST > edição)
$temporadaSelecionada = $_POST['temporada'] ?? ($quiz['temporada'] ?? '');

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pergunta = trim($_POST['pergunta'] ?? '');
    $a = trim($_POST['alternativa_a'] ?? '');
    $b = trim($_POST['alternativa_b'] ?? '');
    $c = trim($_POST['alternativa_c'] ?? '');
    $d = trim($_POST['alternativa_d'] ?? '');
    $resposta = $_POST['resposta_correta'] ?? 'A';
    $anime_id = $_POST['anime_id'] ?? null;
    $temporada = $_POST['temporada'] ?? null;

    if ($id) {
        // Atualiza quiz
        $sql = "UPDATE quizzes 
                SET pergunta=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, resposta_correta=?, anime_id=?, temporada=? 
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pergunta, $a, $b, $c, $d, $resposta, $anime_id, $temporada, $id]);
    } else {
        // Insere quiz
        $sql = "INSERT INTO quizzes (pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, resposta_correta, anime_id, temporada) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pergunta, $a, $b, $c, $d, $resposta, $anime_id, $temporada]);
    }

    header('Location: ../../../PHP/admin/quiz/admin_quiz.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Editar Quiz" : "Novo Quiz" ?></title>
    <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../img/slogan3.png" type="image/png">
</head>
<body class="admin">
<div class="admin-links">
    <h1><?= $id ? "Editar Quiz" : "Cadastrar Novo Quiz" ?></h1>
    <nav>
        <a href="../../../PHP/admin/quiz/admin_quiz.php" class="admin-btn">Voltar</a>
        <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
    </nav>
</div>

<main class="admin-form">
    <form method="post" id="quizForm">
        <label>Pergunta:</label><br>
        <textarea name="pergunta" rows="3" required><?= htmlspecialchars($_POST['pergunta'] ?? $quiz['pergunta']) ?></textarea><br><br>

        <label>Alternativa A:</label><br>
        <input type="text" name="alternativa_a" value="<?= htmlspecialchars($_POST['alternativa_a'] ?? $quiz['alternativa_a']) ?>" required><br><br>

        <label>Alternativa B:</label><br>
        <input type="text" name="alternativa_b" value="<?= htmlspecialchars($_POST['alternativa_b'] ?? $quiz['alternativa_b']) ?>" required><br><br>

        <label>Alternativa C:</label><br>
        <input type="text" name="alternativa_c" value="<?= htmlspecialchars($_POST['alternativa_c'] ?? $quiz['alternativa_c']) ?>" required><br><br>

        <label>Alternativa D:</label><br>
        <input type="text" name="alternativa_d" value="<?= htmlspecialchars($_POST['alternativa_d'] ?? $quiz['alternativa_d']) ?>" required><br><br>

        <label>Resposta Correta:</label><br>
        <select name="resposta_correta" required>
            <?php foreach(['A','B','C','D'] as $letra): ?>
                <option value="<?= $letra ?>" <?= ($_POST['resposta_correta'] ?? $quiz['resposta_correta']) === $letra ? 'selected' : '' ?>><?= $letra ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Relacionar Anime:</label><br>
        <select name="anime_id" id="animeSelect" required>
            <option value="">Selecione</option>
            <?php foreach($animes as $a): ?>
                <option value="<?= $a['id'] ?>" <?= ($animeSelecionadoId == $a['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Temporada:</label><br>
        <select name="temporada" id="temporadaSelect" required>
            <option value="">Selecione a temporada</option>
            <?php foreach($temporadas as $temp): ?>
                <option value="<?= $temp['numero'] ?>" <?= ((string)$temporadaSelecionada === (string)$temp['numero']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($temp['nome'] ?: "Temporada {$temp['numero']}") ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Salvar" class="admin-btn">
    </form>
</main>

<script>
// Atualiza temporadas dinamicamente via AJAX
document.getElementById('animeSelect').addEventListener('change', function() {
    const animeId = this.value;
    const temporadaSelect = document.getElementById('temporadaSelect');

    temporadaSelect.innerHTML = '<option value="">Carregando...</option>';

    fetch(`../../../PHP/shared/temporadas.php?anime_id=${animeId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Selecione a temporada</option>';
            data.forEach(t => {
                options += `<option value="${t.numero}">${t.nome || 'Temporada ' + t.numero}</option>`;
            });
            temporadaSelect.innerHTML = options;
        })
        .catch(() => {
            temporadaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        });
});
</script>
</body>
</html>
