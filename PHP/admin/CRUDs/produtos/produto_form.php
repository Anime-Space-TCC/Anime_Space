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
    'sku' => '',
    'nome' => '',
    'descricao' => '',
    'preco' => '',
    'estoque' => '',
    'quantidade_vendida' => 0,
    'imagem' => '',
    'categoria' => '',
    'ativo' => 1,
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
    $sku = trim($_POST['sku'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? 0;
    $estoque = $_POST['estoque'] ?? 0;
    $quantidade_vendida = $_POST['quantidade_vendida'] ?? 0;
    $imagem = trim($_POST['imagem'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if ($id) {
        // Atualiza o produto
        $sql = "UPDATE produtos 
                SET sku=?, nome=?, descricao=?, preco=?, estoque=?, quantidade_vendida=?, imagem=?, categoria=?, ativo=? 
                WHERE id=?";
        $pdo->prepare($sql)->execute([$sku, $nome, $descricao, $preco, $estoque, $quantidade_vendida, $imagem, $categoria, $ativo, $id]);
    } else {
        // Insere novo produto
        $sql = "INSERT INTO produtos (sku, nome, descricao, preco, estoque, quantidade_vendida, imagem, categoria, ativo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$sku, $nome, $descricao, $preco, $estoque, $quantidade_vendida, $imagem, $categoria, $ativo]);
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
    <link rel="stylesheet" href="../../../CSS/style.css?v=3" />
    <link rel="icon" href="../../../img/slogan3.png" type="image/png">
</head>
<body class="admin">
    <div class="admin-links">
        <h1><?= $id ? "Editar Produto" : "Cadastrar Novo Produto" ?></h1> 
        <nav>
            <a href="admin_produto.php" class="admin-btn">⬅️ Voltar</a>
            <a href="../../../PHP/shared/logout.php" class="admin-btn">🚪 Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post">
            <label>SKU (Código do Produto):</label>
            <input type="text" name="sku" maxlength="50" value="<?= htmlspecialchars($produto['sku']) ?>" required><br><br>

            <label>Nome:</label>
            <input type="text" name="nome" maxlength="100" value="<?= htmlspecialchars($produto['nome']) ?>" required><br><br>

            <label>Categoria:</label>
            <input type="text" name="categoria" maxlength="50" value="<?= htmlspecialchars($produto['categoria']) ?>"><br><br>

            <label>Preço (R$):</label>
            <input type="number" name="preco" step="0.01" min="0" value="<?= htmlspecialchars($produto['preco']) ?>" required><br><br>

            <label>Estoque:</label>
            <input type="number" name="estoque" min="0" value="<?= htmlspecialchars($produto['estoque']) ?>" required><br><br>

            <label>Quantidade Vendida:</label>
            <input type="number" name="quantidade_vendida" min="0" value="<?= htmlspecialchars($produto['quantidade_vendida']) ?>"><br><br>

            <label>Descrição:</label>
            <textarea name="descricao" rows="5"><?= htmlspecialchars($produto['descricao']) ?></textarea><br><br>

            <label>Nome do arquivo da imagem:</label>
            <input type="text" name="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>"><br><br>

            <label>Status:</label><br>
            <input type="checkbox" name="ativo" class="checkbox" <?= $produto['ativo'] ? 'checked' : '' ?>> Produto ativo<br><br>

            <input type="submit" value="💾 Salvar" class="admin-btn">
        </form>
    </main>
</body>
</html>
