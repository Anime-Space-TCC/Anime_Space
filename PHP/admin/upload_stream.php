<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui a conexão com o banco de dados
session_start(); // Inicia a sessão para controle de autenticação

// Verifica se o usuário é admin; caso contrário, redireciona para a página de login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../../HTML/login.html');
    exit();
}

// Obtém o ID do anime via GET
$id = $_GET['id'] ?? null;

// Verifica se o ID foi passado; caso contrário, exibe mensagem e encerra
if (!$id) {
    echo "Anime não encontrado.";
    exit;
}

// Busca o nome do anime pelo ID informado
$anime = $pdo->prepare("SELECT nome FROM animes WHERE id = ?");
$anime->execute([$id]);
$animeInfo = $anime->fetch();

// Caso não encontre o anime, exibe mensagem e encerra
if (!$animeInfo) {
    echo "Anime não encontrado.";
    exit;
}

// Inicializa variáveis para mensagens de erro e sucesso
$erro = '';
$sucesso = '';

// Processa o formulário quando submetido via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $numero = $_POST['numero'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $link = $_POST['link'] ?? '';

    // Validação simples para campos obrigatórios
    if (!$numero || !$titulo || !$link) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        // Insere novo episódio na tabela
        $stmt = $pdo->prepare("INSERT INTO episodios (anime_id, numero, titulo, link) VALUES (?, ?, ?, ?)");
        $inserido = $stmt->execute([$id, $numero, $titulo, $link]);

        // Mensagem de sucesso ou erro
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
  <title>Upload de Episódios - <?= htmlspecialchars($animeInfo['nome']) ?></title> <!-- Título dinâmico com nome do anime -->
  <link rel="stylesheet" href="../../CSS/style.css"> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="streaming">
  <div class="links">
    <h1>Adicionar Episódio - <?= htmlspecialchars($animeInfo['nome']) ?></h1> <!-- Cabeçalho com o nome do anime -->
    <nav>
      <a href="../../HTML/home.html">Home</a>
      <a href="episodes.php?id=<?= htmlspecialchars($id) ?>">Voltar para Episódios</a>
      <a href="stream.php">Voltar para Streaming</a>
    </nav>
  </div>

  <main>
    <!-- Exibe mensagem de erro, se houver -->
    <?php if ($erro): ?>
      <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <!-- Exibe mensagem de sucesso, se houver -->
    <?php if ($sucesso): ?>
      <p style="color: green;"><?= htmlspecialchars($sucesso) ?></p>
    <?php endif; ?>

    <!-- Formulário para adicionar episódio -->
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
