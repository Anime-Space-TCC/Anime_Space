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

<script src="../../JS/promocao.js"></script>
