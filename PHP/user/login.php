<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

$errors = [];

// =====================
// Processa submissão do formulário
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username) {
        $errors[] = "Informe o nome de usuário.";
    }

    if (!$password) {
        $errors[] = "Informe a senha.";
    }

    // =====================
    // Tenta realizar login
    // =====================
    if (empty($errors)) {
        $resultado = login($pdo, $username, $password);

        if ($resultado['success']) {
            // Redireciona para o perfil
            header('Location: ../../PHP/user/profile.php');
            exit;
        } else {
            $errors[] = $resultado['error'];
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
<link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="login">
<div class="login-container">
  <div class="login-box">
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
        <input type="text" name="username" placeholder="Usuário" required 
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <input type="submit" value="Entrar" class="btn" />
    </form>

    <div class="links">
      <p>Não tem conta? <a href="register.php">Cadastre-se</a></p>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
