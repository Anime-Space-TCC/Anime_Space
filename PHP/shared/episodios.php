<?php
// /PHP/shared/episodios.php

require_once __DIR__ . '/conexao.php';

/**
 * =========================
 * FUNÇÕES DE EPISÓDIOS
 * =========================
 */

/**
 * Retorna todos os episódios de um anime com contagem de likes e dislikes
 *
 * @param PDO $pdo
 * @param int $animeId
 * @return array
 */
function buscarEpisodiosComReacoes(PDO $pdo, int $animeId): array {
    $stmt = $pdo->prepare("
        SELECT e.*, date_format(e.data_lancamento, '%d/%m/%Y') as data_lancamento,
            COALESCE(SUM(CASE WHEN r.reacao = 'like' THEN 1 ELSE 0 END), 0) AS likes,
            COALESCE(SUM(CASE WHEN r.reacao = 'dislike' THEN 1 ELSE 0 END), 0) AS dislikes
        FROM episodios e
        LEFT JOIN episodio_reacoes r ON e.id = r.episodio_id
        WHERE e.anime_id = ?
        GROUP BY e.id
        ORDER BY e.temporada ASC, e.numero ASC
    ");
    $stmt->execute([$animeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca um episódio específico de um anime (com temporada garantida)
 *
 * @param PDO $pdo
 * @param int $episodioId
 * @param int $animeId
 * @return array|null
 */
function buscarEpisodioSelecionado(PDO $pdo, int $episodioId, int $animeId): ?array {
    $stmt = $pdo->prepare("
        SELECT id, anime_id, temporada, numero, titulo, descricao, duracao, data_lancamento, miniatura, video_url, linguagem
        FROM episodios 
        WHERE id = ? AND anime_id = ? 
        LIMIT 1
    ");
    $stmt->execute([$episodioId, $animeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Organiza uma lista de episódios por temporada
 *
 * @param array $episodios
 * @return array
 */
function organizarPorTemporada(array $episodios): array {
    $temporadas = [];
    foreach ($episodios as $ep) {
        $temporadas[$ep['temporada']][] = $ep;
    }
    return $temporadas;
}

/**
 * Filtra episódios por linguagem
 *
 * @param array $episodios
 * @param string $linguagem
 * @return array
 */
function filtrarPorLinguagem(array $episodios, string $linguagem): array {
    return array_filter($episodios, fn($ep) => strtolower($ep['linguagem']) === strtolower($linguagem));
}

/**
 * Retorna os últimos episódios lançados
 *
 * @param int $limite Quantidade de episódios (padrão: 20)
 * @return array
 */
function getUltimosEpisodios(int $limite = 20): array {
    global $pdo;

    $sql = "
        SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        ORDER BY e.data_lancamento DESC
        LIMIT :limite
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca um episódio com informações do anime
 *
 * @param int $episodioId
 * @return array|null
 */
function buscarEpisodioComAnime(int $episodioId): ?array {
    global $pdo;

    $sql = "
        SELECT e.*, a.nome AS anime_nome, a.capa AS anime_capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        WHERE e.id = ?
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$episodioId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Extrai o ID do YouTube de uma URL
 *
 * @param string $url
 * @return string|null
 */
function extrairIdYoutube($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|embed)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches);
    return $matches[1] ?? null;
}
