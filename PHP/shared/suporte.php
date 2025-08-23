<?php
require_once __DIR__ . '/conexao.php';

/**
 * Salva uma mensagem de suporte no banco de dados
 *
 * @param string $nome
 * @param string $email
 * @param string $mensagem
 * @return bool true se sucesso, false se falha
 */
function enviarMensagemSuporte($nome, $email, $mensagem) {
    global $pdo;

    if (empty($nome) || empty($email) || empty($mensagem)) {
        return false;
    }

    $stmt = $pdo->prepare("
        INSERT INTO suporte (nome, email, mensagem, data_envio)
        VALUES (?, ?, ?, NOW())
    ");

    return $stmt->execute([$nome, $email, $mensagem]);
}
