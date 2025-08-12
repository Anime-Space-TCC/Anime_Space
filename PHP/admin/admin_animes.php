<?php
session_start(); // Inicia a sessão para gerenciar autenticação
require __DIR__ . '/../shared/conexao.php'; // Inclui conexão com o banco

// Verifica se o usuário é admin, se não for redireciona para login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../HTML/login.html');
    exit();
}

// Consulta todos os animes ordenados pela nota, do maior para o menor
$animes = $pdo->query("SELECT * FROM animes ORDER BY nota DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Admin - Animes</title>
  <link rel="stylesheet" href="../CSS/style.css" />
</head>
<body class="recomendacao">
  <div class="links">
    <h1>Gerenciar Animes</h1>
    <nav>
      <a href="../../PHP/user/index.php">Home</a> 
      <a href="anime_form.php">Novo Anime</a> 
      <a href="logout.php">Sair</a> 
    </nav>
  </div>
  <main>
    <table class="anime-table">
      <thead>
        <tr>
          <th>Imagem</th> <!-- Coluna da imagem -->
          <th>Nome</th> <!-- Coluna do nome -->
          <th>Gêneros</th> <!-- Coluna dos gêneros -->
          <th>Nota</th> <!-- Coluna da nota -->
          <th>Ações</th> <!-- Coluna para ações: editar/excluir -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($animes as $a): ?> <!-- Itera sobre os animes -->
          <tr>
            <!-- Imagem da capa do anime -->
            <td><img src="../img/<?= htmlspecialchars($a['imagem']) ?>" alt="<?= htmlspecialchars($a['nome']) ?>" width="100"></td>
            <!-- Nome do anime -->
            <td><?= htmlspecialchars($a['nome']) ?></td>
            <!-- Gêneros do anime -->
            <td><?= htmlspecialchars($a['generos']) ?></td>
            <!-- Nota formatada com uma casa decimal e destaque -->
            <td class="destaque"><?= number_format($a['nota'], 1) ?></td>
            <!-- Links para editar e excluir o anime -->
            <td>
              <a href="anime_form.php?id=<?= $a['id'] ?>">✏️ Editar</a> |
              <a href="anime_delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Excluir este anime?')">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <!-- Exibe o total de animes cadastrados -->
          <td colspan="5">Total: <?= count($animes) ?> animes cadastrados</td>
        </tr>
      </tfoot>
    </table>
  </main>
</body>
</html>
