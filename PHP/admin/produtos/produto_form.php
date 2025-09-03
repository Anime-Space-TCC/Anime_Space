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
$produto = [
    'nome' => '',
    'preco' => '',
    'estoque' => '',
    'imagem' => '',
    'descricao' => ''
];

// Se for edição, busca o produto
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        die("Produto não encontrado.");
    }
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $estoque = $_POST['estoque'] ?? 0;
    $imagem = $_POST['imagem'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if ($id) {
        // Atualiza o produto
        $sql = "UPDATE produtos SET nome=?, preco=?, estoque=?, imagem=?, descricao=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nome, $preco, $estoque, $imagem, $descricao, $id]);
    } else {
        // Insere novo produto
        $sql = "INSERT INTO produtos (nome, preco, estoque, imagem, descricao) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$nome, $preco, $estoque, $imagem, $descricao]);
    }

    header('Location: admin_produto.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Produto" : "Novo Produto" ?></title> 
    <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
    <div class="admin-links">
        <h1><?= $id ? "Editar Produto" : "Cadastrar Novo Produto" ?></h1> 
        <nav>
            <a href="admin_produtos.php" class="admin-btn">Voltar</a>
            <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required><br><br>

            <label>Preço (R$):</label><br>
            <input type="number" name="preco" step="0.01" min="0" value="<?= htmlspecialchars($produto['preco']) ?>" required><br><br>

            <label>Estoque:</label><br>
            <input type="number" name="estoque" min="0" value="<?= htmlspecialchars($produto['estoque']) ?>" required><br><br>

            <label>Descrição:</label><br>
            <textarea name="descricao" rows="5" required><?= htmlspecialchars($produto['descricao']) ?></textarea><br><br>

            <label>Nome do arquivo da imagem:</label><br>
            <input type="text" name="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>" required><br><br>

            <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
