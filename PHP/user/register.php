<?php
session_start(); // Inicia a sessão para uso de variáveis de sessão
require __DIR__ . '/../shared/conexao.php'; // Importa a conexão com o banco de dados

$errors = []; // Array para armazenar mensagens de erro

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // Obtém e limpa o nome de usuário
    $email = trim($_POST['email'] ?? ''); // Obtém e limpa o e-mail
    $password = $_POST['password'] ?? ''; // Obtém a senha
    $password_confirm = $_POST['password_confirm'] ?? ''; // Obtém a confirmação da senha

    // Validações básicas dos campos
    if (!$username) $errors[] = "Informe um nome de usuário.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Informe um e-mail válido.";
    if (strlen($password) < 6) $errors[] = "A senha deve ter ao menos 6 caracteres.";
    if ($password !== $password_confirm) $errors[] = "As senhas não conferem.";

    // Verifica se já existe usuário ou e-mail cadastrado
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Usuário ou e-mail já cadastrado.";
        }
    }

    // Se não houver erros, insere o novo usuário no banco
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT); // Cria hash da senha
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hash])) {
            // Salva dados na sessão e redireciona para o perfil
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: profile.php');
            exit;
        } else {
            $errors[] = "Erro ao cadastrar usuário."; // Mensagem em caso de falha na inserção
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
<body class="login"> <!-- Classe para estilização de login/cadastro -->
<div class="login-container">
  <div class="login-box">
    <h2>Cadastro</h2> 
    
    <!-- Exibe mensagens de erro, se houver -->
    <?php if ($errors): ?>
      <ul style="color: #f00; margin-bottom: 15px;">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- Formulário de cadastro -->
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
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
