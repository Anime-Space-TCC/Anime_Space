<?php 
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/usuarios.php';
require __DIR__ . '/../shared/auth.php'; 

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recupera os dados do formulário, removendo espaços extras com trim
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // =====================
    // Validações dos campos
    // =====================
    if (!$username) {
        $errors[] = "Informe um nome de usuário.";
    }

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Informe um e-mail válido.";
    }

    // Senha forte (usa a função do auth.php)
    if ($err = validarSenhaForte($password)) {
        $errors[] = $err;
    }

    if ($password !== $password_confirm) {
        $errors[] = "As senhas não conferem.";
    }

    // =====================
    // Verifica duplicidade
    // =====================
    if (empty($errors) && usuarioExiste($pdo, $username, $email)) {
        $errors[] = "Usuário ou e-mail já cadastrado.";
    }

    // =====================
    // Criação do usuário
    // =====================
    if (empty($errors)) {
        // Criptografa a senha com algoritmo seguro
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $novoId = criarUsuario($pdo, $username, $email, $hash);

        if ($novoId) {
            // Cria sessão automaticamente após cadastro
            $_SESSION['user_id'] = $novoId;
            $_SESSION['username'] = $username;

            header('Location: profile.php');
            exit;
        } else {
            $errors[] = "Erro ao cadastrar usuário.";
        }
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
      <ul style="color: #f00; margin-bottom: 15px;">
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
      <a href="../../PHP/user/login.php">Já tem conta? Faça login</a>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
