<?php
// =======================
// Inicialização de sessão
// =======================
session_start();

$pagamentos = $_SESSION['pagamentos_sucesso'] ?? [];
if (empty($pagamentos)) {
    die("Nenhum pagamento encontrado.");
}

// Usuário logado
$usuario = $_SESSION['username'] ?? 'Cliente';

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
    <title>Pagamento Confirmado - Animes Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body>
    <?php
    $current_page = 'loja';
    include __DIR__ . '/navbar.php';
    ?>

    <main class="loja-content">
        <div class="comprovante">
            <h1>Pagamento Confirmado!</h1>
            <p style="text-align:center;">Seu pagamento foi processado com sucesso. Seguem os detalhes da compra:</p>

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

            <div class="botoes-comprovante">
                <a href="../../PHP/user/loja.php" class="btn-voltar-comprovante">Voltar para Loja</a>
                <a href="#" class="btn-imprimir-comprovante" id="btnImprimir">Imprimir Comprovante</a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/rodape.php'; ?>

    <script>
        const usuarioNome = <?= json_encode($usuario) ?>;
    </script>

    <script src="../../JS/carrinho.js"></script>
    <script src="../../JS/comprovante.js"></script>
</body>

</html>