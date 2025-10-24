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

  <script>
    // Inicializa imediatamente ao carregar a página
    window.addEventListener('DOMContentLoaded', () => {
      if (typeof inicializarDashboard === 'function') {
        inicializarDashboard();
      }
    });
    // Quando carrega a página inicialmente
    document.addEventListener('DOMContentLoaded', () => {
      const dashboard = document.getElementById('conteudo').querySelector('.painel-conteudo');
      if (dashboard && typeof inicializarDashboard === 'function') {
        inicializarDashboard();
      }
    });

    // Quando troca de página via SPA
    document.querySelectorAll('.sidebar-menu a[data-page]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const page = link.getAttribute('data-page');

        document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
        link.classList.add('active');

        fetch(page)
          .then(res => res.ok ? res.text() : Promise.reject('Erro ao carregar ' + page))
          .then(html => {
            document.getElementById('conteudo').innerHTML = html;
            // Só chama depois de inserir o conteúdo
            if (typeof inicializarDashboard === 'function') inicializarDashboard();
          })
          .catch(err => {
            console.error(err);
            document.getElementById('conteudo').innerHTML = `<p>Falha ao carregar ${page}</p>`;
          });
      });
    });
  </script>
  <script src="../../JS/administrador.js?v=6"></script>
</body>

</html>