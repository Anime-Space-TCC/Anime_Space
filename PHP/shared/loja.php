<?php 
// Retorna os últimos episódios lançados com paginação
function getProdutosPaginados(int $porPagina = 14, int $offset = 0): array {
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