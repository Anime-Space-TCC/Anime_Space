<?php
require_once __DIR__ . '/auth.php';
verificarLogin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$acao = $_POST['acao'] ?? '';
$id = $_POST['id'] ?? '';
$quantidade = $_POST['quantidade'] ?? 1;

// Adicionar produto
if ($acao === 'adicionar' && $id) {
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
    echo json_encode(['sucesso' => true, 'totalItens' => array_sum($_SESSION['carrinho'])]);
    exit;
}

// Remover produto
if ($acao === 'remover' && $id) {
    unset($_SESSION['carrinho'][$id]);
    echo json_encode(['sucesso' => true, 'totalItens' => array_sum($_SESSION['carrinho'])]);
    exit;
}

// Atualizar quantidade
if ($acao === 'atualizar' && $id && $quantidade > 0) {
    $_SESSION['carrinho'][$id] = (int)$quantidade;
    echo json_encode(['sucesso' => true, 'totalItens' => array_sum($_SESSION['carrinho'])]);
    exit;
}

// Caso nenhuma ação válida
echo json_encode(['sucesso' => false]);
