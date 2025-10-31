<?php
require_once __DIR__ . '/conexao.php';

// =========================
// FUNÇÕES DE RETORNO DE DADOS
// =========================


// Retorna todos os gêneros cadastrados
function getGeneros() {
    global $pdo;
    return $pdo->query("
        SELECT nome 
        FROM generos 
        ORDER BY nome ASC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

// Retorna todos os anos disponíveis
function getAnos() {
    global $pdo;
    return $pdo->query("
        SELECT valor 
        FROM ano 
        ORDER BY valor DESC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

// Retorna todas as linguagens disponíveis nos episódios
function getLinguagens() {
    global $pdo;
    return $pdo->query("
        SELECT DISTINCT linguagem 
        FROM episodios 
        ORDER BY linguagem ASC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

// =========================
// FUNÇÃO DE FILTRAGEM DE ANIMES
// =========================

// Busca os animes aplicando filtros de gênero, ano, linguagem e busca
function getAnimesFiltrados($filtroGenero = '', $filtroAno = '', $filtroLinguagem = '', $busca = '') {
    global $pdo;

    $sql = "
        SELECT DISTINCT a.id, a.nome, a.capa, a.ano, a.nota,
            GROUP_CONCAT(DISTINCT g.nome SEPARATOR ', ') AS generos
        FROM animes a
        LEFT JOIN anime_generos ag ON a.id = ag.anime_id
        LEFT JOIN generos g ON ag.genero_id = g.id
        LEFT JOIN episodios e ON e.anime_id = a.id
        WHERE 1 = 1
    ";

    $params = [];

    if (!empty($filtroGenero)) {
        $sql .= " AND g.nome = :genero";
        $params[':genero'] = $filtroGenero;
    }

    if (!empty($filtroAno)) {
        $sql .= " AND a.ano = :ano";
        $params[':ano'] = $filtroAno;
    }

    if (!empty($filtroLinguagem)) {
        $sql .= " AND e.linguagem = :linguagem";
        $params[':linguagem'] = $filtroLinguagem;
    }

    if (!empty($busca)) {
        $sql .= " AND (a.nome LIKE :busca1 OR g.nome LIKE :busca2)";
        $params[':busca1'] = '%' . $busca . '%';
        $params[':busca2'] = '%' . $busca . '%';
    }

    $sql .= " GROUP BY a.id ORDER BY a.nome ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Retorna os últimos episódios lançados com paginação
function getAnimesPaginados(int $porPagina = 18, int $offset = 0): array {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT * FROM animes
        ORDER BY nome ASC
        LIMIT :offset, :porPagina
    ");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
