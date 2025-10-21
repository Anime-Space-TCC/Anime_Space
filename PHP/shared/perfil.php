<?php
require_once 'conexao.php';

// Busca favoritos do usuário
function buscarFavoritos(int $userId): array
{
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

// Registra histórico de visualização
function registrarHistoricoAnime($pdo, $userId, $animeId) {
    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM historico WHERE user_id = ? AND anime_id = ?");
    $stmt->execute([$userId, $animeId]);
    if ($stmt->fetch()) {
        // Atualiza data se já existe
        $stmt = $pdo->prepare("UPDATE historico SET data_acesso = NOW() WHERE user_id = ? AND anime_id = ?");
        $stmt->execute([$userId, $animeId]);
    } else {
        // Insere novo registro
        $stmt = $pdo->prepare("INSERT INTO historico (user_id, anime_id) VALUES (?, ?)");
        $stmt->execute([$userId, $animeId]);
    }
}

// Buscar historico detalhado do usuario
function buscarHistoricoAnimes($pdo, $userId, $limite = 10) {
    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa, ha.data_acesso
        FROM historico ha
        JOIN animes a ON ha.anime_id = a.id
        WHERE ha.user_id = ?
        ORDER BY ha.data_acesso DESC
        LIMIT ?
    ");
    $stmt->execute([$userId, $limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarRecomendacoes(int $userId): array
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT a.id, a.nome, a.capa, r.motivo
        FROM animes a
        JOIN anime_generos ag ON a.id = ag.anime_id
        LEFT JOIN recomendacoes r ON a.id = r.anime_id AND r.user_id = :userId1
        WHERE ag.genero_id IN (
            SELECT ag2.genero_id
            FROM anime_generos ag2
            JOIN (
                SELECT anime_id FROM favoritos WHERE user_id = :userId2
                UNION
                SELECT anime_id FROM avaliacoes WHERE user_id = :userId3
                UNION
                SELECT e.anime_id
                FROM episodios e
                JOIN episodio_reacoes er ON e.id = er.episodio_id
                WHERE er.user_id = :userId4 AND er.reacao = 'like'
                UNION
                SELECT e.anime_id
                FROM episodios e
                JOIN comentarios c ON e.id = c.episodio_id
                WHERE c.user_id = :userId5
            ) AS interacoes ON ag2.anime_id = interacoes.anime_id
        )
        AND a.id NOT IN (
            SELECT anime_id FROM favoritos WHERE user_id = :userId6
        )
        GROUP BY a.id
        ORDER BY COUNT(*) DESC
        LIMIT 3
    ");

    // Execute passando todos os parâmetros
    $stmt->execute([
        'userId1' => $userId,
        'userId2' => $userId,
        'userId3' => $userId,
        'userId4' => $userId,
        'userId5' => $userId,
        'userId6' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

