<?php
session_start(); // Inicia a sessão para gerenciar autenticação
require __DIR__ . '/../../shared/conexao.php'; // Inclui conexão com o banco

// Verifica se o usuário é admin, se não for redireciona para login
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os episodeos
$temporadas = $pdo->query("SELECT * FROM temporadas ORDER BY anime_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Temporadas</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Temporadas</h1>
    <nav>
      <a href="../../../PHP/user/index.php">Home</a> 
      <a href="../../../PHP/admin/temporadas/temporadas_form.php" class="admin-btn">Nova Temporada</a> 
      <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Anime</th>
          <th>Temporada</th>
          <th>Descrição</th>
          <th>Ano</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($temporadas as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['anime_id']) ?></td>
            <td><?= htmlspecialchars($t['numero']) ?></td>
            <td><?= htmlspecialchars($t['descricao']) ?></td>
            <td><?= htmlspecialchars($t['ano_inicio']) ?></td>
            <td>
              <a href="../../../PHP/admin/temporadas/temporadas_form.php?id=<?= $t['id'] ?>" class="admin-btn">✏️ Editar</a>
              <a href="../../../PHP/admin/temporadas/temporadas_delete.php?id=<?= $t['id'] ?>" class="admin-btn" onclick="return confirm('Excluir esta temporada?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">Total: <?= count($temporadas) ?> temporadas cadastradas</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
