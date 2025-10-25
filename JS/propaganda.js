// ================================
// Script Universal de Propagandas
// ================================

// Detecta a página atual
const currentPage = window.location.pathname;

// Seleciona áreas de anúncios (se existirem)
const lateralAds = document.querySelectorAll('.ads-lateral .ad-item img');
const letreiro = document.getElementById('letreiro'); // usado no rodapé animado

// ================================
// Conjuntos de banners
// ================================
const banners = {
  rodape: [
    '../../img/ads/propaganda1.jpg',
    '../../img/ads/propaganda2.jpg',
    '../../img/ads/propaganda3.jpg',
    '../../img/ads/propaganda4.jpg',
    '../../img/ads/propaganda5.jpg',
    '../../img/ads/propaganda6.jpg'
  ],
  laterais: [
    ['../../img/ads/propaganda7.jpg', '../../img/ads/propaganda8.jpg'],
    ['../../img/ads/propaganda9.jpg', '../../img/ads/propaganda10.jpg']
  ]
};

// ================================
// Letreiro do rodapé (animado)
// ================================
function carregarLetreiro(imagens) {
  if (!letreiro) return;

  // Gera dinamicamente os blocos de propaganda
  letreiro.innerHTML = imagens
    .map(src => `<div class="propaganda"><img src="${src}" alt="Anúncio"></div>`)
    .join('');

  // Duplica o conteúdo para efeito de rolagem contínua
  letreiro.innerHTML += letreiro.innerHTML;
}

// ================================
// Rotacionar anúncios laterais
// ================================
function rotateAds(adElements, adGroups, interval = 8000) {
  if (!adElements.length || !adGroups.length) return;
  let index = 0;
  setInterval(() => {
    index = (index + 1) % adGroups.length;
    adElements.forEach((img, i) => {
      if (adGroups[index][i]) img.src = adGroups[index][i];
    });
  }, interval);
}

// ================================
// Execuções automáticas
// ================================

// Rodapé: sempre ativo se o letreiro existir
carregarLetreiro(banners.rodape);

// Laterais: apenas em páginas específicas
if (currentPage.includes('estreias') || currentPage.includes('lancamentos')) {
  rotateAds(lateralAds, banners.laterais);
}
