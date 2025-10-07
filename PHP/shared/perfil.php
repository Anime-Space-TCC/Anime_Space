<?php
require_once 'conexao.php';

// Busca favoritos do usuário
function buscarFavoritos(int $userId): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa 
        FROM favoritos f 
        JOIN animes a ON f.anime_id = a.id 
        WHERE f.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca histórico recente do usuário
function buscarHistorico(int $userId, int $limite = 10): array {
    global $pdo;
    $limite = max(1, $limite); // garante mínimo 1
    $stmt = $pdo->prepare("
        SELECT e.id, e.titulo, e.miniatura, h.data_assistido 
        FROM historico h 
        JOIN episodios e ON h.episodio_id = e.id 
        WHERE h.user_id = ? 
        ORDER BY h.data_assistido DESC 
        LIMIT $limite
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca recomendações para o usuário
function buscarRecomendacoes(int $userId): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa, r.motivo 
        FROM recomendacoes r 
        JOIN animes a ON r.anime_id = a.id 
        WHERE r.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
