<?php
session_start(); // Inicia a sessão para gerenciar autenticação
require __DIR__ . '/../../shared/conexao.php'; // Inclui conexão com o banco

// Verifica se o usuário é admin, se não for redireciona para login
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os episodeos
$episodes = $pdo->query("SELECT * FROM episodios ORDER BY anime_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Episódios</title>
  <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Episódios</h1>
    <nav>
      <a href="../../../PHP/user/index.php">Home</a> 
      <a href="../../../PHP/admin/episodes/episodes_form.php" class="admin-btn">Novo Episódio</a> 
      <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Anime</th>
          <th>Temporada</th>
          <th>Episódio</th>
          <th>Título</th>
          <th>Descriçao</th>
          <th>Duração</th>
          <th>Lançamento</th>
          <th>Miniatura</th>
          <th>Linguagem</th>
          <th>Link Video</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($episodes as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['anime_id']) ?></td>
            <td><?= htmlspecialchars($e['temporada']) ?></td>
            <td><?= htmlspecialchars($e['numero']) ?></td>
            <td><?= htmlspecialchars($e['titulo']) ?></td>
            <td><?= htmlspecialchars($e['descricao']) ?></td>
            <td><?= htmlspecialchars($e['duracao']) ?></td>
            <td><?= htmlspecialchars($e['data_lancamento']) ?></td>
            <td><?= htmlspecialchars($e['miniatura']) ?></td>
            <td><?= htmlspecialchars($e['linguagem']) ?></td>
            <td><?= htmlspecialchars($e['video_url']) ?></td>
            <td>
              <a href="../../../PHP/admin/episodes/episodes_form.php?id=<?= $e['id'] ?>" class="admin-btn">✏️ Editar</a>
              <a href="../../../PHP/admin/episodes/episodes_delete.php?id=<?= $e['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este episódio?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7">Total: <?= count($episodes) ?> episódios cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
