<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Busca todas as temporadas com nome do anime
$sql = "SELECT t.*, a.nome AS anime_nome
        FROM temporadas t
        JOIN animes a ON a.id = t.anime_id
        ORDER BY a.nome, t.numero";
$temporadas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Admin - Temporadas</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2">
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Temporadas</h1>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a>
      <a href="../../../../PHP/admin/temporadas/temporadas_form.php" class="admin-btn">Nova Temporada</a>
      <a href="../../../../PHP/admin/dashboard.php" class="admin-btn">Voltar</a>
      <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Anime</th>
          <th>Número</th>
          <th>Nome</th>
          <th>Ano Início</th>
          <th>Ano Fim</th>
          <th>Episódios</th>
          <th>Capa</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($temporadas as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['anime_nome']) ?></td>
          <td><?= htmlspecialchars($t['numero']) ?></td>
          <td><?= htmlspecialchars($t['nome']) ?></td>
          <td><?= htmlspecialchars($t['ano_inicio']) ?></td>
          <td><?= htmlspecialchars($t['ano_fim']) ?></td>
          <td><?= htmlspecialchars($t['qtd_episodios']) ?></td>
          <td>
            <?php if (!empty($t['capa'])): ?>
              <img src="../../../img/<?= htmlspecialchars($t['capa']) ?>" alt="<?= htmlspecialchars($t['anime_id']) ?>" width="100">
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <a href="../../../../PHP/admin/CRUDs/temporadas/temporadas_form.php?id=<?= $t['id'] ?>" class="admin-btn">Editar</a>
            <a href="../../../../PHP/admin/CRUDs/temporadas/temporadas_delete.php?id=<?= $t['id'] ?>" class="admin-btn" onclick="return confirm('Excluir esta temporada?')">Excluir</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8">Total: <?= count($temporadas) ?> temporadas cadastradas</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
