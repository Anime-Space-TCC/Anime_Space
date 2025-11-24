<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header('Location: ../../../../PHP/user/login.php');
  exit();
}

// Verifica se há pesquisa
$busca = $_GET['buscarTemporada'] ?? '';

if (!empty($busca)) {
  $stmt = $pdo->prepare("
        SELECT t.*, a.nome AS anime_nome
        FROM temporadas t
        JOIN animes a ON a.id = t.anime_id
        WHERE t.nome LIKE :busca1 OR a.nome LIKE :busca2
        ORDER BY a.nome, t.numero
    ");
  $stmt->execute([
    ':busca1' => "%$busca%",
    ':busca2' => "%$busca%"
  ]);
  $temporadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
  $stmt = $pdo->prepare("
        SELECT t.*, a.nome AS anime_nome
        FROM temporadas t
        JOIN animes a ON a.id = t.anime_id
        ORDER BY a.nome, t.numero
    ");
  $stmt->execute();
  $temporadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Admin - Temporadas</title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2">
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>

<body class="admin-cruds">
  <div class="admin-links">
    <h1>Gerenciar Temporadas</h1>
    <form method="GET" class="admin-busca">
      <input type="text" name="buscarTemporada" placeholder="Buscar temporada..."
        value="<?= htmlspecialchars($_GET['buscarTemporada'] ?? '') ?>">
      <button type="submit">Buscar</button>
      <?php if (!empty($_GET['buscarTemporada'])): ?>
        <a href="admin_temporadas.php" class="limpar-btn">Limpar</a>
      <?php endif; ?>
    </form>
    <nav>
      <a href="../../../../PHP/user/index.php" class="admin-btn">Home</a>
      <a href="../../../../PHP/admin/CRUDs/temporadas/temporadas_form.php" class="admin-btn">Nova Temporada</a>
      <a href="../../../../PHP/admin/index.php" class="admin-btn">Voltar</a>
    </nav>
  </div>

  <main>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Capa</th>
          <th>Anime</th>
          <th>Número</th>
          <th>Ano Início</th>
          <th>Ano Fim</th>
          <th>Episódios</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($temporadas as $t): ?>
          <tr>
            <td>
              <?php if (!empty($t['capa'])): ?>
                <img src="../../../../img/<?= htmlspecialchars($t['capa']) ?>" alt="<?= htmlspecialchars($t['anime_id']) ?>"
                  width="100">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($t['anime_nome']) ?></td>
            <td><?= htmlspecialchars($t['numero']) ?></td>
            <td><?= htmlspecialchars($t['ano_inicio']) ?></td>
            <td><?= htmlspecialchars($t['ano_fim']) ?></td>
            <td><?= htmlspecialchars($t['qtd_episodios']) ?></td>
            <td>
              <a href="../../../../PHP/admin/CRUDs/temporadas/temporadas_form.php?id=<?= (int) $t['id'] ?>"
                class="admin-btn">Editar</a>
              <a href="../../../../PHP/admin/CRUDs/temporadas/temporadas_delete.php?id=<?= (int) $t['id'] ?>"
                class="admin-btn" onclick="return confirm('Excluir esta temporada?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody> <tfoot>
        <tr>
          <td colspan="8">TOTAL:
            <?= count($temporadas) ?> temporadas cadastradas
          </td>
        </tr>
        </tfoot>
    </table>
  </main>
</body>

</html>