<?php
require_once __DIR__ . '/conexao.php';

// Salva o resultado do quiz de um usuário e retorna o número de acertos.
// Evita respostas duplicadas para o mesmo usuário e anime.
function salvarResultadoQuiz($userId, $anime_id, $respostas) {
    global $pdo;

    // Valida o array de respostas
    if (!is_array($respostas) || empty($respostas)) {
        return ['acertos' => 0, 'total' => 0, 'status' => 'invalido', 'msg' => 'Respostas inválidas'];
    }

    // Verifica se o usuário já respondeu esse quiz
    $stmtCheck = $pdo->prepare("SELECT 1 FROM quiz_respostas WHERE user_id = ? AND anime_id = ? LIMIT 1");
    $stmtCheck->execute([$userId, $anime_id]);
    if ($stmtCheck->fetch()) {
        return ['acertos' => 0, 'total' => count($respostas), 'status' => 'duplicado', 'msg' => 'Quiz já respondido'];
    }

    $acertos = 0;
    $total = count($respostas);

    try {
        $pdo->beginTransaction();

        foreach ($respostas as $resp) {
            if (!isset($resp['pergunta_id'], $resp['resposta_usuario'], $resp['correta'])) {
                continue; // ignora respostas incompletas
            }

            if ($resp['correta']) {
                $acertos++;
            }

            $stmt = $pdo->prepare("
                INSERT INTO quiz_respostas (user_id, pergunta_id, resposta_usuario, correta, anime_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $resp['pergunta_id'],
                $resp['resposta_usuario'],
                $resp['correta'],
                $anime_id
            ]);
        }

        $pdo->commit();
        return ['acertos' => $acertos, 'total' => $total, 'status' => 'salvo', 'msg' => null];

    } catch (Exception $e) {
        $pdo->rollBack();
        return ['acertos' => 0, 'total' => 0, 'status' => 'erro', 'msg' => $e->getMessage()];
    }
}

// Busca quizzes por anime e temporada.
function buscarQuizPorAnimeETemporada(PDO $pdo, int $anime_id, ?int $temporada = null) {
    $sql = "SELECT * FROM quizzes WHERE anime_id = :anime_id";
    if ($temporada !== null) {
        $sql .= " AND temporada = :temporada";
    }
    $sql .= " ORDER BY id ASC"; // mantém ordem consistente

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':anime_id', $anime_id, PDO::PARAM_INT);
    if ($temporada !== null) {
        $stmt->bindValue(':temporada', $temporada, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
