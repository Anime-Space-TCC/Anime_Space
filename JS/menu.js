// Menu Lateral
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-toggle');
    const menu = document.getElementById('menuLateral');

    if (menuBtn && menu) {
        menuBtn.addEventListener('click', () => menu.classList.toggle('open'));
    }
});
// Busca Expandida
document.addEventListener('DOMContentLoaded', () => {
  const buscaBtn = document.getElementById('buscaBtn');
  const buscaContainer = document.querySelector('.busca-container');
  const inputBusca = buscaContainer?.querySelector('input[name="busca"]');

  if (buscaBtn && buscaContainer && inputBusca) {
    buscaBtn.addEventListener('click', () => {
      buscaContainer.classList.toggle('active');

      if (buscaContainer.classList.contains('active')) {
        inputBusca.focus();
      } else {
        inputBusca.value = '';
      }
    });
  }
});
// BOTÃO DE NOTIFICAÇÕES
const btnToggle = document.getElementById('btnToggle');
const caixa = document.getElementById('caixaNotificacoes');

btnToggle.addEventListener('click', () => {
  // Alterna classe ativo no botão
  btnToggle.classList.toggle('ativo');
  // Alterna visibilidade da caixa
  caixa.classList.toggle('ativo');
});

// TABS DENTRO DA CAIXA
const tabs = document.querySelectorAll('.tab-btn');
const conteudos = document.querySelectorAll('.tab-conteudo');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    // Remove active de todos
    tabs.forEach(t => t.classList.remove('active'));
    conteudos.forEach(c => c.classList.remove('active'));

    // Adiciona active ao clicado
    tab.classList.add('active');
    document.getElementById(tab.dataset.tab).classList.add('active');
  });
});

// FECHAR A CAIXA AO CLICAR FORA
document.addEventListener('click', (e) => {
  if (!btnToggle.contains(e.target) && !caixa.contains(e.target)) {
    btnToggle.classList.remove('ativo');
    caixa.classList.remove('ativo');
  }
});
