<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;

// Recebe e valida os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$preco = floatval($_POST['preco'] ?? 0);
$estoque = intval($_POST['estoque'] ?? 0);
$imagem = trim($_POST['imagem'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

// Validação básica
if ($nome === '') {
    die("O campo Nome é obrigatório.");
}
if ($preco < 0) {
    die("O preço deve ser maior ou igual a 0.");
}
if ($estoque < 0) {
    die("O estoque deve ser maior ou igual a 0.");
}

// Atualiza ou insere o produto
if ($id) {
    // Atualiza o produto
    $sql = "UPDATE produtos SET nome=?, preco=?, estoque=?, imagem=?, descricao=? WHERE id=?";
    $pdo->prepare($sql)->execute([$nome, $preco, $estoque, $imagem, $descricao, $id]);
} else {
    // Insere novo produto
    $sql = "INSERT INTO produtos (nome, preco, estoque, imagem, descricao) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$nome, $preco, $estoque, $imagem, $descricao]);
}

// Redireciona após salvar
header('Location: admin_produto.php');
exit();
?>
