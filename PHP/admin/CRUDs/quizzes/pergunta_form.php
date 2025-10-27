<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) die("ID do quiz não informado.");

// Busca o quiz
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) die("Quiz não encontrado.");

// Se for edição, recebe o id da pergunta
$id = $_GET['id'] ?? null;
$pergunta = [
    'pergunta' => '',
    'alternativa_a' => '',
    'alternativa_b' => '',
    'alternativa_c' => '',
    'alternativa_d' => '',
    'resposta_correta' => 'a'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM quiz_perguntas WHERE id=? AND quiz_id=?");
    $stmt->execute([$id, $quiz_id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $pergunta = $resultado;
    } else {
        die("Pergunta não encontrada.");
    }
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Usa os valores enviados ou mantém os antigos se não foram enviados
    $texto = trim($_POST['pergunta'] ?? $pergunta['pergunta']);
    $resposta_a = trim($_POST['alternativa_a'] ?? $pergunta['alternativa_a']);
    $resposta_b = trim($_POST['alternativa_b'] ?? $pergunta['alternativa_b']);
    $resposta_c = trim($_POST['alternativa_c'] ?? $pergunta['alternativa_c']);
    $resposta_d = trim($_POST['alternativa_d'] ?? $pergunta['alternativa_d']);
    $correta = $_POST['correta'] ?? $pergunta['resposta_correta'];

    if ($id) {
        // Atualiza apenas os campos enviados
        $sql = "UPDATE quiz_perguntas SET 
                    pergunta=?, 
                    resposta_correta=?, 
                    alternativa_a=?, 
                    alternativa_b=?, 
                    alternativa_c=?, 
                    alternativa_d=? 
                WHERE id=? AND quiz_id=?";
        $pdo->prepare($sql)->execute([
            $texto, $correta, $resposta_a, $resposta_b, $resposta_c, $resposta_d, $id, $quiz_id
        ]);
    } else {
        // Insere nova pergunta
        $sql = "INSERT INTO quiz_perguntas (quiz_id, pergunta, resposta_correta, alternativa_a, alternativa_b, alternativa_c, alternativa_d)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([
            $quiz_id, $texto, $correta, $resposta_a, $resposta_b, $resposta_c, $resposta_d
        ]);
    }

    // Atualiza o total de perguntas no quiz
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_perguntas WHERE quiz_id=?");
    $stmt->execute([$quiz_id]);
    $total = $stmt->fetchColumn();
    $pdo->prepare("UPDATE quizzes SET total_perguntas=? WHERE id=?")->execute([$total, $quiz_id]);

    header("Location: perguntas.php?quiz_id=$quiz_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title><?= $id ? "Editar Pergunta" : "Nova Pergunta" ?> - <?= htmlspecialchars($quiz['titulo']) ?></title>
<link rel="stylesheet" href="../../../../CSS/style.css?v=2">
</head>
<body class="admin-cruds">

<div class="admin-links">
    <h1><?= $id ? "Editar Pergunta" : "Nova Pergunta" ?></h1>
    <nav>
        <a href="perguntas.php?quiz_id=<?= $quiz['id'] ?>" class="admin-btn">Voltar</a>
        <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
    </nav>
</div>

<main class="admin-form">
<form method="post">
    <label>Pergunta:</label><br>
    <textarea name="pergunta" required><?= htmlspecialchars($pergunta['pergunta'] ?? '') ?></textarea><br><br>

    <label>Alternativa A:</label><br>
    <input type="text" name="alternativa_a" value="<?= htmlspecialchars($pergunta['alternativa_a'] ?? '') ?>" required><br><br>

    <label>Alternativa B:</label><br>
    <input type="text" name="alternativa_b" value="<?= htmlspecialchars($pergunta['alternativa_b'] ?? '') ?>" required><br><br>

    <label>Alternativa C:</label><br>
    <input type="text" name="alternativa_c" value="<?= htmlspecialchars($pergunta['alternativa_c'] ?? '') ?>" required><br><br>

    <label>Alternativa D:</label><br>
    <input type="text" name="alternativa_d" value="<?= htmlspecialchars($pergunta['alternativa_d'] ?? '') ?>" required><br><br>

    <label>Resposta Correta:</label><br>
    <select name="correta" required>
        <option value="a" <?= ($pergunta['resposta_correta'] ?? 'a') === 'a' ? 'selected' : '' ?>>A</option>
        <option value="b" <?= ($pergunta['resposta_correta'] ?? 'a') === 'b' ? 'selected' : '' ?>>B</option>
        <option value="c" <?= ($pergunta['resposta_correta'] ?? 'a') === 'c' ? 'selected' : '' ?>>C</option>
        <option value="d" <?= ($pergunta['resposta_correta'] ?? 'a') === 'd' ? 'selected' : '' ?>>D</option>
    </select><br><br>

    <input type="submit" value="Salvar" class="admin-btn">
</form>
</main>
</body>
</html>
