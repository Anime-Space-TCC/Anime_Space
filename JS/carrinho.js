// Logica do Carrinho
const botoes = document.querySelectorAll('.btn-adicionar');
const totalCarrinhoEl = document.getElementById('totalCarrinho');

botoes.forEach(btn => {
    btn.addEventListener('click', () => {
        const produtoId = btn.getAttribute('data-id');

        fetch('/ESTEVAO/Anime_Space/PHP/shared/carrinho.php', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=adicionar&id=${produtoId}`
    })
    .then(res => res.text())  // usar text() temporariamente para debug
    .then(data => {
        console.log(data); // veja exatamente o que está retornando
        try {
            const json = JSON.parse(data);
            if(json.sucesso){
                totalCarrinhoEl.textContent = json.totalItens;
                totalCarrinhoEl.classList.add('pulse');
                setTimeout(() => totalCarrinhoEl.classList.remove('pulse'), 500);
            } else {
                alert('Erro ao adicionar produto!');
            }
        } catch(e) {
            console.error('Resposta não é JSON:', e, data);
        }
    });
    });
});