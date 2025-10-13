<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';
require_once __DIR__ . '/../shared/pagamento.php';

verificarLogin();
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die("Erro: usuário não logado.");

if (empty($_SESSION['carrinho'])) die("Erro: carrinho vazio.");

// Verifica o método
$metodo = $_POST['metodo'] ?? '';
if (!in_array($metodo, ['pix','boleto','cartao'])) {
    die("Erro: selecione um método de pagamento.");
}

$pagamentosConfirmados = [];

foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
    for ($i = 0; $i < $quantidade; $i++) {
        $pagamento = registrarPagamento($pdo, $user_id, $produto_id, $metodo);
        if ($pagamento) $pagamentosConfirmados[] = $pagamento;
    }
}

// Salva os pagamentos na sessão
$_SESSION['pagamentos_confirmados'] = $pagamentosConfirmados;

// Limpa o carrinho
$_SESSION['carrinho'] = [];

// Redireciona para confirmação
header("Location: confirmar_pagamento.php");
exit;
