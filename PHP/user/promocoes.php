<?php
// promoções para o slideshow
$stmt = $pdo->query("SELECT * FROM produtos WHERE ativo = 1 AND preco < 100 ORDER BY data_criacao DESC");
$promocoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="promocoes-slideshow">
    <?php foreach ($promocoes as $index => $produto): ?>
        <div class="slide" <?= $index === 0 ? 'style="display:block;"' : '' ?>>
            <img src="../../img/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <div class="slide-info">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                <?php if($produto['estoque'] > 0): ?>
                    <button class="btn-adicionar-slide" data-id="<?= $produto['id'] ?>">Adicionar ao Carrinho</button>
                <?php else: ?>
                    <button disabled>Indisponível</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

<script>
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
</script>
