async function inicializarDashboard() {
  try {
    const resposta = await fetch('../shared/analytic.php');
    const dados = await resposta.json();

    // Atualiza os cards
    document.getElementById('usuarios-geral').textContent = dados.geral.usuarios;
    document.getElementById('animes-geral').textContent = dados.geral.animes;
    document.getElementById('episodios-geral').textContent = dados.geral.episodios;
    document.getElementById('acessos-geral').textContent = dados.geral.acessos;

    // Preenche tabela de acessos recentes
    const tbody = document.querySelector('#tabela-acessos tbody');
    tbody.innerHTML = dados.acessos_recentes.map(a => `
      <tr>
        <td>${a.id}</td>
        <td>${a.usuario}</td>
        <td>${a.pagina}</td>
        <td>${a.origem}</td>
        <td>${a.data_acesso}</td>
      </tr>
    `).join('');

    // Cria gráficos (exemplo simplificado)
    new Chart(document.getElementById('chartIdade'), {
      type: 'bar',
      data: {
        labels: dados.usuarios_idade.map(i => i.faixa),
        datasets: [{
          label: 'Usuários',
          data: dados.usuarios_idade.map(i => i.total)
        }]
      }
    });

    new Chart(document.getElementById('chartNacionalidade'), {
      type: 'pie',
      data: {
        labels: dados.usuarios_nacionalidade.map(n => n.pais),
        datasets: [{
          data: dados.usuarios_nacionalidade.map(n => n.total)
        }]
      }
    });

  } catch (erro) {
    console.error('Erro ao carregar dashboard:', erro);
  }
}
