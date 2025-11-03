<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;

// Recebe e valida os dados do formulário
$sku = trim($_POST['sku'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$preco = floatval($_POST['preco'] ?? 0);
$estoque = intval($_POST['estoque'] ?? 0);
$quantidade_vendida = intval($_POST['quantidade_vendida'] ?? 0);
$descricao = trim($_POST['descricao'] ?? '');
$ativo = isset($_POST['ativo']) ? 1 : 0;

// Upload da imagem (se enviada)
$imagem = null;
if (!empty($_FILES['imagem']['name'])) {
    $imagem = time() . "_" . basename($_FILES['imagem']['name']);
    $uploadPath = "../../../../img/" . $imagem;
    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadPath)) {
        die("Erro ao fazer upload da imagem.");
    }
}

// Validação básica
if ($sku === '') die("O campo SKU é obrigatório.");
if ($nome === '') die("O campo Nome é obrigatório.");
if ($preco < 0) die("O preço deve ser maior ou igual a 0.");
if ($estoque < 0) die("O estoque deve ser maior ou igual a 0.");
if ($quantidade_vendida < 0) die("A quantidade vendida deve ser maior ou igual a 0.");

// Atualiza ou insere o produto
if ($id) {
    // Atualiza o produto
    $sql = "UPDATE produtos 
            SET sku=?, nome=?, descricao=?, preco=?, estoque=?, quantidade_vendida=?, imagem=?, categoria=?, ativo=? 
            WHERE id=?";
    $pdo->prepare($sql)->execute([
        $sku, $nome, $descricao, $preco, $estoque, $quantidade_vendida, $imagem, $categoria, $ativo, $id
    ]);
} else {
    // Insere novo produto
    $sql = "INSERT INTO produtos (sku, nome, descricao, preco, estoque, quantidade_vendida, imagem, categoria, ativo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([
        $sku, $nome, $descricao, $preco, $estoque, $quantidade_vendida, $imagem, $categoria, $ativo
    ]);
}

// Redireciona para o painel de produtos
header('Location: ../../../../PHP/admin/CRUDs/produtos/admin_produto.php');
exit();
