<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Busca todas as notícias
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Gerenciar Noticias</h1>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a> 
      <a href="../../../../PHP/admin/CRUDs/noticias/noticias_form.php" class="admin-btn">Nova Notícia</a> 
      <a href="../../../../PHP/admin/index.php" class="admin-btn">Voltar</a> 
      <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

<main>
<table class="admin-table">
    <thead>
        <tr>
            <th>Tags</th>
            <th>Visualizações</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($noticias as $noticia): ?>
        <tr>
            <td><?= htmlspecialchars($noticia['tags']) ?></td>
            <td><?= $noticia['visualizacoes'] ?></td>
            <td>
              <?php if (!empty($noticia['imagem'])): ?>
                <img src="../../../../img/<?= htmlspecialchars($noticia['imagem']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
              <?php else: ?>
                <em>Sem imagem</em>
              <?php endif; ?>
            </td>
            <td>
                <a href="noticias_form.php?id=<?= $noticia['id'] ?>"  class="admin-btn">Editar</a>
                <a href="noticias_delete.php?id=<?= $noticia['id'] ?>" onclick="return confirm('Deseja realmente deletar?');" class="admin-btn">Deletar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
