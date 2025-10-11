<?php 
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/usuarios.php';
require __DIR__ . '/../shared/auth.php'; 
require_once __DIR__ . '/../shared/register.php';

$errors = [];
$result = null;

// se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registrarUsuario(
        $pdo,
        $_POST['username'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? '',
        $_POST['password_confirm'] ?? ''
    );

    if ($result['success']) {
        header('Location: login.php');
        exit;
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" /> 
<title>Cadastro - Anime Space</title> 
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="login"> 
<div class="login-container">
  <div class="login-box">
    <h2>Cadastro</h2> 
    
    <?php if ($errors): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form action="register.php" method="post">
      <div class="textbox">
        <input type="text" name="username" placeholder="Nome de usuário" required 
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="email" name="email" placeholder="Email" required 
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <div class="textbox">
        <input type="password" name="password_confirm" placeholder="Confirme a senha" required />
      </div>
      <input type="submit" value="Cadastrar" class="btn" />
    </form>

    <div class="links">
      <a href="../../PHP/user/login.php">Esqueceu a senha?</a>
      <a href="../../PHP/user/login.php">Já tem conta? Faça login</a>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
