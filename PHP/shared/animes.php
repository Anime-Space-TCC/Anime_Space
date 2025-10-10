<?php
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

// Busca todos os generos disponiveis
function buscarAnimePorId(PDO $pdo, int $id): ?array {
    // Busca os dados básicos do anime
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nome,
            capa,
            sinopse,
            ano,
            nota
        FROM animes
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $anime = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anime) return null;

    // Busca os gêneros como array
    $stmt2 = $pdo->prepare("
        SELECT g.id, g.nome 
        FROM generos g
        JOIN anime_generos ag ON g.id = ag.genero_id
        WHERE ag.anime_id = ?
    ");
    $stmt2->execute([$id]);
    $anime['generos'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    return $anime;
}

// Busca os animes que estrearam na temporada atual
function buscarEstreiasTemporada(PDO $pdo): array {
    $sql = "
        SELECT 
            t.id AS temporada_id,
            t.anime_id,
            t.numero AS temporada,
            t.nome AS temporada_nome,
            a.nome AS anime_nome,
            a.capa AS anime_capa,
            e.numero AS numero,
            e.titulo AS titulo,
            e.data_lancamento
        FROM temporadas t
        JOIN animes a ON t.anime_id = a.id
        LEFT JOIN episodios e 
            ON e.anime_id = t.anime_id 
           AND e.temporada = t.numero
           AND e.numero = (
               SELECT MIN(e2.numero)
               FROM episodios e2
               WHERE e2.anime_id = t.anime_id
                 AND e2.temporada = t.numero
           )
        ORDER BY a.nome, t.numero
    ";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca os animes com maior nota
function buscarTopAnimes(PDO $pdo, int $limite = 5): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa, nota, sinopse FROM animes ORDER BY nota DESC LIMIT :limite");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca os lançamentos mais recentes (últimos cadastrados)
function buscarLancamentos(PDO $pdo, int $limite = 9): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa FROM animes ORDER BY id DESC LIMIT :limite");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca animes pelo nome (termo de pesquisa)
function buscarAnimePorNome(PDO $pdo, string $nome): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa, sinopse FROM animes WHERE nome LIKE :nome");
    $stmt->execute(['nome' => "%$nome%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

