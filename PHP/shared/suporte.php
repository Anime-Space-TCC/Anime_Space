<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/gamificacao.php'; // Função adicionarXP

// Envia uma mensagem de suporte e dá XP ao usuário
function enviarMensagemSuporte(int $userId, string $nome, string $email, string $mensagem): bool {
    global $pdo;

    if (empty($nome) || empty($email) || empty($mensagem)) {
        return false;
    }

    try {
        // 1️⃣ Insere a mensagem no banco
        $stmt = $pdo->prepare("
            INSERT INTO suporte (nome, email, mensagem, data_envio)
            VALUES (?, ?, ?, NOW())
        ");
        $ok = $stmt->execute([$nome, $email, $mensagem]);

        if (!$ok) return false;

        // 2️⃣ Verifica se o usuário já ganhou XP por suporte
        $stmtLog = $pdo->prepare("
            SELECT COUNT(*) 
            FROM xp_logs 
            WHERE user_id = ? AND tipo_acao = 'suporte'
        ");
        $stmtLog->execute([$userId]);
        $jaGanhouXP = $stmtLog->fetchColumn() > 0;

        // 3️⃣ Só adiciona XP se ainda não ganhou
        if (!$jaGanhouXP) {
            adicionarXP($pdo, $userId, 100); // +100 XP por ajudar o site

            // Registra o log
            $log = $pdo->prepare("
                INSERT INTO xp_logs (user_id, tipo_acao, referencia_id, xp_ganho)
                VALUES (?, 'suporte', NULL, 100)
            ");
            $log->execute([$userId]);
        }

        return true;

    } catch (PDOException $e) {
        error_log("Erro ao enviar suporte: " . $e->getMessage());
        return false;
    }
}
