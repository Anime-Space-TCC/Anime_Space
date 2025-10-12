// Remover produto
const removerBotoes = document.querySelectorAll('.btn-remover');
removerBotoes.forEach(btn => {
    btn.addEventListener('click', () => {
        const produtoId = btn.getAttribute('data-id');
        fetch('../shared/carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `acao=remover&id=${produtoId}`
        })
        .then(res => res.json())
        .then(data => { if(data.sucesso) location.reload(); });
    });
});

// Alterar quantidade
const quantidades = document.querySelectorAll('.quantidade');
quantidades.forEach(input => {
    input.addEventListener('change', () => {
        const id = input.dataset.id;
        let novaQtd = parseInt(input.value);
        if(novaQtd < 1) novaQtd = 1;
        input.value = novaQtd;

        fetch('../shared/carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `acao=atualizar&id=${id}&quantidade=${novaQtd}`
        })
        .then(res => res.json())
        .then(data => { if(data.sucesso) location.reload(); });
    });
});