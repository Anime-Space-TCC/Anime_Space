<?php
session_start(); // Inicia a sessão para uso de variáveis de sessão
require __DIR__ . '/../shared/conexao.php'; // Importa o arquivo de conexão com o banco de dados

$errors = []; // Inicializa um array para armazenar mensagens de erro

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // Obtém e limpa o campo de nome de usuário
    $password = $_POST['password'] ?? ''; // Obtém a senha

    // Verifica se os campos foram preenchidos
    if (!$username || !$password) {
        $errors[] = "Preencha usuário e senha.";
    } else {
        // Prepara e executa a consulta SQL para buscar o usuário
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(); // Obtém os dados do usuário

        // Verifica se o usuário existe e se a senha está correta
        if ($user && password_verify($password, $user['password'])) {
            // Salva dados do usuário na sessão e redireciona para o perfil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../../PHP/user/profile.php'); // Redireciona
            exit; // Encerra o script
        } else {
            $errors[] = "Usuário ou senha inválidos."; // Mensagem de erro
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

    <!-- Exibe mensagens de erro, se houver -->
    <?php if ($errors): ?>
      <ul style="color: #f00; margin-bottom: 15px;">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li> <!-- Exibe cada erro -->
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form action="login.php" method="post">
      <div class="textbox">
        <input type="text" name="username" placeholder="Usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
        <!-- Campo de usuário com valor persistente -->
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
        <!-- Campo de senha -->
      </div>
      <input type="submit" value="Entrar" class="btn" /> <!-- Botão de login -->
    </form>

    <div class="links"> <!-- Links adicionais -->
      <p>Não tem conta?<a href="register.php">Cadastre-se</a></p>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
