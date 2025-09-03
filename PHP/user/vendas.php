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
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png" /> 
</head>
<body class="loja">
  <h1>Loja Anime Space</h1>
  <div class="loja-container">
    <?php foreach ($produtos as $produto): ?>
      <div class="card-produto">
        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
        <p><?= htmlspecialchars($produto['descricao']) ?></p>
        <p class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        <button>Adicionar ao Carrinho</button>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
