<?php
session_start(); // Inicia a sessão para acessar variáveis de sessão

// Verifica se o usuário está logado, caso contrário redireciona para login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redireciona para a página de login
    exit; // Encerra o script para evitar execução adicional
}

$username = $_SESSION['username']; // Pega o nome de usuário da sessão para exibir
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Perfil - Anime Space</title> 
  <link rel="stylesheet" href="../../CSS/style.css" /> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="perfil"> <!-- Classe para estilização da página de perfil -->
  <div class="login-container"> <!-- Container principal da página -->
    <div class="login-box"> <!-- Caixa centralizada de conteúdo -->
      <h2>Olá, <?= htmlspecialchars($username) ?>!</h2> <!-- Saudação ao usuário -->
      <p>Seja bem-vindo ao seu perfil. Aqui você poderá visualizar e editar seus dados futuramente.</p>

      <div class="links"> <!-- Links para navegação -->
        <a href="../../PHP/user/index.php">🏠 Home</a>
        <a href="stream.php">📺 Streaming</a>
        <a href="editar_perfil.php">✏️ Editar Perfil</a>
      </div>

      <!-- Formulário para realizar logout -->
      <form action="logout.php" method="post" style="margin-top: 20px;">
        <input type="submit" value="Sair da Conta" class="btn" /> <!-- Botão para sair -->
      </form>
    </div>
  </div>
</body>
</html>
