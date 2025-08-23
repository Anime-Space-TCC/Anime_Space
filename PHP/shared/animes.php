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
function buscarEstreiasTemporada(PDO $pdo): array {
    $sql = "
        SELECT e.*, a.nome AS anime_nome, a.capa AS anime_capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        WHERE e.numero = 1
          AND e.temporada = (
              SELECT MAX(e2.temporada)
              FROM episodios e2
              WHERE e2.anime_id = e.anime_id
          )
        ORDER BY e.data_lancamento DESC
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
