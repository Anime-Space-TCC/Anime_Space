<?php

// Busca todos os gêneros cadastrados no banco de dados.
function buscarTodosGeneros(PDO $pdo): array {
    $stmt = $pdo->query("SELECT nome, id_destaque FROM generos ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
