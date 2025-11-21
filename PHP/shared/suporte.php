<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/gamificacao.php'; // Função adicionarXP

/**
 * Envia uma mensagem de suporte, opcionalmente envia e-mail e dá XP ao usuário
 */
function enviarMensagemSuporte(int $userId, string $nome, string $email, string $mensagem, bool $enviarEmail = false): bool
{
    global $pdo;

    if (empty($nome) || empty($email) || empty($mensagem)) {
        return false;
    }

    try {
        // Insere a mensagem no banco com referência ao usuário
        $stmt = $pdo->prepare("
            INSERT INTO suporte (user_id, nome, email, mensagem, data_envio, respondido)
            VALUES (?, ?, ?, ?, NOW(), 0)
        ");
        $ok = $stmt->execute([$userId, $nome, $email, $mensagem]);

        if (!$ok)
            return false;

        // Envia e-mail somente se ativado (para produção)
        if ($enviarEmail) {
            $destino = "suporte@animespace.com";
            $assunto = "Nova mensagem de suporte - Usuário ID: $userId";
            $corpo = "Você recebeu uma nova mensagem de suporte.\n\n";
            $corpo .= "Nome: $nome\n";
            $corpo .= "E-mail: $email\n";
            $corpo .= "Mensagem:\n$mensagem\n";
            $headers = "From: $nome <$email>\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (!mail($destino, $assunto, $corpo, $headers)) {
                error_log("Erro ao enviar e-mail de suporte: $nome <$email>");
            }
        }

        // Verifica se o usuário já ganhou XP por suporte
        $stmtLog = $pdo->prepare("
            SELECT COUNT(*) 
            FROM xp_logs 
            WHERE user_id = ? AND tipo_acao = 'suporte'
        ");
        $stmtLog->execute([$userId]);
        $jaGanhouXP = $stmtLog->fetchColumn() > 0;

        // Só adiciona XP se ainda não ganhou
        if (!$jaGanhouXP) {
            adicionarXP($pdo, $userId, 100); // +100 XP

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
