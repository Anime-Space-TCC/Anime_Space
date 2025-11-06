<?php

// Retorna os últimos quizzs com paginação
function getQuizzesPaginados(int $porPagina = 10, int $offset = 0): array {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT * FROM quizzes
        ORDER BY titulo ASC
        LIMIT :offset, :porPagina
    ");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}