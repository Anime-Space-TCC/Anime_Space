<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $errors[] = "Preencha usuário e senha.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../../PHP/user/profile.php');
            exit;
        } else {
            $errors[] = "Usuário ou senha inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Login - Anime Space</title>
<link rel="stylesheet" href="../../CSS/style.css" />
</head>
<body class="login">
<div class="login-container">
  <div class="login-box">

    <!-- Imagem de login adicionada -->
    <img src="../../img/slogan3.png" alt="Imagem de Login" class="login-image" />

    <h2>Login</h2>

    <?php if ($errors): ?>
      <ul style="color: #f00; margin-bottom: 15px;">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="textbox">
        <input type="text" name="username" placeholder="Usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <input type="submit" value="Entrar" class="btn" />
    </form>

    <div class="links">
      <p>Não tem conta?<a href="register.php">Cadastre-se</a></p>
      <a href="../../HTML/home.html">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
