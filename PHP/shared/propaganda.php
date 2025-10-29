<div class="letreiro-container">
  <div class="letreiro" id="letreiro">
    <div class="propaganda"><img src="../../img/ads/propaganda1.jpg" alt="Anúncio 1"></div>
    <div class="propaganda"><img src="../../img/ads/propaganda2.jpg" alt="Anúncio 2"></div>
    <div class="propaganda"><img src="../../img/ads/propaganda3.jpg" alt="Anúncio 3"></div>
    <div class="propaganda"><img src="../../img/ads/propaganda4.jpg" alt="Anúncio 4"></div>
  </div>
</div>

<script>
const letreiro = document.getElementById('letreiro');
// Duplica o conteúdo para loop contínuo
letreiro.innerHTML += letreiro.innerHTML;
</script>
