<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Perfil - Anime Space</title>
  <link rel="stylesheet" href="../../CSS/style.css" />
</head>
<body class="perfil">
  <div class="login-container">
    <div class="login-box">
      <h2>Olá, <?= htmlspecialchars($username) ?>!</h2>
      <p>Seja bem-vindo ao seu perfil. Aqui você poderá visualizar e editar seus dados futuramente.</p>

      <div class="links">
        <a href="../../HTML/home.html">🏠 Home</a>
        <a href="stream.php">📺 Streaming</a>
        <a href="edit_profile.php">✏️ Editar Perfil</a>
      </div>

      <form action="logout.php" method="post" style="margin-top: 20px;">
        <input type="submit" value="Sair da Conta" class="btn" />
      </form>
    </div>
  </div>
</body>
</html>
