<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Busca todos os produtos ordenados pela data de criação
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY data_criacao DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Loja - Anime Space</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body class="loja">

  <!-- Cabeçalho -->
  <header class="loja-header">
    <div class="loja-topo">
      <h1>Loja Anime Space</h1>
      <p>Produtos exclusivos para os fãs de animes</p>
    </div>
  </header>

  <!-- Banner de destaque -->
  <section class="loja-banner">
    <div class="banner-conteudo">
      <h2>Promoção Especial</h2>
      <p>Aproveite descontos imperdíveis em camisetas, quadros e acessórios temáticos!</p>
      <a href="#produtos" class="btn-banner">Ver ofertas</a>
    </div>
  </section>

  <!-- Grid de produtos -->
  <main class="loja-conteudo" id="produtos">
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
            <button class="btn-adicionar">Adicionar ao Carrinho</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Rodapé -->
  <footer class="loja-footer">
    <p>&copy; <?= date("Y") ?> Anime Space - Todos os direitos reservados.</p>
  </footer>

</body>
</html>
