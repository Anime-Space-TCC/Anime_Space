<?php
// Retorna os últimos episódios lançados com paginação
function getProdutosPaginados(int $porPagina = 14, int $offset = 0): array
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT * FROM produtos
        ORDER BY id DESC
        LIMIT :offset, :porPagina
    ");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca apenas produtos que têm promoção ativa
function buscarProdutosPromocao()
{
    global $pdo;

    $stmt = $pdo->query("
        SELECT id, nome, descricao, imagem, estoque, preco, preco_promocional
        FROM produtos
        WHERE preco_promocional IS NOT NULL AND preco_promocional > 0
        ORDER BY preco_promocional ASC
        LIMIT 10
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}