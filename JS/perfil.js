//  Carrega e atualiza todos os dados do perfil
function carregarPerfil() {
    fetch('../../PHP/shared/perfil_data.php', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => {
            if (!data.sucesso) {
                console.error('Erro ao carregar perfil:', data.erro);
                return;
            }

            // Favoritos
            const favContainer = document.querySelector('.favoritos-section .cards-container');
            if (favContainer) {
                favContainer.innerHTML = '';
                if (data.favoritos.length) {
                    data.favoritos.forEach(f => {
                        const card = document.createElement('a');
                        card.href = `../../PHP/user/episodes.php?id=${f.id}`;
                        card.className = 'card';
                        card.innerHTML = `<img src="../../img/${f.capa}" alt="${f.nome}"><p>${f.nome}</p>`;
                        favContainer.appendChild(card);
                    });
                } else {
                    favContainer.innerHTML = '<p>Nenhum favorito ainda.</p>';
                }
            }

            // Histórico
            const histContainer = document.querySelector('.historico-section .cards-container');
            if (histContainer) {
                histContainer.innerHTML = '';
                if (data.historico.length) {
                    data.historico.forEach(h => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.innerHTML = `<img src="../../img/${h.miniatura}" alt="${h.titulo}">
                                          <p>${h.titulo}</p>
                                          <small>${new Date(h.data_assistido).toLocaleString('pt-BR')}</small>`;
                        histContainer.appendChild(card);
                    });
                } else {
                    histContainer.innerHTML = '<p>Nenhum episódio assistido recentemente.</p>';
                }
            }

            // Recomendações
            const recContainer = document.querySelector('.recomendacoes-section .cards-container');
            if (recContainer) {
                recContainer.innerHTML = '';
                if (data.recomendacoes.length) {
                    data.recomendacoes.forEach(r => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.innerHTML = `<img src="../../img/${r.capa}" alt="${r.nome}">
                                          <p>${r.nome}</p>
                                          ${r.motivo ? `<small>${r.motivo}</small>` : ''}`;
                        recContainer.appendChild(card);
                    });
                } else {
                    recContainer.innerHTML = '<p>Sem recomendações no momento.</p>';
                }
            }

            // XP e Nível
            const levelElem = document.querySelector('.level');
            if (levelElem) levelElem.innerHTML = `Nível: ${data.nivel}<p class="titulo">${data.tituloNivel}</p>`;

            const expFill = document.querySelector('.exp-fill');
            if (expFill) expFill.style.width = data.porcentagem + '%';

            const xpText = document.querySelector('.xp-text');
            if (xpText) xpText.textContent = `${data.xp} / ${data.xpNecessario} XP`;
        })
        .catch(err => console.error('Falha ao carregar perfil:', err));
}

// Inicializa após DOM carregado
document.addEventListener('DOMContentLoaded', function() {
    carregarPerfil();

    // Atualização da foto via AJAX
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            const arquivo = this.files[0];
            if (!arquivo) return;

            const formData = new FormData();
            formData.append('acao', 'foto'); // necessário para seu PHP
            formData.append('foto', arquivo);

            fetch('../../PHP/shared/profile_upload.php', {
                method: 'POST',
                body: formData,
                cache: 'no-store'
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    // Atualiza a imagem do avatar
                    const avatarImg = document.querySelector('.avatar img');
                    if (avatarImg) avatarImg.src = data.novaFoto + '?t=' + new Date().getTime();

                    // Atualiza todos os dados do perfil
                    carregarPerfil();
                } else {
                    console.error('Erro ao atualizar foto:', data.erro);
                    alert(data.erro || 'Erro ao atualizar foto.');
                }
            })
            .catch(err => console.error('Falha na requisição:', err));
        });
    }
});
