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