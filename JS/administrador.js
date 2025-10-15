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

    // ===== Gráfico de Idade (donut) =====
    new Chart(document.getElementById('chartIdade'), {
      type: 'doughnut',
      data: {
        labels: data.usuarios_idade.map(x => x.faixa),
        datasets: [{
          data: data.usuarios_idade.map(x => x.total),
          backgroundColor: ['#000000', '#ff9f00', '#000000', '#ff9f00']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
      }
    });

    // ===== Gráfico de Nacionalidade (pizza) =====
    new Chart(document.getElementById('chartNacionalidade'), {
      type: 'pie',
      data: {
        labels: data.usuarios_nacionalidade.map(x => x.pais),
        datasets: [{
          data: data.usuarios_nacionalidade.map(x => x.total),
          backgroundColor: ['#000000', '#ff9f00', '#000000', '#ff9f00']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
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
          backgroundColor: '#ff9f00'
        }]
      }
    });

    /// ===== Últimos acessos =====
    const tbody = document.querySelector('#tabela-acessos tbody');
    tbody.innerHTML = '';

    if(data.acessos_recentes.length) {
      data.acessos_recentes.forEach(a => {
        // Encurta página e origem (apenas o nome do arquivo)
        const paginaCurta = a.pagina ? a.pagina.split('/').pop() : '-';
        const origemCurta = a.origem ? a.origem.split('/').pop() : '-';

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${a.id}</td>
          <td>${a.usuario || 'Visitante'}</td>
          <td title="${a.pagina}">${paginaCurta}</td>
          <td title="${a.origem}">${origemCurta}</td>
          <td>${new Date(a.data_acesso).toLocaleString()}</td>
        `;
        tbody.appendChild(tr);
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="5">Nenhum acesso registrado.</td></tr>';
    }

  } catch(err) {
    console.error(err);
    alert('Erro ao carregar dados: ' + err.message);
  }
}

carregarAnalytics();