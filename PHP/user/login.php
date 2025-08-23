<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/auth.php';

$errors = [];

// Verifica se o formulário foi submetido via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Chama a função de login passando username e password do formulário
    // O operador null coalescing ?? garante que, se o campo não existir, será usado string vazia
    $resultado = login($pdo, $_POST['username'] ?? '', $_POST['password'] ?? '');

    // Se login for bem-sucedido
    if ($resultado['success']) {
        // Redireciona o usuário para a página de perfil
        header('Location: ../../PHP/user/profile.php');
        exit; // interrompe a execução após o redirecionamento
    } else {
        // Caso haja erro no login, adiciona a mensagem ao array de erros
        $errors[] = $resultado['error'];
    }
}


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
        <input type="text" name="username" placeholder="Usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <input type="submit" value="Entrar" class="btn" />
    </form>

    <div class="links">
      <p>Não tem conta?<a href="register.php">Cadastre-se</a></p>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
