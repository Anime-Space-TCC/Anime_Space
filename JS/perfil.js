//  Carrega e atualiza todos os dados do perfil 
function carregarPerfil() {
    fetch('../../PHP/shared/perfil_data.php', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => {
            if (!data.sucesso) {
                console.error('Erro ao carregar perfil:', data.erro);
                return;
            }

            //  Favoritos 
            const favContainer = document.querySelector('.favoritos-section .cards-container');
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

            //  Histórico 
            const histContainer = document.querySelector('.historico-section .cards-container');
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

            //  Recomendações 
            const recContainer = document.querySelector('.recomendacoes-section .cards-container');
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

            //  XP e Nível
            document.querySelector('.level').innerHTML = `Nível: ${data.nivel}<p class="titulo">${data.tituloNivel}</p>`;
            const expFill = document.querySelector('.exp-fill');
            expFill.style.width = data.porcentagem + '%';
            document.querySelector('.xp-text').textContent = `${data.xp} / ${data.xpNecessario} XP`;
        })
        .catch(err => console.error('Falha ao carregar perfil:', err));
}

//  Inicializa 
carregarPerfil();

//  Atualização da foto via AJAX 
document.getElementById('foto').addEventListener('change', function() {
    const arquivo = this.files[0];
    if (!arquivo) return;

    const formData = new FormData();
    formData.append('foto', arquivo);

    fetch('../../PHP/shared/profile_upload.php', {
        method: 'POST',
        body: formData,
        cache: 'no-store' // evita problemas de cache
    })
    .then(res => res.json())
    .then(data => {
        if(data.sucesso){
            // Atualiza a imagem do avatar sem precisar recarregar
            const avatarImg = document.querySelector('.avatar img');
            avatarImg.src = data.novaFoto + '?t=' + new Date().getTime();

            // Atualiza todos os dados do perfil (XP, nível, favoritos, etc.)
            carregarPerfil();
        } else {
            console.error('Erro ao atualizar foto:', data.erro);
            alert(data.erro || 'Erro ao atualizar foto.');
        }
    })
    .catch(err => console.error('Falha na requisição:', err));
});

