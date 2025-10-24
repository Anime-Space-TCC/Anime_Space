// Aguarda o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
  inicializarDashboard();
});

async function inicializarDashboard() {
  try {
    const resposta = await fetch('../shared/analises.php');
    if (!resposta.ok) throw new Error('Falha ao buscar dados do servidor');

    const dados = await resposta.json();

    // ===== Atualiza cards =====
    const g = dados.geral || {};
    const setCard = (id, valor) => {
      const el = document.getElementById(id);
      if (el) el.textContent = valor ?? '0';
    };
    setCard('usuarios-geral', g.usuarios);
    setCard('animes-geral', g.animes);
    setCard('episodios-geral', g.episodios);
    setCard('acessos-geral', g.acessos);

    // ===== Função auxiliar para criar gráfico =====
    function criarChart(canvasId, tipo, labels, data, optionsExtra = {}) {
      const canvas = document.getElementById(canvasId);
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      if (ctx.chartInstance) ctx.chartInstance.destroy();
      const chart = new Chart(ctx, {
        type: tipo,
        data: {
          labels,
          datasets: [{
            label: optionsExtra.label || '',
            data,
            backgroundColor: optionsExtra.backgroundColor || '#ff9900',
            borderColor: optionsExtra.borderColor || '#ff9900',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: optionsExtra.showLegend ?? true },
            title: { display: !!optionsExtra.title, text: optionsExtra.title || '' }
          },
          scales: {
            y: { beginAtZero: true, ticks: { color: '#ff9900' } },
            x: { ticks: { color: '#ff9900' } }
          },
          ...optionsExtra.extraOptions
        }
      });
      ctx.chartInstance = chart;
    }

    // ===== Gráficos =====
    if (Array.isArray(dados.usuarios_idade)) {
      criarChart(
        'chartIdade', 'bar',
        dados.usuarios_idade.map(u => u.faixa),
        dados.usuarios_idade.map(u => u.total),
        { title: 'Idade dos Usuários' }
      );
    }

    if (Array.isArray(dados.usuarios_nacionalidade)) {
      criarChart(
        'chartNacionalidade', 'doughnut',
        dados.usuarios_nacionalidade.map(u => u.pais),
        dados.usuarios_nacionalidade.map(u => u.total),
        {
          title: 'Nacionalidade dos Usuários',
          backgroundColor: ['#ff9900','#333','#666','#999','#222','#444']
        }
      );
    }

    if (Array.isArray(dados.acessos_por_dia)) {
      criarChart(
        'chartAcessos', 'line',
        dados.acessos_por_dia.map(a => a.dia),
        dados.acessos_por_dia.map(a => a.total),
        { title: 'Acessos por Dia (últimos 7 dias)', extraOptions: { tension: 0.4 } }
      );
    }

    if (Array.isArray(dados.top_animes)) {
      criarChart(
        'chartTopAnimes', 'bar',
        dados.top_animes.map(a => a.titulo),
        dados.top_animes.map(a => a.total),
        { title: 'Top Animes Mais Assistidos' }
      );
    }

    // ===== Tabela últimos 10 acessos =====
    const tbody = document.querySelector('#tabela-acessos tbody');
    if (tbody && Array.isArray(dados.acessos_recentes)) {
      tbody.innerHTML = '';
      dados.acessos_recentes.forEach(a => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${a.id}</td>
          <td>${a.usuario}</td>
          <td>${(a.pagina || '').split('/').pop() || '-'}</td>
          <td>${(a.origem || '').split('/').pop() || '-'}</td>
          <td>${a.data_acesso}</td>
        `;
        tbody.appendChild(tr);
      });
    }

  } catch (erro) {
    console.error('Erro ao carregar dashboard:', erro);
  }
}
