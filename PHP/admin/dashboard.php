<?php
session_start();

// Ajuste do path para a conexão (admin -> ../shared)
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
  <link rel="stylesheet" href="../../CSS/style.css?v=4" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin">

  <div class="admin-links">
    <h1>Painel de Controle</h1>
    <nav>
      <a href="../user/index.php">🏠 Home</a>
      <a href="../shared/logout.php" class="admin-btn">🚪 Sair</a>
    </nav>
  </div>

  <main class="admin-dashboard">
    <h2>Visão geral</h2>

    <div class="admin-stats" id="estatisticas-gerais">
      <div class="stat-card">Usuários: <span id="usuarios-geral">Carregando...</span></div>
      <div class="stat-card">Animes: <span id="animes-geral">Carregando...</span></div>
      <div class="stat-card">Episódios: <span id="episodios-geral">Carregando...</span></div>
      <div class="stat-card">Acessos: <span id="acessos-geral">Carregando...</span></div>
    </div>

    <div class="charts">
      <div class="chart-card">
        <h3>Crescimento de Usuários (ult. 6 meses)</h3>
        <canvas id="chartUsuarios" height="130"></canvas>
      </div>

      <div class="chart-card">
        <h3>Acessos por dia (últimos 7 dias)</h3>
        <canvas id="chartAcessos" height="130"></canvas>
      </div>
    </div>

    <section style="margin-top:18px;">
      <h3>Últimos acessos</h3>
      <div class="small">Os 10 acessos mais recentes registrados na tabela <code>acessos</code>.</div>
      <table class="admin-table" id="tabela-acessos" aria-live="polite">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>IP</th>
            <th>Página</th>
            <th>Origem</th>
            <th>Tipo</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="7">Carregando...</td></tr>
        </tbody>
      </table>
    </section>
    <br>
    <h3>Gerenciar CRUDs</h3>
    <div class="admin-cards">
      <div class="admin-card">
        <img src="../../img/slogan3.png" alt="Animes" style="width:70px;height:70px;object-fit:contain">
        <h4>Animes</h4>
        <a href="../../PHP/admin/CRUDs/animes/admin_animes.php" class="admin-btn">📂 Acessar</a>
      </div>
      <div class="admin-card">
        <img src="../../img/slogan3.png" alt="Temporadas" style="width:70px;height:70px;object-fit:contain">
        <h4>Temporadas</h4>
        <a href="../../PHP/admin/CRUDs/temporadas/admin_temporadas.php" class="admin-btn">📂 Acessar</a>
      </div>
      <div class="admin-card">
        <img src="../../img/slogan3.png" alt="Episódios" style="width:70px;height:70px;object-fit:contain">
        <h4>Episódios</h4>
        <a href="../../PHP/admin/CRUDs/episodes/admin_episodes.php" class="admin-btn">📂 Acessar</a>
      </div>
      <div class="admin-card">
        <img src="../../img/slogan3.png" alt="Produtos" style="width:70px;height:70px;object-fit:contain">
        <h4>Produtos</h4>
        <a href="../../PHP/admin/CRUDs/produtos/admin_produto.php" class="admin-btn">📂 Acessar</a>
      </div>
      <div class="admin-card">
        <img src="../../img/slogan3.png" alt="Noticias" style="width:70px;height:70px;object-fit:contain">
        <h4>Noticias</h4>
        <a href="../../PHP/admin/CRUDs/noticias/admin_noticias.php" class="admin-btn">📂 Acessar</a>
      </div>
    </div>
  </main>

<script>
async function carregarAnalytics() {
  try {
    const res = await fetch('../../PHP/admin/analytics.php', { credentials: 'same-origin' });
    if (!res.ok) throw new Error('Falha ao buscar analytics: ' + res.status);
    const data = await res.json();

    // ===== Cards de visão geral =====
    const statWrap = document.getElementById('estatisticas-gerais');
    statWrap.innerHTML = `
      <div class="stat-card">👥 Usuários<br><strong>${data.geral.usuarios}</strong></div>
      <div class="stat-card">🎬 Animes<br><strong>${data.geral.animes}</strong></div>
      <div class="stat-card">📺 Episódios<br><strong>${data.geral.episodios}</strong></div>
      <div class="stat-card">🌍 Acessos<br><strong>${data.geral.acessos}</strong></div>
    `;

    // ===== Gráfico usuários por mês =====
    new Chart(document.getElementById('chartUsuarios'), {
      type: 'line',
      data: {
        labels: data.usuarios_por_mes.map(x => x.mes),
        datasets: [{
          label: 'Novos usuários',
          data: data.usuarios_por_mes.map(x => x.total),
          borderColor: 'blue',
          fill: false
        }]
      }
    });

    // ===== Gráfico acessos por dia =====
    new Chart(document.getElementById('chartAcessos'), {
      type: 'bar',
      data: {
        labels: data.acessos_por_dia.map(x => x.dia),
        datasets: [{
          label: 'Acessos',
          data: data.acessos_por_dia.map(x => x.total),
          backgroundColor: 'green'
        }]
      }
    });

    // ===== Últimos acessos =====
    const tbody = document.querySelector('#tabela-acessos tbody');
    tbody.innerHTML = '';
    if(data.acessos_recentes.length) {
      data.acessos_recentes.forEach(a => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${a.id}</td>
          <td>${a.usuario || 'Visitante'}</td>
          <td>${a.ip}</td>
          <td>${a.pagina}</td>
          <td>${a.origem}</td>
          <td>${a.tipo}</td>
          <td>${new Date(a.data_acesso).toLocaleString()}</td>
        `;
        tbody.appendChild(tr);
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="7">Nenhum acesso registrado.</td></tr>';
    }

  } catch(err) {
    console.error(err);
    alert('Erro ao carregar dados: ' + err.message);
  }
}

carregarAnalytics();
</script>

</body>
</html>
