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
$animes = $pdo->query("SELECT id, nome FROM animes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Se for edição, busca os dados do quiz
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->execute([$id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        die("Quiz não encontrado.");
    }
}

// Busca temporadas do anime selecionado
$temporadas = [];
if ($quiz['anime_id']) {
    $stmt = $pdo->prepare("SELECT numero, nome FROM temporadas WHERE anime_id = ? ORDER BY numero");
    $stmt->execute([$quiz['anime_id']]);
    $temporadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Processa envio do formulário (mantido igual)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pergunta = $_POST['pergunta'] ?? '';
    $a = $_POST['alternativa_a'] ?? '';
    $b = $_POST['alternativa_b'] ?? '';
    $c = $_POST['alternativa_c'] ?? '';
    $d = $_POST['alternativa_d'] ?? '';
    $resposta = $_POST['resposta_correta'] ?? 'A';
    $anime_id = $_POST['anime_id'] ?? null;
    $temporada = $_POST['temporada'] ?? null;

    if ($id) {
        // Atualiza quiz
        $sql = "UPDATE quizzes 
                SET pergunta=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, resposta_correta=?, anime_id=?, temporada=? 
                WHERE id=?";
        $pdo->prepare($sql)->execute([$pergunta, $a, $b, $c, $d, $resposta, $anime_id, $temporada, $id]);
    } else {
        // Insere quiz
        $sql = "INSERT INTO quizzes (pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, resposta_correta, anime_id, temporada) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$pergunta, $a, $b, $c, $d, $resposta, $anime_id, $temporada]);
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
            <a href="../../../shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post">
            <label>Pergunta:</label><br>
            <textarea name="pergunta" rows="3" required><?= htmlspecialchars($quiz['pergunta']) ?></textarea><br><br>

            <label>Alternativa A:</label><br>
            <input type="text" name="alternativa_a" value="<?= htmlspecialchars($quiz['alternativa_a']) ?>" required><br><br>

            <label>Alternativa B:</label><br>
            <input type="text" name="alternativa_b" value="<?= htmlspecialchars($quiz['alternativa_b']) ?>" required><br><br>

            <label>Alternativa C:</label><br>
            <input type="text" name="alternativa_c" value="<?= htmlspecialchars($quiz['alternativa_c']) ?>" required><br><br>

            <label>Alternativa D:</label><br>
            <input type="text" name="alternativa_d" value="<?= htmlspecialchars($quiz['alternativa_d']) ?>" required><br><br>

            <label>Resposta Correta:</label><br>
            <select name="resposta_correta" required>
                <option value="A" <?= $quiz['resposta_correta'] === 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= $quiz['resposta_correta'] === 'B' ? 'selected' : '' ?>>B</option>
                <option value="C" <?= $quiz['resposta_correta'] === 'C' ? 'selected' : '' ?>>C</option>
                <option value="D" <?= $quiz['resposta_correta'] === 'D' ? 'selected' : '' ?>>D</option>
            </select><br><br>

            <label>Relacionar Anime:</label><br>
            <select name="anime_id" required>
                <option value="">Selecione</option>
                <?php foreach($animes as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= $quiz['anime_id'] == $a['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Temporada:</label><br>
            <select name="temporada" id="temporadaSelect" required>
                <option value="">Selecione a temporada</option>
                <?php foreach($temporadas as $temp): ?>
                    <option value="<?= $temp['numero'] ?>" <?= (isset($quiz['temporada']) && $quiz['temporada'] == $temp['numero']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($temp['nome'] ?: "Temporada {$temp['numero']}") ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
