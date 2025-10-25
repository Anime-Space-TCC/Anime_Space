<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header('Location: ../../../user/login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Painel de Controle - Admin</title>
  <link rel="stylesheet" href="../../CSS/style.css?v=6" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="admin">

  <!-- MENU LATERAL -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <img src="../../img/slogan3.png" alt="Logo" class="sidebar-logo">
      <h2>Painel Admin</h2>
    </div>

    <nav class="sidebar-menu">
      <a href="#" data-page="dashboard.php" class="active">Dashboard</a>
      <a href="#" data-page="analises.php">Análises</a>
      <a href="#" data-page="CRUDs/index.php">CRUDs</a>
      <a href="../../PHP/user/index.php" aria-label="Página Inicial" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20">
          <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z" />
        </svg>
      </a>
      <a href="../shared/logout.php" class="logout">Sair</a>
    </nav>
  </aside>

  <!-- CONTEÚDO PRINCIPAL -->
  <main id="conteudo" class="admin-main">
    <?php include __DIR__ . '/dashboard.php'; ?>
  </main>

  <script src="../../JS/painel.js"></script>
  <script src="../../JS/administrador.js?v=6"></script>
</body>

</html>