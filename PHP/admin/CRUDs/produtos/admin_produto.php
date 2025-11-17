<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Verifica se há pesquisa
$busca = $_GET['buscarProdutos'] ?? '';

if (!empty($busca)) {
    $stmt = $pdo->prepare("
        SELECT id, sku, nome, descricao, preco, promocao, preco_promocional, estoque, quantidade_vendida, imagem, categoria, ativo, data_criacao, data_atualizacao
        FROM produtos
        WHERE nome LIKE :busca1 OR categoria LIKE :busca2 OR sku LIKE :busca3
        ORDER BY data_criacao DESC
    ");
    $stmt->execute([
        ':busca1' => "%$busca%",
        ':busca2' => "%$busca%",
        ':busca3' => "%$busca%"
    ]);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    $stmt = $pdo->prepare("
        SELECT id, sku, nome, descricao, preco, promocao, preco_promocional, estoque, quantidade_vendida, imagem, categoria, ativo, data_criacao, data_atualizacao
        FROM produtos
        ORDER BY data_criacao DESC
    ");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Painel Admin - Produtos</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=3" />
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin-cruds">

  <div class="admin-links">
    <h1>Gerenciar Produtos</h1>
    <form method="GET" class="admin-busca">
      <input type="text" name="buscarProdutos" placeholder="Buscar produtos..." value="<?= htmlspecialchars($_GET['buscarProdutos'] ?? '') ?>">
      <button type="submit">Buscar</button>
      <?php if (!empty($_GET['buscarProdutos'])): ?>
        <a href="admin_produto.php" class="limpar-btn">Limpar</a>
      <?php endif; ?>
    </form>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a> 
      <a href="../../../../PHP/admin/CRUDs/produtos/produto_form.php" class="admin-btn">Novo Produto</a> 
      <a href="../../../../PHP/admin/index.php" class="admin-btn">Voltar</a> 
    </nav>
  </div>

  <main>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>SKU</th>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Preço</th>
          <th>Estoque</th>
          <th>Vendidos</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $p): ?>
          <tr class="<?= $p['ativo'] ? 'ativo-row' : 'inativo-row' ?>">
            <td>
              <?php if (!empty($p['imagem'])): ?>
                <img src="../../../../img/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
              <?php else: ?>
                <em>Sem imagem</em>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['sku']) ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= htmlspecialchars($p['categoria'] ?: '-') ?></td>
            <td class="destaque">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['estoque']) ?></td>
            <td><?= htmlspecialchars($p['quantidade_vendida']) ?></td>
            <td class="<?= $p['ativo'] ? 'ativo' : 'inativo' ?>">
              <?= $p['ativo'] ? 'Ativo' : 'Inativo' ?>
            </td>
            <td>
              <a href="../../../../PHP/admin/CRUDs/produtos/produto_form.php?id=<?= $p['id'] ?>" class="admin-btn">Editar</a>
              <a href="../../../../PHP/admin/CRUDs/produtos/produto_delete.php?id=<?= $p['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este produto?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="11">TOTAL: <?= count($produtos) ?> produto(s) cadastrado(s)</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
