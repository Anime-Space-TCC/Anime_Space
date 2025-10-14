<?php
session_start(); // Inicia a sessão para gerenciar autenticação
require __DIR__ . '/../../../shared/conexao.php'; // Inclui conexão com o banco

// Verifica se o usuário é admin, se não for redireciona para login
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Consulta todos os animes
$animes = $pdo->query("SELECT * FROM animes ORDER BY nota DESC")->fetchAll(PDO::FETCH_ASSOC);

// Para cada anime, busca os gêneros relacionados
foreach ($animes as &$anime) {
    $stmt = $pdo->prepare("
        SELECT g.nome 
        FROM generos g
        INNER JOIN anime_generos ag ON g.id = ag.genero_id
        WHERE ag.anime_id = ?
        ORDER BY g.nome
    ");
    $stmt->execute([$anime['id']]);
    $generos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $anime['generos'] = implode(', ', $generos);
}
unset($anime); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Animes</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2" />
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
  <div class="admin-links">
    <h1>Gerenciar Animes</h1>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a> 
      <a href="../../../../PHP/admin/CRUDs/animes/anime_form.php" class="admin-btn">Novo Anime</a> 
      <a href="../../../../PHP/admin/dashboard.php" class="admin-btn">Voltar</a> 
      <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <main>
    <table class="admin-anime-table">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Gêneros</th>
          <th>Nota</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($animes as $a): ?>
          <tr>
            <td><img src="../../../../img/<?= htmlspecialchars($a['capa']) ?>" alt="<?= htmlspecialchars($a['nome']) ?>" width="100"></td>
            <td><?= htmlspecialchars($a['nome']) ?></td>
            <td><?= htmlspecialchars($a['generos']) ?></td>
            <td class="destaque"><?= number_format($a['nota'], 1) ?></td>
            <td>
              <a href="../../../../PHP/admin/CRUDs/animes/anime_form.php?id=<?= $a['id'] ?>" class="admin-btn">Editar</a>
              <a href="../../../../PHP/admin/CRUDs/animes/anime_delete.php?id=<?= $a['id'] ?>" class="admin-btn" onclick="return confirm('Excluir este anime?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">Total: <?= count($animes) ?> animes cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
