//Carrosel
      document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.carrossel-slide');
        if (!slides || slides.length === 0) return; // nada a fazer

        let index = 0;
        const intervaloMs = 4000;
        let timer = null;

        function ativarSlide(novoIndex) {
          slides.forEach((s, i) => {
            if (i === novoIndex) s.classList.add('ativo');
            else s.classList.remove('ativo');
          });
          index = novoIndex;
        }

        function proximo() {
          const next = (index + 1) % slides.length;
          ativarSlide(next);
        }

        function anterior() {
          const prev = (index - 1 + slides.length) % slides.length;
          ativarSlide(prev);
        }

        // inicia automático
        timer = setInterval(proximo, intervaloMs);

        // pausa ao passar o mouse no carrossel
        const container = document.querySelector('.carrossel-noticias') || document.querySelector('.carrossel-slides');
        if (container) {
          container.addEventListener('mouseenter', () => {
            if (timer) { clearInterval(timer); timer = null; }
          });
          container.addEventListener('mouseleave', () => {
            if (!timer) timer = setInterval(proximo, intervaloMs);
          });
        }

        // Garantir que o primeiro slide esteja ativo (se nenhum estiver)
        const existeAtivo = Array.from(slides).some(s => s.classList.contains('ativo'));
        if (!existeAtivo) ativarSlide(0);
      });