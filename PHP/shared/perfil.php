<?php
require_once 'conexao.php';

// Busca favoritos do usuário
function buscarFavoritos($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa 
        FROM favoritos f 
        JOIN animes a ON f.anime_id = a.id 
        WHERE f.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca histórico recente do usuário
function buscarHistorico($userId, $limite = 10) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT e.id, e.titulo, e.miniatura, h.data_assistido 
        FROM historico h 
        JOIN episodios e ON h.episodio_id = e.id 
        WHERE h.user_id = ? 
        ORDER BY h.data_assistido DESC 
        LIMIT ?");
    $stmt->execute([$userId, $limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca recomendações para o usuário
function buscarRecomendacoes($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa, r.motivo 
        FROM recomendacoes r 
        JOIN animes a ON r.anime_id = a.id 
        WHERE r.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
