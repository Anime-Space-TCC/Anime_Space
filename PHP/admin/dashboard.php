<section class="painel-conteudo">
  <h2 class="painel-titulo">Visão geral</h2>

  <div class="admin-stats" id="estatisticas-gerais">
    <div class="stat-card">Usuários: <span id="usuarios-geral">Carregando...</span></div>
    <div class="stat-card">Animes: <span id="animes-geral">Carregando...</span></div>
    <div class="stat-card">Episódios: <span id="episodios-geral">Carregando...</span></div>
    <div class="stat-card">Acessos: <span id="acessos-geral">Carregando...</span></div>
  </div>

  <div class="charts">
    <div class="chart-card">
      <h3>Idade dos Usuários</h3>
      <canvas id="chartIdade" height="130"></canvas>
    </div>

    <div class="chart-card">
      <h3>Nacionalidade dos Usuários</h3>
      <canvas id="chartNacionalidade" height="130"></canvas>
    </div>

    <div class="chart-card">
      <h3>Acessos por dia (últimos 7 dias)</h3>
      <canvas id="chartAcessos" height="130"></canvas>
    </div>
  </div>
</section>

<script>
if (typeof inicializarDashboard === "function") {
  inicializarDashboard();
}
</script>