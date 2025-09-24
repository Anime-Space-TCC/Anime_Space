<?php
require_once __DIR__ . '/conexao.php';

// Envia uma mensagem de suporte para o banco de dados.
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
