<?php
session_start();
require __DIR__ . '/conexao.php';

$pagamentos = $_SESSION['pagamentos_confirmados'] ?? [];
if (empty($pagamentos)) die("Nenhum pagamento encontrado.");

foreach ($pagamentos as $p) {
    $sku = $p['sku'] ?? null;
    if (!$sku) die("❌ SKU não informado - Valor: " . $p['valor']);

    $valor = floatval(str_replace(',', '.', str_replace('.', '', $p['valor'])));
    $metodo = $p['tipo'] ?? 'cartao';

    // Buscar produto
    $stmt = $pdo->prepare("SELECT id, estoque, quantidade_vendida FROM produtos WHERE sku = ?");
    $stmt->execute([$sku]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) die("❌ Produto não encontrado para o SKU: " . htmlspecialchars($sku));

    $produto_id = $produto['id'];

    // Registrar pagamento real
    $stmt = $pdo->prepare("INSERT INTO pagamentos (user_id, produto_id, valor, metodo, status)
                           VALUES (?, ?, ?, ?, 'aprovado')");
    $stmt->execute([$_SESSION['user_id'], $produto_id, $valor, $metodo]);

    // Atualizar estoque e vendas
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - 1, quantidade_vendida = quantidade_vendida + 1 WHERE id = ?");
    $stmt->execute([$produto_id]);
}

// Salva os pagamentos em uma sessão temporária para mostrar no comprovante
$_SESSION['pagamentos_sucesso'] = $_SESSION['pagamentos_confirmados'];

// ✅ Limpa sessão dos pagamentos (somente carrinho, se quiser)
unset($_SESSION['pagamentos_confirmados']);

// Redireciona para a página de sucesso
header("Location: ../../PHP/user/pagamento_sucesso.php");
exit;
