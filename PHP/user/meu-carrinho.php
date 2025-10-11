<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';
verificarLogin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$totalCarrinho = array_sum($_SESSION['carrinho']);

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

<main class="page-content">
    <h1>Meu Carrinho</h1>

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
                    <span><?= htmlspecialchars($produto['nome']) ?></span>
                    <span>Quantidade: <input type="number" min="1" value="<?= $quantidade ?>" class="quantidade" data-id="<?= $id ?>"></span>
                    <span>Preço: R$ <?= number_format($produto['preco'] * $quantidade, 2, ',', '.') ?></span>
                    <button class="btn-remover" data-id="<?= $id ?>">Remover</button>
                </div>
            <?php endforeach; ?>
        </div>

        <p>Total de itens: <span id="totalCarrinho"><?= $totalCarrinho ?></span></p>
        <button id="finalizar">Finalizar Compra</button>

        <!-- Métodos de pagamento -->
        <div class="pagamento-container">
            <h2>Escolha sua forma de pagamento</h2>
            <div class="pagamento-opcao" data-metodo="cartao">
                <img src="../../img/cartao.png" alt="Cartão">
                Cartão de Crédito/Débito
            </div>
            <div class="pagamento-opcao" data-metodo="pix">
                <img src="../../img/pix.png" alt="PIX">
                PIX
            </div>
            <div class="pagamento-opcao" data-metodo="boleto">
                <img src="../../img/boleto.png" alt="Boleto">
                Boleto Bancário
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
// Remover produto
const removerBotoes = document.querySelectorAll('.btn-remover');
removerBotoes.forEach(btn => {
    btn.addEventListener('click', () => {
        const produtoId = btn.getAttribute('data-id');
        fetch('../shared/carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `acao=remover&id=${produtoId}`
        })
        .then(res => res.json())
        .then(data => { if(data.sucesso) location.reload(); });
    });
});

// Alterar quantidade
const quantidades = document.querySelectorAll('.quantidade');
quantidades.forEach(input => {
    input.addEventListener('change', () => {
        const id = input.dataset.id;
        let novaQtd = parseInt(input.value);
        if(novaQtd < 1) novaQtd = 1;
        input.value = novaQtd;

        fetch('../shared/carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `acao=atualizar&id=${id}&quantidade=${novaQtd}`
        })
        .then(res => res.json())
        .then(data => { if(data.sucesso) location.reload(); });
    });
});

// Seleção de pagamento (apenas visual)
const opcoes = document.querySelectorAll('.pagamento-opcao');
opcoes.forEach(op => {
    op.addEventListener('click', () => {
        opcoes.forEach(o => o.style.boxShadow = '');
        op.style.boxShadow = '0 0 30px #ff9f00';
        alert(`Você selecionou ${op.dataset.metodo.toUpperCase()}`);
    });
});
</script>

</body>
</html>
