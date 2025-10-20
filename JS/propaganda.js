// ================================
// Script universal de propagandas
// ================================

// Detecta a página pela URL
const currentPage = window.location.pathname; // ex: "/HTML/home.html" ou "/PHP/user/estreias.php"

// Seleciona todas as imagens de anúncios
const lateralAds = document.querySelectorAll('.ads-lateral .ad-item img');
const footerAds  = document.querySelectorAll('.ads-rodape .ad-item img');

// Define conjuntos de banners
const banners = {
  rodape: [
    ['../../img/ads/propaganda1.jpg', '../../img/ads/propaganda2.jpg', '../../img/ads/propaganda3.jpg'],
    ['../../img/ads/propaganda4.jpg', '../../img/ads/propaganda5.jpg', '../../img/ads/propaganda6.jpg']
  ],
  laterais: [
    ['../../img/ads/propaganda7.jpg', '../../img/ads/propaganda8.jpg', '../../img/ads/propaganda9.jpg', '../../img/ads/propaganda10.jpg']
  ]
};

// Função para rotacionar banners
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
// Rodapé: sempre ativo se existir
// ================================
rotateAds(footerAds, banners.rodape);

// ================================
// Laterais: apenas em estreias ou lançamentos
// ================================
if (currentPage.includes('estreias') || currentPage.includes('lancamentos')) {
  rotateAds(lateralAds, banners.laterais);
}
