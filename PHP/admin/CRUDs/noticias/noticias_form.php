<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$noticia = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Produto" : "Novo Produto" ?></title> 
    <link rel="stylesheet" href="../../../../CSS/style.css?v=3" />
    <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>
<body class="admin-cruds">
    <div class="admin-links">
        <h1><?= $id ? "Editar Notícia" : "Cadastrar Nova Notícia" ?></h1> 
        <nav>
            <a href="admin_noticias.php" class="admin-btn">Voltar</a>
            <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form action="noticias_save.php<?= $id ? '?id=' . $id : '' ?>" method="post" enctype="multipart/form-data">
            <label>Título:</label><br>
            <input type="text" name="titulo" value="<?= $noticia['titulo'] ?? '' ?>" required><br><br>

            <label>Resumo:</label><br>
            <textarea name="resumo" rows="5" required><?= $noticia['resumo'] ?? '' ?></textarea><br><br>

            <label>URL Externa:</label><br>
            <input type="url" name="url_externa" value="<?= $noticia['url_externa'] ?? '' ?>" required><br><br>

            <label>Tags (separadas por vírgula):</label><br>
            <input type="text" name="tags" value="<?= $noticia['tags'] ?? '' ?>"><br><br>

            <label>Imagem:</label><br>
            <input type="file" name="imagem"><br>
            <?php if (!empty($noticia['imagem'])): ?>
                 <img src="../../../../img/<?= htmlspecialchars($noticia['imagem']) ?>" alt="Capa do Anime" width="150"><br>
            <?php endif; ?>
            <br>

            <input type="submit" value="Salvar" class="admin-btn">
        </form>
    </main>
</body>
</html>
