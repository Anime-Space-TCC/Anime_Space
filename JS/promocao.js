// Carrossel Promoções
let slideIndex = 0;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.querySelectorAll(".slide");
    if(slides.length === 0) return;
    if (n >= slides.length) {slideIndex = 0;}
    if (n < 0) {slideIndex = slides.length - 1;}
    slides.forEach(slide => slide.style.display = "none");
    slides[slideIndex].style.display = "block";
}

// Adicionar produtos do slide ao carrinho
document.querySelectorAll('.btn-adicionar-slide').forEach(btn => {
    btn.addEventListener('click', () => {
        const produtoId = btn.getAttribute('data-id');
        fetch('carrinho.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `acao=adicionar&id=${produtoId}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.sucesso){
                alert(`Produto adicionado! Total no carrinho: ${data.totalItens}`);
                const totalCarrinhoEl = document.getElementById('totalCarrinho');
                if(totalCarrinhoEl) totalCarrinhoEl.textContent = data.totalItens;
            } else {
                alert('Erro ao adicionar produto!');
            }
        });
    });
});