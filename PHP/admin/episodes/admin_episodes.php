<?php
session_start();
require __DIR__ . '/../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os episódios com o nome do anime
$sql = "
    SELECT e.*, a.nome AS anime_nome
    FROM episodios e
    INNER JOIN animes a ON e.anime_id = a.id
    ORDER BY a.nome, e.temporada, e.numero
";
$episodios = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
      <a href="../../../PHP/user/index.php" class="admin-btn">Home</a> 
      <a href="../../../PHP/admin/episodes/episodes_form.php" class="admin-btn">Novo Episódio</a> 
      <a href="../../../PHP/admin/index.php" class="admin-btn">Voltar</a> 
      <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Miniatura</th>
          <th>Anime</th>
          <th>Temporada</th>
          <th>Nº</th>
          <th>Título</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($episodios as $e): ?>
          <tr>
            <td>
              <?php if (!empty($e['miniatura'])): ?>
                <img src="../../../img/<?= htmlspecialchars($e['miniatura']) ?>" alt="<?= htmlspecialchars($e['titulo']) ?>" width="100">
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($e['anime_nome']) ?></td>
            <td><?= htmlspecialchars($e['temporada']) ?></td>
            <td><?= htmlspecialchars($e['numero']) ?></td>
            <td><?= htmlspecialchars($e['titulo']) ?></td>
            <td>
              <a href="../../../PHP/admin/episodes/episodes_form.php?id=<?= $e['id'] ?>" class="admin-btn">✏️ Editar</a>
              <a href="../../../PHP/admin/episodes/episodes_delete.php?id=<?= $e['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este episódio?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7">Total: <?= count($episodios) ?> episódios cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
