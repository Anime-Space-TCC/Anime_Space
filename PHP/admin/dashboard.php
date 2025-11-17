<section class="painel-conteudo">

  <!-- ===== Cards gerais ===== -->
  <div class="admin-stats" id="estatisticas-gerais">
    <div class="stat-card">Usuários: <span id="usuarios-geral">Carregando...</span></div>
    <div class="stat-card">Animes: <span id="animes-geral">Carregando...</span></div>
    <div class="stat-card">Episódios: <span id="episodios-geral">Carregando...</span></div>
    <div class="stat-card">Acessos: <span id="acessos-geral">Carregando...</span></div>
  </div>

  <!-- ===== Gráficos ===== -->
  <div class="charts">
    <div class="chart-card">
      <h3>Idade dos Usuários</h3>
      <canvas id="chartIdade" width="100%" height="100%"></canvas>
    </div>

    <div class="chart-card">
      <h3>Nacionalidade dos Usuários</h3>
      <canvas id="chartNacionalidade" width="100%" height="100%"></canvas>
    </div>

    <div class="chart-card">
      <h3>Acessos por Dia (últimos 7 dias)</h3>
      <canvas id="chartAcessos" width="100%" height="100%"></canvas>
    </div>

    <div class="chart-card">
      <h3>Top Animes Mais Assistidos</h3>
      <canvas id="chartTopAnimes" width="100%" height="100%"></canvas>
    </div>
  </div>
</section>
