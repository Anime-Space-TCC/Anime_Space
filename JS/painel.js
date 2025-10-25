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