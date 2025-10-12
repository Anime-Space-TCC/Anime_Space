    // Toggle sinopse
    function toggleSinopse() {
      const sinopseContainer = document.getElementById('sinopse-container');
      const btn = document.querySelector('header .btn-info');
      if (sinopseContainer && btn) {
        sinopseContainer.classList.toggle('active');
        btn.textContent = sinopseContainer.classList.contains('active') ? '▲' : '▼';
      }
    }

    // Toggle descrição
    function toggleDescricao(btn) {
      const card = btn.closest('.card');
      if (!card) return;

      const descricao = card.querySelector('.episodio-descricao');
      if (!descricao) return;

      descricao.classList.toggle('hidden');
      btn.textContent = descricao.classList.contains('hidden') ? '▼' : '▲';
    }