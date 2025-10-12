// Seleção de pagamento (apenas visual)
const opcoes = document.querySelectorAll('.pagamento-opcao');
opcoes.forEach(op => {
    op.addEventListener('click', () => {
        opcoes.forEach(o => o.style.boxShadow = '');
        op.style.boxShadow = '0 0 30px #ff9f00';
        alert(`Você selecionou ${op.dataset.metodo.toUpperCase()}`);
    });
});