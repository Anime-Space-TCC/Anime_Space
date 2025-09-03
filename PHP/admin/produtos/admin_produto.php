<?php
session_start(); // Inicia a sessão para gerenciar autenticação
require __DIR__ . '/../../shared/conexao.php'; // Inclui conexão com o banco

// Verifica se o usuário é admin, se não for redireciona para login
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os produtos
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY data_criacao DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Produtos</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Produtos</h1>
    <nav>
      <a href="../../../PHP/user/index.php" class="admin-btn">Home</a> 
      <a href="../../../PHP/admin/produtos/produto_form.php" class="admin-btn">Novo Produto</a> 
      <a href="../../../PHP/admin/index.php" class="admin-btn">Voltar</a> 
      <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Preço</th>
          <th>Estoque</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $p): ?>
          <tr>
            <td><img src="../../../img/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>" width="100"></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td class="destaque">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['estoque']) ?></td>
            <td>
              <a href="../../../PHP/admin/produtos/produto_form.php?id=<?= $p['id'] ?>" class="admin-btn">✏️ Editar</a>
              <a href="../../../PHP/admin/produtos/produto_delete.php?id=<?= $p['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este produto?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">Total: <?= count($produtos) ?> produtos cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
