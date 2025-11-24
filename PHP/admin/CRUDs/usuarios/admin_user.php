<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header('Location: ../../../PHP/user/login.php');
  exit();
}

// Verifica se há pesquisa
$busca = $_GET['buscarUsuario'] ?? '';

if (!empty($busca)) {
  $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE username LIKE :busca1 OR email LIKE :busca2
        ORDER BY data_criacao DESC
    ");
  $stmt->execute([
    ':busca1' => "%$busca%",
    ':busca2' => "%$busca%"
  ]);
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
  $stmt = $pdo->prepare("
        SELECT *
        FROM users
        ORDER BY data_criacao DESC
    ");
  $stmt->execute();
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <title>Admin - Usuários</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>

<body class="admin-cruds">
  <div class="admin-links">
    <h1>Gerenciar Usuários</h1>
    <form method="GET" class="admin-busca">
      <input type="text" name="buscarUsuario" placeholder="Buscar usuário..."
        value="<?= htmlspecialchars($_GET['buscarUsuario'] ?? '') ?>">
      <button type="submit">Buscar</button>
      <?php if (!empty($_GET['buscarUsuario'])): ?>
        <a href="admin_user.php" class="limpar-btn">Limpar</a>
      <?php endif; ?>
    </form>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a>
      <a href="user_form.php" class="admin-btn">Novo Usuário</a>
      <a href="../../../../PHP/admin/index.php" class="admin-btn">Voltar</a>
    </nav>
  </div>

  <main>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Tipo</th>
          <th>Nível</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td>
              <img src="../../../uploads/<?= htmlspecialchars(basename($u['foto_perfil'] ?: 'default.jpg')) ?>" width="60"
                alt="Foto de <?= htmlspecialchars($u['username']) ?>">
            </td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['tipo']) ?></td>
            <td><?= htmlspecialchars($u['nivel']) ?></td>
            <td>
              <a href="user_form.php?id=<?= $u['id'] ?>" class="admin-btn">Editar</a>
              <a href="user_delete.php?id=<?= $u['id'] ?>" class="admin-btn"
                onclick="return confirm('Excluir este usuário?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6">TOTAL: <?= count($usuarios) ?> usuários cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>

</html>