<?php
// /PHP/shared/animes.php

require_once __DIR__ . '/auth.php';

// ==============================
// Inicialização de sessão
// ==============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================
// Funções relacionadas a Animes
// ==============================

/**
 * Busca um anime pelo ID
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|null
 */
function buscarAnimePorId(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT nome, capa, sinopse FROM animes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Busca os animes que estrearam na temporada atual
 *
 * @param PDO $pdo
 * @return array
 */
function buscarEstreiasTemporada(PDO $pdo) {
    $sql = "
        SELECT t.id AS temporada_id, t.anime_id, t.numero AS temporada, t.nome AS temporada_nome,
               a.nome AS anime_nome, a.capa AS anime_capa,
               e.numero AS numero, e.titulo AS titulo, e.data_lancamento
        FROM temporadas t
        JOIN animes a ON t.anime_id = a.id
        LEFT JOIN episodios e ON e.anime_id = t.anime_id AND e.temporada = t.numero
        ORDER BY a.nome, t.numero, e.numero
    ";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca os animes com maior nota
 *
 * @param PDO $pdo
 * @param int $limite
 * @return array
 */
function buscarTopAnimes(PDO $pdo, int $limite = 5): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa, nota, descricao FROM animes ORDER BY nota DESC LIMIT ?");
    $stmt->execute([$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca os lançamentos mais recentes (últimos cadastrados)
 *
 * @param PDO $pdo
 * @param int $limite
 * @return array
 */
function buscarLancamentos(PDO $pdo, int $limite = 20): array {
    // Ordena por ID desc para evitar depender de coluna específica de data.
    $stmt = $pdo->prepare("SELECT id, nome, capa FROM animes ORDER BY id DESC LIMIT ?");
    $stmt->bindValue(1, $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
