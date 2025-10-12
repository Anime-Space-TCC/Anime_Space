// Avaliação de estrelas
    document.querySelectorAll('.avaliacao-estrelas').forEach(container => {
      const animeId = container.dataset.animeId;
      const estrelas = container.querySelectorAll('.estrela');
      const notaBox = container.querySelector('.nota-display');

      const atualizarEstrelas = valor => {
        estrelas.forEach(e => e.classList.toggle('ativa', e.dataset.valor <= valor));
      };

      estrelas.forEach(estrela => {
        estrela.addEventListener('click', e => {
          e.preventDefault();
          const valorEstrela = Number(estrela.dataset.valor);
          const nota = valorEstrela * 2;

          fetch('../shared/avaliar.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `anime_id=${encodeURIComponent(animeId)}&avaliacao=${nota}`,
              credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
              if (data.sucesso) {
                atualizarEstrelas(valorEstrela);
                notaBox.textContent = `Nota: ${data.nota}/10`;
              } else {
                alert(data.erro || 'Erro ao registrar avaliação.');
              }
            })
            .catch(() => alert('Erro ao enviar avaliação.'));
        });
      });
    });