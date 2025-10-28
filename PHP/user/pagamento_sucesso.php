<?php
session_start();

$pagamentos = $_SESSION['pagamentos_sucesso'] ?? [];

if (empty($pagamentos)) {
    die("Nenhum pagamento encontrado.");
}

// Calcula o total geral
$totalGeral = 0;
foreach ($pagamentos as $p) {
    $valorFloat = floatval(str_replace(',', '.', str_replace('.', '', $p['valor'])));
    $totalGeral += $valorFloat;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Confirmado</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
<?php
// Navbar
$current_page = 'loja'; 
include __DIR__ . '/navbar.php';
?>

<main class="loja-content">
    <div class="comprovante">
        <h1>Pagamento Confirmado!</h1>
        <p>Seu pagamento foi processado com sucesso. Seguem os detalhes da compra:</p>

        <table>
            <tr>
                <th>SKU</th>
                <th>Produto</th>
                <th>Valor</th>
                <th>Método</th>
            </tr>
            <?php foreach ($pagamentos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['nome'] ?? '-') ?></td>
                <td>R$ <?= $p['valor'] ?></td>
                <td><?= ucfirst($p['tipo'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td colspan="2"><strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong></td>
            </tr>
        </table>

        <a href="../../PHP/user/loja.php" class="btn-voltar-comprovante">Voltar para Loja</a>
    </div>
</main>

<?php include __DIR__ . '/rodape.php'; ?>
<script src="../../JS/carrinho.js"></script>
</body>
</html>
