<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';
verificarLogin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Soma o total
$totalCarrinho = array_sum($_SESSION['carrinho']);

// Busca produtos
$produtosCarrinho = [];
if (!empty($_SESSION['carrinho'])) {
    $ids = array_keys($_SESSION['carrinho']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produtosCarrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $produtosCarrinhoById = [];
    foreach ($produtosCarrinho as $produto) {
        $produtosCarrinhoById[$produto['id']] = $produto;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meu Carrinho - Anime Space</title>
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body>

<?php
$current_page = 'meu-carrinho'; 
include __DIR__ . '/navbar.php';
?>

<main class="loja-content">
    <h1 class="titulo-carrinho">🛒 Meu Carrinho</h1>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <div class="carrinho-lista">
            <?php foreach ($_SESSION['carrinho'] as $id => $quantidade): ?>
                <?php
                if (!isset($produtosCarrinhoById[$id])) continue;
                $produto = $produtosCarrinhoById[$id];
                ?>
                <div class="carrinho-item">
                    <img src="../../img/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <div class="carrinho-info">
                        <span class="nome-produto"><?= htmlspecialchars($produto['nome']) ?></span>
                        <span>Qtd: <input type="number" min="1" value="<?= $quantidade ?>" class="quantidade" data-id="<?= $id ?>"></span>
                        <span>Preço: <strong>R$ <?= number_format($produto['preco'] * $quantidade, 2, ',', '.') ?></strong></span>
                    </div>
                    <button class="btn-remover" data-id="<?= $id ?>">Remover</button>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="total-itens"><strong>Total de itens:</strong> <span id="totalCarrinho"><?= $totalCarrinho ?></span></p>

        <!-- 🔽 MÉTODOS DE PAGAMENTO (fora da lista) -->
        <form action="../../PHP/user/processar_pagamento.php" method="POST" class="form-pagamento">
            <input type="hidden" name="metodo" class="input-metodo" value="">
            <div class="pagamento-container">
                <h2>Escolha sua forma de pagamento</h2>
                <div class="pagamento-opcao" data-metodo="cartao">
                    <img src="../../img/cartao.jpg" alt="Cartão">
                    Cartão de Crédito/Débito
                </div>
                <div class="pagamento-opcao" data-metodo="pix">
                    <img src="../../img/pix.jpg" alt="PIX">
                    PIX
                </div>
                <div class="pagamento-opcao" data-metodo="boleto">
                    <img src="../../img/boleto.jpg" alt="Boleto">
                    Boleto Bancário
                </div>
                <button type="submit" class="btn-finalizar">Finalizar Compra</button>
            </div>
        </form>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/rodape.php'; ?>

<script src="../../JS/carrinho.js"></script>
<script src="../../JS/produto.js"></script>
<script src="../../JS/pagamento.js"></script>

</body>
</html>

