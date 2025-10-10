<?php

// Busca todos os gêneros cadastrados no banco de dados.
function buscarTodosGeneros(PDO $pdo): array {
    $stmt = $pdo->query("SELECT nome, id_destaque FROM generos ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca uma quantidade limitada de gêneros cadastrados no banco de dados.
function buscarGenerosLimit(PDO $pdo, int $limite): array {
    $stmt = $pdo->prepare("SELECT nome, id_destaque FROM generos ORDER BY nome LIMIT :limite");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}