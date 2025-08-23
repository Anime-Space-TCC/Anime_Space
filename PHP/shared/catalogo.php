<?php
require_once __DIR__ . '/conexao.php';

/**
 * =========================
 * FUNÇÕES DE RETORNO DE DADOS
 * =========================
 */

/**
 * Retorna todos os gêneros cadastrados
 *
 * @return array
 */
function getGeneros() {
    global $pdo;
    return $pdo->query("
        SELECT nome 
        FROM generos 
        ORDER BY nome ASC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Retorna todos os anos disponíveis
 *
 * @return array
 */
function getAnos() {
    global $pdo;
    return $pdo->query("
        SELECT valor 
        FROM ano 
        ORDER BY valor DESC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Retorna todas as linguagens disponíveis nos episódios
 *
 * @return array
 */
function getLinguagens() {
    global $pdo;
    return $pdo->query("
        SELECT DISTINCT linguagem 
        FROM episodios 
        ORDER BY linguagem ASC
    ")->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * =========================
 * FUNÇÃO DE FILTRAGEM DE ANIMES
 * =========================
 */

/**
 * Busca os animes aplicando filtros de gênero, ano, linguagem e busca
 *
 * @param string $filtroGenero
 * @param string $filtroAno
 * @param string $filtroLinguagem
 * @param string $busca
 * @return array
 */
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
