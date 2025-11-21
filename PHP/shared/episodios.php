<?php
require_once __DIR__ . '/conexao.php';


//=========================
//  FUNÇÕES DE EPISÓDIOS
//=========================


// Retorna todos os episódios de um anime com contagem de likes e dislikes
function buscarEpisodiosComReacoes(PDO $pdo, int $animeId): array
{
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

// Busca um episódio específico de um anime (com temporada garantida)
function buscarEpisodioSelecionado(PDO $pdo, int $episodioId, int $animeId): ?array
{
    $stmt = $pdo->prepare("
        SELECT id, anime_id, temporada, numero, titulo, descricao, duracao, data_lancamento, miniatura, video_url, linguagem
        FROM episodios 
        WHERE id = ? AND anime_id = ? 
        LIMIT 1
    ");
    $stmt->execute([$episodioId, $animeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// Organiza uma lista de episódios por temporada
function organizarPorTemporada(array $episodios): array
{
    $temporadas = [];
    foreach ($episodios as $ep) {
        $temporadas[$ep['temporada']][] = $ep;
    }
    return $temporadas;
}

// Filtra episódios por linguagem
function filtrarPorLinguagem(array $episodios, string $linguagem): array
{
    return array_filter($episodios, fn($ep) => strtolower($ep['linguagem']) === strtolower($linguagem));
}

// Retorna os últimos episódios lançados com paginação
function getUltimosEpisodiosPaginados(int $porPagina = 10, int $offset = 0): array
{
    global $pdo;

    $sql = "
        SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        ORDER BY e.data_lancamento DESC
        LIMIT :offset, :porPagina
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca um episódio com informações do anime
function buscarEpisodioComAnime(int $episodioId): ?array
{
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

// Extrai o ID do YouTube de uma URL
function extrairIdYoutube($url)
{
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|embed)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches);
    return $matches[1] ?? null;
}

