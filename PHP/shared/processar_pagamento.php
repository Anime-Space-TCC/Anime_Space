<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/pagamento.php';

// Verifica se o usuário está logado
verificarLogin();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../../PHP/user/login.php");
    exit;
}

// =====================================
// CANCELAR PAGAMENTO
// =====================================
if (isset($_POST['cancelar_pagamento'])) {
    // Limpa tudo relacionado à compra
    unset($_SESSION['pagamentos_confirmados']);
    $_SESSION['carrinho'] = [];

    // Redireciona de volta ao carrinho
    header("Location: ../../PHP/user/meu-carrinho.php");
    exit;
}

// =====================================
// PROCESSAR PAGAMENTO NORMAL
// =====================================
if (empty($_SESSION['carrinho'])) {
    // Se o carrinho estiver vazio, vai direto para a loja
    header("Location: ../../PHP/user/meu-carrinho.php");
    exit;
}

// Verifica o método de pagamento
$metodo = $_POST['metodo'] ?? '';
if (!in_array($metodo, ['pix', 'boleto', 'cartao'])) {
    header("Location: ../../PHP/user/meu-carrinho.php");
    exit;
}

$pagamentosConfirmados = [];

// Percorre o carrinho
foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
    for ($i = 0; $i < $quantidade; $i++) {
        // 1) Registrar pagamento
        $pagamento = registrarPagamento($pdo, $user_id, $produto_id, $metodo);
        if ($pagamento) {
            // 2) Buscar SKU do produto
            $stmt = $pdo->prepare("SELECT sku, nome FROM produtos WHERE id = ?");
            $stmt->execute([$produto_id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3) Adiciona SKU ao pagamento
            $pagamento['sku'] = $produto['sku'] ?? null;
            $pagamento['nome'] = $produto['nome'] ?? null;

            // 4) Salva no array de pagamentos confirmados
            $pagamentosConfirmados[] = $pagamento;
        }
    }
}

// Salva os pagamentos confirmados na sessão
$_SESSION['pagamentos_confirmados'] = $pagamentosConfirmados;

// Limpa o carrinho
$_SESSION['carrinho'] = [];

// Redireciona para a página de confirmação
header("Location: ../../PHP/user/confirmar_pagamento.php");
exit;
