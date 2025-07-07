<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validações básicas
    if (!$username) $errors[] = "Informe um nome de usuário.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Informe um e-mail válido.";
    if (strlen($password) < 6) $errors[] = "A senha deve ter ao menos 6 caracteres.";
    if ($password !== $password_confirm) $errors[] = "As senhas não conferem.";

    // Verifica se já existe usuário ou email
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Usuário ou e-mail já cadastrado.";
        }
    }

    // Se tudo ok, inserir usuário
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hash])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
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
        <input type="text" name="username" placeholder="Nome de usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
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
      <a href="../../HTML/home.html">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
