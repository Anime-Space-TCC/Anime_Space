<?php
require_once __DIR__ . '/conexao.php';

/**
 * Busca todas as perguntas de um quiz de um determinado episódio.
 *
 * @param int $episodio_id ID do episódio
 * @return array Lista de perguntas do quiz
 */
function buscarQuizPorEpisodio($episodio_id) {
    global $pdo; // usa a conexão PDO global

    // Prepara e executa a query para buscar todas as perguntas do episódio
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE episodio_id = ?");
    $stmt->execute([$episodio_id]);

    // Retorna todas as perguntas como array
    return $stmt->fetchAll();
}

/**
 * Salva o resultado do quiz de um usuário e retorna o número de acertos.
 *
 * @param int $userId ID do usuário
 * @param int $episodio_id ID do episódio
 * @param array $respostas Array com as respostas do usuário
 * @return array ['acertos' => int, 'total' => int] Contagem de acertos e total de perguntas
 */
function salvarResultadoQuiz($userId, $episodio_id, $respostas) {
    global $pdo;

    $acertos = 0;           // contador de respostas corretas
    $total = count($respostas); // total de perguntas respondidas

    foreach ($respostas as $resp) {
        // Incrementa acertos se a resposta estiver correta
        if ($resp['correta']) {
            $acertos++;
        }

        // Insere cada resposta do usuário no banco de dados
        $stmt = $pdo->prepare("
            INSERT INTO quiz_respostas (user_id, pergunta_id, resposta_usuario, correta, episodio_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $resp['pergunta_id'],
            $resp['resposta_usuario'],
            $resp['correta'],
            $episodio_id
        ]);
    }

    // Retorna o total de acertos e o total de perguntas
    return ['acertos' => $acertos, 'total' => $total];
}
