<?php
require __DIR__ . '/../shared/conexao.php';
session_start();

// Verifica se é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../../HTML/login.html');
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Anime não encontrado.";
    exit;
}

$anime = $pdo->prepare("SELECT nome FROM animes WHERE id = ?");
$anime->execute([$id]);
$animeInfo = $anime->fetch();

if (!$animeInfo) {
    echo "Anime não encontrado.";
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';

    if (!$numero || !$titulo || !$link) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO episodios (anime_id, numero, titulo, link) VALUES (?, ?, ?, ?)");
        $inserido = $stmt->execute([$id, $numero, $titulo, $link]);

        if ($inserido) {
            $sucesso = "Episódio $numero - $titulo adicionado com sucesso!";
        } else {
            $erro = "Erro ao adicionar o episódio.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Upload de Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="streaming">
  <div class="links">
    <h1>Adicionar Episódio - <?= htmlspecialchars($animeInfo['nome']) ?></h1>
    <nav>
      <a href="../../HTML/home.html">Home</a>
      <a href="episodes.php?id=<?= htmlspecialchars($id) ?>">Voltar para Episódios</a>
      <a href="stream.php">Voltar para Streaming</a>
    </nav>
  </div>

  <main>
    <?php if ($erro): ?>
      <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if ($sucesso): ?>
      <p style="color: green;"><?= htmlspecialchars($sucesso) ?></p>
    <?php endif; ?>

    <form action="upload_episodios.php?id=<?= htmlspecialchars($id) ?>" method="post">
      <label for="numero">Número do Episódio:</label><br>
      <input type="number" id="numero" name="numero" min="1" required><br><br>

      <label for="titulo">Título do Episódio:</label><br>
      <input type="text" id="titulo" name="titulo" required><br><br>

      <label for="link">Link para assistir:</label><br>
      <input type="url" id="link" name="link" required><br><br>

      <button type="submit">Adicionar Episódio</button>
    </form>
  </main>
</body>
</html>
