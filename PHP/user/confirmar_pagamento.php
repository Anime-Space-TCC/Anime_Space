<?php
session_start();
$pagamentos = $_SESSION['pagamentos_confirmados'] ?? [];
if (empty($pagamentos)) die("Nenhum pagamento encontrado.");

// Total
$total = 0;
foreach ($pagamentos as $p) {
    $valorItem = floatval(str_replace(',', '.', str_replace('.', '', $p['valor'])));
    $total += $valorItem;
}

// Método do primeiro pagamento
$metodo = $pagamentos[0]['tipo'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Pagamento - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
<?php
$current_page = 'confirmar_pagamento';
include __DIR__ . '/navbar.php';
?>

<main class="loja-content">
    <div class="pagamento-confirmacao">
        <h1>Confirmação de Pagamento</h1>
        <p>Método selecionado: <strong><?= htmlspecialchars(ucfirst($metodo)) ?></strong></p>

        <h2>Itens Comprados:</h2>
        <ul>
            <?php foreach ($pagamentos as $p):
                $valorItem = floatval(str_replace(',', '.', str_replace('.', '', $p['valor'])));
            ?>
            <li>SKU: <?= htmlspecialchars($p['sku'] ?? 'Não informado') ?> - Valor: R$ <?= number_format($valorItem, 2, ',', '.') ?></li>
            <?php endforeach; ?>
        </ul>

        <p class="total-itens"><strong>Total Geral:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>

        <?php if ($metodo === 'cartao'): ?>
            <h2>Pagamento com Cartão</h2>
            <form method="post" action="../shared/processar_pagamento.php" class="form-cartao">
                <input type="hidden" name="metodo" value="cartao">
                <input type="text" name="numero_cartao" placeholder="Número do Cartão" required>
                <input type="text" name="nome_titular" placeholder="Nome do Titular" required>
                <input type="text" name="validade" placeholder="Validade (MM/AA)" required>
                <input type="number" name="cvv" placeholder="CVV" required>
                <button type="submit" class="btn-confirmar">Confirmar Pagamento</button>
            </form>
        <?php elseif ($metodo === 'pix'): ?>
            <h2>Pagamento via PIX</h2>
            <div class="qrcode">
                <img src="../../img/qrcode_pix.jpg" alt="QR Code PIX" width="180"><br>
                <code><?= htmlspecialchars($pagamentos[0]['codigo']) ?></code>
            </div>
        <?php elseif ($metodo === 'boleto'): ?>
            <h2>Pagamento com Boleto</h2>
            <div class="linha-digitavel">
                <?= htmlspecialchars($pagamentos[0]['linha_digitavel'] ?? '') ?>
            </div>
            <p>Vencimento: <?= htmlspecialchars($pagamentos[0]['vencimento'] ?? '') ?></p>
        <?php endif; ?>
    </div>

    <div class="acoes-pagamento">
        <form method="post" action="../../PHP/shared/processar_pagamento_real.php">
            <button type="submit" class="btn-confirmar-real">Confirmar Pagamento</button>
        </form>

        <form method="post" action="../../PHP/shared/processar_pagamento.php">
            <input type="hidden" name="cancelar_pagamento" value="1">
            <button type="submit" class="btn-cancelar">Cancelar Pagamento</button>
        </form>
    </div>
</main>
</body>
</html>
