    // Reações
    document.querySelectorAll('.reacao-btn').forEach(button => {
      button.addEventListener('click', () => {
        const card = button.closest('.card');
        if (!card) return;

        const episodioId = card.dataset.episodioId;
        const reacao = button.dataset.reacao;

        if (!episodioId || !['like', 'dislike'].includes(reacao)) return;

        fetch('../../PHP/shared/reagir.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `episodio_id=${encodeURIComponent(episodioId)}&reacao=${encodeURIComponent(reacao)}`
          })
          .then(res => res.json())
          .then(data => {
            if (data.sucesso) {
              const likeSpan = card.querySelector('.contador-like');
              const dislikeSpan = card.querySelector('.contador-dislike');
              if (likeSpan) likeSpan.textContent = data.likes ?? 0;
              if (dislikeSpan) dislikeSpan.textContent = data.dislikes ?? 0;

              card.querySelectorAll('.reacao-btn').forEach(btn => btn.classList.remove('ativo'));
              button.classList.add('ativo');
            } else {
              alert(data.erro || 'Erro ao processar reação.');
            }
          })
          .catch(() => alert('Erro ao enviar reação.'));
      });
    });