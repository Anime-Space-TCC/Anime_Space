<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Recebe o ID do quiz (para edição)
$id = $_GET['id'] ?? null;

// Campos padrão
$quiz = [
    'anime_id' => '',
    'titulo' => '',
    'descricao' => '',
    'nivel_minimo' => 1,
    'capa' => '',
    'ativo' => 1
];

// Busca todos os animes para o select
$animes = $pdo->query("SELECT id, nome FROM animes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Se for edição, busca o quiz
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->execute([$id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        die("Quiz não encontrado.");
    }
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anime_id = $_POST['anime_id'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $nivel_minimo = $_POST['nivel_minimo'] ?? 1;
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    // Upload de capa (mantém a existente se nenhuma for enviada)
    $capa = $quiz['capa'] ?? '';
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid('quiz_') . '.' . $ext;
        $destino = __DIR__ . '/../../../../img/' . $nomeArquivo;
        move_uploaded_file($_FILES['capa']['tmp_name'], $destino);
        $capa = $nomeArquivo;
    }

    if ($id) {
        // Atualiza o quiz existente
        $sql = "UPDATE quizzes 
                SET anime_id=?, titulo=?, descricao=?, nivel_minimo=?, capa=?, ativo=? 
                WHERE id=?";
        $pdo->prepare($sql)->execute([$anime_id, $titulo, $descricao, $nivel_minimo, $capa, $ativo, $id]);
    } else {
        // Cria um novo quiz
        $sql = "INSERT INTO quizzes (anime_id, titulo, descricao, nivel_minimo, capa, ativo)
                VALUES (?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$anime_id, $titulo, $descricao, $nivel_minimo, $capa, $ativo]);
        $id = $pdo->lastInsertId();
    }

    header('Location: admin_quiz.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Quiz" : "Novo Quiz" ?></title> 
    <link rel="stylesheet" href="../../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin-cruds">
    <div class="admin-links">
        <h1><?= $id ? "Editar Quiz" : "Cadastrar Novo Quiz" ?></h1> 
        <nav>
            <a href="admin_quizzes.php" class="admin-btn">Voltar</a>
            <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post" enctype="multipart/form-data">
            <label>Anime relacionado:</label><br>
            <select name="anime_id" required>
                <option value="">-- Selecione o anime --</option>
                <?php foreach ($animes as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($a['id'] == $quiz['anime_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Título:</label><br>
            <input type="text" name="titulo" value="<?= htmlspecialchars($quiz['titulo']) ?>" required><br><br>

            <label>Descrição:</label><br>
            <textarea name="descricao" rows="5"><?= htmlspecialchars($quiz['descricao']) ?></textarea><br><br>

            <label>Nível mínimo (1 a 10):</label><br>
            <input type="number" name="nivel_minimo" min="1" max="10" value="<?= (int)$quiz['nivel_minimo'] ?>" required><br><br>

            <label>Imagem de capa:</label><br>
            <input type="file" name="capa"><br>
            <?php if (!empty($quiz['capa'])): ?>
                <img src="../../../../img/<?= htmlspecialchars($quiz['capa']) ?>" alt="Capa do Quiz" width="150">
            <?php endif; ?>
            <br><br>

            <label>Status:</label><br>
            <label>
                <input type="checkbox" name="ativo" <?= $quiz['ativo'] ? 'checked' : '' ?>> Ativo
            </label><br><br>

            <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
