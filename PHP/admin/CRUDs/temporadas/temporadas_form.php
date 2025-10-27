<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header('Location: ../../../../PHP/user/login.php');
  exit();
}

$id = $_GET['id'] ?? null;

$temporada = [
  'anime_id'      => '',
  'numero'        => '',
  'nome'          => '',
  'ano_inicio'    => '',
  'ano_fim'       => '',
  'qtd_episodios' => '',
  'capa'          => ''
];

// Busca todos os animes
$animes = $pdo->query("SELECT id, nome FROM animes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM temporadas WHERE id = ?");
  $stmt->execute([$id]);
  $temporada = $stmt->fetch(PDO::FETCH_ASSOC) ?: $temporada;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title><?= $id ? "Editar Temporada" : "Nova Temporada" ?></title>
  <link rel="stylesheet" href="../../../../CSS/style.css?v=2">
  <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>

<body class="admin-cruds">
  <div class="admin-links">
    <h1><?= $id ? "Editar Temporada" : "Cadastrar Nova Temporada" ?></h1>
    <nav>
      <a href="../../../../PHP/admin/CRUDs/temporadas/admin_temporadas.php" class="admin-btn">Voltar</a>
      <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
    </nav>
  </div>

  <main class="admin-form">
    <form method="post">>
      <?php if ($id): ?>
        <input type="hidden" name="id" value="<?= (int)$id ?>">
      <?php endif; ?>

      <label>Anime:</label><br>
      <select name="anime_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach ($animes as $a): ?>
          <option value="<?= $a['id'] ?>" <?= $temporada['anime_id'] == $a['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($a['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Número da Temporada:</label><br>
      <input type="number" name="numero" value="<?= htmlspecialchars($temporada['numero']) ?>" required><br><br>

      <label>Nome da Temporada:</label><br>
      <input type="text" name="nome" value="<?= htmlspecialchars($temporada['nome']) ?>"><br><br>

      <label>Ano de Início:</label><br>
      <input type="number" name="ano_inicio" value="<?= htmlspecialchars($temporada['ano_inicio']) ?>" min="1900" max="2100"><br><br>

      <label>Ano de Fim:</label><br>
      <input type="number" name="ano_fim" value="<?= htmlspecialchars($temporada['ano_fim']) ?>" min="1900" max="2100"><br><br>

      <label>Quantidade de Episódios:</label><br>
      <input type="number" name="qtd_episodios" value="<?= htmlspecialchars($temporada['qtd_episodios']) ?>"><br><br>

      <label>Imagem de Capa:</label><br>
      <input type="file" name="capa"><br>
      <?php if (!empty($temporada['capa'])): ?>
        <img src="../../../../img/<?= htmlspecialchars($temporada['capa']) ?>" alt="Imagem da Temporada" width="150"><br>
      <?php endif; ?>
      <br>

      <input type="submit" value="Salvar" class="admin-btn">
    </form>
  </main>
</body>

</html>