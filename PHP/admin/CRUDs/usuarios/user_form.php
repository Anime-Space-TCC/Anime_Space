<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$usuario = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Editar Usuário" : "Novo Usuário" ?></title>
    <link rel="stylesheet" href="../../../../CSS/style.css?v=2">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body class="admin-cruds">

    <div class="admin-links">
        <h1><?= $id ? "Editar Usuário" : "Novo Usuário" ?></h1>
        <nav>
            <a href="admin_user.php" class="admin-btn">Voltar</a>
        </nav>
    </div>

    <form action="user_save.php" method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?? '' ?>">

        <label>Username:</label>
        <input type="text" name="username" required value="<?= $usuario['username'] ?? '' ?>">

        <label>Email:</label>
        <input type="email" name="email" required value="<?= $usuario['email'] ?? '' ?>">

        <label>Senha (deixe vazio para não alterar):</label>
        <input type="password" name="password">

        <label>Tipo:</label>
        <select name="tipo">
            <option value="user" <?= (isset($usuario) && $usuario['tipo'] === 'user') ? 'selected' : '' ?>>Usuário</option>
            <option value="admin" <?= (isset($usuario) && $usuario['tipo'] === 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>

        <label>Foto de perfil:</label>
        <input type="file" name="foto_perfil">

        <?php if ($usuario): ?>
            <p>Foto atual: <img src="../../../uploads/<?= $usuario['foto_perfil'] ?>" width="80"></p>
        <?php endif; ?>

        <button type="submit" class="admin-btn">Salvar</button>
    </form>

</body>

</html>