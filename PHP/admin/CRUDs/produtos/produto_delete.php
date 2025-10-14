<?php
require __DIR__ . '/../../shared/conexao.php'; 
session_start();  

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Obtém o ID do produto e garante que seja inteiro
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID inválido.");
}

// Opcional: verifica se o produto existe antes de deletar
$stmt = $pdo->prepare("SELECT id, nome FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

// Deleta o produto
$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
$stmt->execute([$id]);

// Redireciona para o painel de produtos
header("Location: admin_produto.php");
exit();
