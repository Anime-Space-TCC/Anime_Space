<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui conexão com o banco de dados
session_start(); // Inicia a sessão para controle de login

// Verifica se o usuário é admin; caso contrário, redireciona para login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../../PHP/user/login.php');
    exit();
}

// Verifica se está editando (se recebeu id via GET)
$id = $_GET['id'] ?? null;

// Inicializa um array com campos vazios para o formulário
$anime = ['nome' => '', 'generos' => '', 'nota' => '', 'imagem' => ''];

// Se for edição, busca os dados do anime pelo ID
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM animes WHERE id = ?");
    $stmt->execute([$id]);
    $anime = $stmt->fetch();

    // Se não encontrou o anime, encerra o script com mensagem
    if (!$anime) {
        die("Anime não encontrado.");
    }
}

// Processa o envio do formulário (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $generos = $_POST['generos'];
    $nota = $_POST['nota'];
    $imagem = $_POST['imagem'];

    if ($id) {
        // Atualiza os dados do anime existente
        $sql = "UPDATE animes SET nome=?, generos=?, nota=?, imagem=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nome, $generos, $nota, $imagem, $id]);
    } else {
        // Insere novo anime no banco
        $sql = "INSERT INTO animes (nome, generos, nota, imagem) VALUES (?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$nome, $generos, $nota, $imagem]);
    }

    // Redireciona para página de administração após salvar
    header('Location: admin_animes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Anime" : "Novo Anime" ?></title> 
    <link rel="stylesheet" href="../CSS/style.css"> 
</head>
<body class="recomendacao">
    <div class="links">
        <h1><?= $id ? "Editar Anime" : "Cadastrar Novo Anime" ?></h1> 
        <nav>
            <a href="admin_animes.php">Voltar</a> | 
            <a href="logout.php">Sair</a> 
        </nav>
    </div>

    <main style="max-width: 600px; margin: auto; color: white;">
        <form method="post">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($anime['nome']) ?>" required><br><br>

            <label>Gêneros:</label><br>
            <input type="text" name="generos" value="<?= htmlspecialchars($anime['generos']) ?>" required><br><br>

            <label>Nota:</label><br>
            <input type="number" name="nota" step="0.1" min="0" max="10" value="<?= htmlspecialchars($anime['nota']) ?>" required><br><br>

            <label>Nome do arquivo da imagem:</label><br>
            <input type="text" name="imagem" value="<?= htmlspecialchars($anime['imagem']) ?>" required><br><br>

            <input type="submit" value="Salvar" class="btn"> 
        </form>
    </main>
</body>
</html>
