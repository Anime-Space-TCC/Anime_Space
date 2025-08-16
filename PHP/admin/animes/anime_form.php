<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Recebe o ID para edição
$id = $_GET['id'] ?? null;

// Campos padrão
$anime = [
    'nome' => '',
    'nota' => '',
    'capa' => '',
    'descricao' => '',
    'ano' => ''
];

// Pega todos os gêneros do banco
$todosGeneros = $pdo->query("SELECT * FROM generos ORDER BY nome")->fetchAll();

// Se estiver editando, pega os gêneros já selecionados
$generosSelecionados = [];
if ($id) {
    $stmt = $pdo->prepare("SELECT genero_id FROM anime_generos WHERE anime_id = ?");
    $stmt->execute([$id]);
    $generosSelecionados = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Se for edição, busca o anime
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM animes WHERE id = ?");
    $stmt->execute([$id]);
    $anime = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anime) {
        die("Anime não encontrado.");
    }
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $generos = $_POST['generos'] ?? []; // array de IDs
    $nota = $_POST['nota'] ?? 0;
    $capa = $_POST['capa'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $ano = $_POST['ano'] ?? '';

    if ($id) {
        // Atualiza o anime
        $sql = "UPDATE animes SET nome=?, nota=?, capa=?, descricao=?, ano=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nome, $nota, $capa, $descricao, $ano, $id]);
    } else {
        // Insere novo anime
        $sql = "INSERT INTO animes (nome, nota, capa, descricao, ano) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$nome, $nota, $capa, $descricao, $ano]);
        $id = $pdo->lastInsertId(); // pega o ID do anime recém-criado
    }

    // Atualiza os gêneros
    $pdo->prepare("DELETE FROM anime_generos WHERE anime_id = ?")->execute([$id]);
    if (!empty($generos)) {
        $stmt = $pdo->prepare("INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)");
        foreach ($generos as $genero_id) {
            $stmt->execute([$id, $genero_id]);
        }
    }

    header('Location: admin_animes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Anime" : "Novo Anime" ?></title> 
    <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
    <div class="admin-links">
        <h1><?= $id ? "Editar Anime" : "Cadastrar Novo Anime" ?></h1> 
        <nav>
            <a href="admin_animes.php" class="admin-btn">Voltar</a>
            <a href="logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($anime['nome']) ?>" required><br><br>

            <label>Gêneros:</label><br>
            <?php foreach($todosGeneros as $g): ?>
                <input type="checkbox" name="generos[]" value="<?= $g['id'] ?>"
                <?= in_array($g['id'], $generosSelecionados) ? 'checked' : '' ?>>
                <?= htmlspecialchars($g['nome']) ?><br>
            <?php endforeach; ?>

            <label>Ano de lançamento:</label><br>
            <input type="number" name="ano" value="<?= htmlspecialchars($anime['ano']) ?>" required><br><br>

            <label>Descrição:</label><br>
            <textarea name="descricao" rows="5" required><?= htmlspecialchars($anime['descricao']) ?></textarea><br><br>

            <label>Nota:</label><br>
            <input type="number" name="nota" step="0.1" min="0" max="10" value="<?= htmlspecialchars($anime['nota']) ?>" required><br><br>

            <label>Nome do arquivo da imagem:</label><br>
            <input type="text" name="capa" value="<?= htmlspecialchars($anime['capa']) ?>" required><br><br>

            <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
