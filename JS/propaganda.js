// ================================
// Script Universal de Propagandas
// ================================

// Detecta a página atual
const currentPage = window.location.pathname;

// Seleciona áreas de anúncios (se existirem)
const lateralAds = document.querySelectorAll(".ads-lateral .ad-item img");
const letreiro = document.getElementById("letreiro"); 

// ================================
// Conjuntos de banners
// ================================
const banners = {
  rodape: [
    "../../img/ads/propaganda1.jpg",
    "../../img/ads/propaganda2.jpg",
    "../../img/ads/propaganda3.jpg",
    "../../img/ads/propaganda4.jpg",
    "../../img/ads/propaganda5.jpg",
    "../../img/ads/propaganda6.jpg",
  ],
  laterais: [
    ["../../img/ads/propaganda7.jpg", "../../img/ads/propaganda8.jpg"],
    ["../../img/ads/propaganda9.jpg", "../../img/ads/propaganda10.jpg"],
  ],
};

// ================================
// Função para carregar o letreiro
// ================================
function carregarLetreiro(imagens) {
  if (!letreiro) return;

  // Gera dinamicamente os blocos de propaganda
  letreiro.innerHTML = imagens
    .map(
      (src) => `<div class="propaganda"><img src="${src}" alt="Anúncio"></div>`
    )
    .join("");
}

// ================================
// Função iniciar animação do letreiro (vai e volta)
function iniciarLetreiro() {
  if (!letreiro) return;

  // largura total do letreiro
  const largura = letreiro.scrollWidth;

  // define a animação via CSS em pixels, vai e volta suave
  letreiro.style.animation = `vaiVolta ${Math.max(2, largura / 1000)}s ease-in-out infinite alternate`;

  // cria keyframes dinamicamente
  const styleSheet = document.styleSheets[0];
  const keyframes = `
    @keyframes vaiVolta {
      0% { transform: translateX(0); }
      100% { transform: translateX(-${largura - letreiro.parentElement.clientWidth}px); }
    }
  `;

  // remove keyframes antigos se existirem
  for (let i = styleSheet.cssRules.length - 1; i >= 0; i--) {
    if (styleSheet.cssRules[i].name === "vaiVolta") styleSheet.deleteRule(i);
  }

  styleSheet.insertRule(keyframes, styleSheet.cssRules.length);
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

// Rodapé: carrega banners e inicia animação
carregarLetreiro(banners.rodape);
window.addEventListener('load', iniciarLetreiro);

// Laterais: apenas em páginas específicas
if (currentPage.includes("estreias") || currentPage.includes("lancamentos")) {
  rotateAds(lateralAds, banners.laterais);
}
