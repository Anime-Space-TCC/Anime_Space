<?php
require_once __DIR__ . '/gamificacao.php'; // garante que a função adicionarXP está disponível

function inserirComentario(PDO $pdo, int $userId, int $episodioId, string $comentario): bool
{
    try {
        // Insere o comentário normalmente
        $stmt = $pdo->prepare("
            INSERT INTO comentarios (user_id, episodio_id, comentario, data_comentario)
            VALUES (?, ?, ?, NOW())
        ");
        $ok = $stmt->execute([$userId, $episodioId, $comentario]);

        if (!$ok)
            return false;

        // Verifica se o usuário já ganhou XP por comentar neste episódio
        $stmtLog = $pdo->prepare("
            SELECT COUNT(*) FROM xp_logs
            WHERE user_id = ? AND tipo_acao = 'comentario' AND referencia_id = ?
        ");
        $stmtLog->execute([$userId, $episodioId]);
        $jaGanhouXP = $stmtLog->fetchColumn() > 0;

        // Só adiciona XP na primeira vez que comenta em um episódio específico
        if (!$jaGanhouXP) {
            adicionarXP($pdo, $userId, 15); // 🔹 exemplo: +15 XP por comentário

            // Registra o log
            $log = $pdo->prepare("
                INSERT INTO xp_logs (user_id, tipo_acao, referencia_id, xp_ganho)
                VALUES (?, 'comentario', ?, 15)
            ");
            $log->execute([$userId, $episodioId]);
        }

        return true;

    } catch (PDOException $e) {
        error_log("Erro ao inserir comentário: " . $e->getMessage());
        return false;
    }
}
?>