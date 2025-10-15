<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/acessos.php';
verificarLogin();

// Início da sessão (apenas se não existir)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa carrinho se necessário
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Busca produtos ativos
$stmt = $pdo->query("SELECT * FROM produtos WHERE ativo = 1 ORDER BY data_criacao DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de itens no carrinho
$totalCarrinho = array_sum($_SESSION['carrinho']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Loja - Anime Space</title>
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body class="loja">

<?php
// Para a loja
$current_page = 'loja'; 
include __DIR__ . '/navbar.php';
?>

<main class="page-content">
    <header class="loja-header">
        <div class="titulo-pagina">
            <h1>Loja Anime Space</h1>
        </div>
    </header>

    <!-- Banner de destaque -->
    <section class="loja-banner">
        <?php include __DIR__ . '/promocoes.php'; ?>
    </section>


    <!-- Grid de produtos -->
    <section class="loja-conteudo" id="produtos">
        <h2 class="secao-titulo">Novidades da Loja</h2>
        <div class="produtos-grid">
            <?php foreach ($produtos as $produto): ?>
                <div class="produto-card">
                    <div class="produto-imagem-box">
                        <img src="../../img/<?= htmlspecialchars($produto['imagem']) ?>" 
                             alt="<?= htmlspecialchars($produto['nome']) ?>" 
                             class="produto-imagem">
                    </div>
                    <div class="produto-info">
                        <h3 class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p class="produto-descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                        <p class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                        <p class="produto-estoque">Estoque: <?= $produto['estoque'] ?></p>
                        <?php if($produto['estoque'] > 0): ?>
                            <button class="btn-adicionar" data-id="<?= $produto['id'] ?>">Adicionar ao Carrinho</button>
                        <?php else: ?>
                            <button class="btn-adicionar" disabled>Indisponível</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/rodape.php'; ?>

<script src="../../JS/carrinho.js"></script>

</body>
</html>
