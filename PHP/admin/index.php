<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

// Verifica se o usuário é admin
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
  <link rel="stylesheet" href="../../CSS/style.css?v=5" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin">

  <!-- MENU LATERAL FIXO -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <img src="../../img/slogan3.png" alt="Logo" class="sidebar-logo">
      <h2>Painel Admin</h2>
    </div>

    <nav class="sidebar-menu">
      <a href="#" data-page="../admin/dashboard.php" class="active">📊 Dashboard</a>
      <a href="#" data-page="../admin/analytics.php">📈 Análises</a>
      <a href="#" data-page="../admin/CRUDs/index.php">⚙️ CRUDs</a>
      <a href="../shared/logout.php" class="logout">🚪 Sair</a>
    </nav>
  </aside>

  <!-- CONTEÚDO PRINCIPAL -->
  <main id="conteudo" class="admin-main">
    <?php include '../admin/dashboard.php'; ?>
  </main>

  <script>
  // === Troca de conteúdo sem recarregar a página ===
  document.querySelectorAll('.sidebar-menu a[data-page]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const page = e.target.getAttribute('data-page');

      // Remove destaque do menu anterior
      document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
      e.target.classList.add('active');

      // Carrega a nova página dentro do <main>
      fetch(page)
        .then(res => {
          if (!res.ok) throw new Error('Erro ao carregar página: ' + page);
          return res.text();
        })
        .then(html => document.getElementById('conteudo').innerHTML = html)
        .catch(err => {
          console.error(err);
          document.getElementById('conteudo').innerHTML = `<p>Erro ao carregar ${page}</p>`;
        });
    });
  });
  </script>

  <script src="../../JS/administrador.js"></script>
</body>
</html>
