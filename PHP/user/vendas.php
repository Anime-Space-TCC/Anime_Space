<?php
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

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
  <header class="loja-header">
    <h1>Loja Anime Space</h1>
  </header>

  <main class="produtos-grid">
    <?php foreach ($produtos as $produto): ?>
      <div class="produto-card">
        <img src="../../img/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="produto-imagem">
        <h2 class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></h2>
        <p class="produto-descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
        <p class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        <button class="btn-adicionar">Adicionar ao Carrinho</button>
      </div>
    <?php endforeach; ?>
  </main>
</body>
</html>
