<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Teste Gráficos - Chart.js</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart { width: 400px; height: 300px; margin: 20px; }
  </style>
</head>
<body>
  <h2>Teste Chart.js</h2>
  <canvas id="chartIdade" class="chart"></canvas>

  <script>
    // 1️⃣ Busca dados do backend
    fetch('../shared/analises.php')
      .then(res => res.json())
      .then(dados => {
        if (!dados.usuarios_idade || !Array.isArray(dados.usuarios_idade)) {
          console.error("dados.usuarios_idade não existe ou não é array");
          return;
        }

        const ctx = document.getElementById('chartIdade').getContext('2d');

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: dados.usuarios_idade.map(u => u.faixa),
            datasets: [{
              label: 'Usuários',
              data: dados.usuarios_idade.map(u => u.total),
              backgroundColor: '#ff9900'
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { labels: { color: '#ff9900' } },
              title: { display: true, text: 'Idade dos Usuários', color: '#ff9900' }
            },
            scales: {
              x: { ticks: { color: '#ff9900' }, grid: { color: '#222' } },
              y: { ticks: { color: '#ff9900' }, grid: { color: '#222' } }
            }
          }
        });

        console.log("Gráfico Chart.js desenhado com sucesso!");
      })
      .catch(err => console.error("Erro ao buscar dados:", err));
  </script>
</body>
</html>
