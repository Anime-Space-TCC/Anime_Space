<?php
session_start();
require __DIR__ . '/../../PHP/shared/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../PHP/user/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Painel de Controle - Admin</title>
  <link rel="stylesheet" href="../../CSS/style.css?v=2" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">

  <!-- Header -->
  <div class="admin-links">
    <h1>Painel de Controle</h1>
    <nav>
      <a href="../../PHP/user/index.php">Home</a> 
      <a href="../../PHP/shared/logout.php" class="admin-btn">Sair</a> 
    </nav>
  </div>

  <!-- Conteúdo -->
  <main class="admin-dashboard">
  <h2>Gerenciar CRUDS</h2>

  <div class="admin-cards">
    <div class="admin-card">
      <img src="../../img/slogan3.png" alt="Animes">
      <h3>Animes</h3>
      <p>Gerencie todos os animes cadastrados.</p><br>
      <a href="../../PHP/admin/animes/admin_animes.php" class="admin-btn">📂 Acessar</a>
    </div>

    <div class="admin-card">
      <img src="../../img/slogan3.png" alt="Quizzes">
      <h3>Quizzes</h3>
      <p>Controle os quizzes disponíveis.</p><br>
      <a href="../../PHP/admin/quiz/admin_quiz.php" class="admin-btn">📂 Acessar</a>
    </div>

    <div class="admin-card">
      <img src="../../img/slogan3.png" alt="Temporadas">
      <h3>Temporadas</h3>
      <p>Gerencie as temporadas dos animes.</p><br>
      <a href="../../PHP/admin/temporadas/admin_temporadas.php" class="admin-btn">📂 Acessar</a>
    </div>

    <div class="admin-card">
      <img src="../../img/slogan3.png" alt="Episódios">
      <h3>Episódios</h3>
      <p>Gerencie os episódios lançados.</p><br>
      <a href="../../PHP/admin/episodes/admin_episodes.php" class="admin-btn">📂 Acessar</a>
    </div>

    <div class="admin-card">
      <img src="../../img/slogan3.png" alt="Produtos">
      <h3>Jojinha</h3>
      <p>Gerencie os produtos lançados.</p><br>
      <a href="../../PHP/admin/produtos/admin_produto.php" class="admin-btn">📂 Acessar</a>
    </div>
  </div>
</main>
</body>
</html>
