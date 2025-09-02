<?php
require_once __DIR__ . '/conexao.php';

/**
 * Salva o resultado do quiz de um usuário e retorna o número de acertos.
 * Evita respostas duplicadas para o mesmo usuário e anime.
 *
 * @param int $userId ID do usuário
 * @param int $anime_id ID do anime
 * @param array $respostas Array com as respostas do usuário
 * @return array ['acertos' => int, 'total' => int, 'status' => string]
 */
function salvarResultadoQuiz($userId, $anime_id, $respostas) {
    global $pdo;

    // Verifica se o usuário já respondeu esse quiz
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM quiz_respostas WHERE user_id = ? AND anime_id = ?");
    $stmtCheck->execute([$userId, $anime_id]);
    if ($stmtCheck->fetchColumn() > 0) {
        return ['acertos' => 0, 'total' => count($respostas), 'status' => 'duplicado'];
    }

    $acertos = 0;
    $total = count($respostas);

    foreach ($respostas as $resp) {
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

    return ['acertos' => $acertos, 'total' => $total, 'status' => 'salvo'];
}
