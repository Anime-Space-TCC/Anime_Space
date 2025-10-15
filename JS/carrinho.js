// Logica do Carrinho
const botoes = document.querySelectorAll('.btn-adicionar');
const totalCarrinhoEl = document.getElementById('totalCarrinho');

botoes.forEach(btn => {
    btn.addEventListener('click', () => {
        const produtoId = btn.getAttribute('data-id');

        fetch('../../PHP/shared/carrinho.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `acao=adicionar&id=${produtoId}`
        })
        .then(res => {
            console.log('Status da resposta:', res.status);
            return res.text();
        })
        .then(data => {
            console.log('Resposta do servidor:', data);
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