<?php
require_once __DIR__ . '/../shared/conexao.php';
session_start();

// Limpa tokens antigos
unset($_SESSION['recupera_senha_token']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verifica se o e-mail existe
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['recupera_senha_msg'] = 'E-mail não encontrado.';
        header('Location: ../user/recupera_senha.php');
        exit;
    }

    // Gera token seguro
    $token = bin2hex(random_bytes(16));
    $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salva no banco
    $stmt = $pdo->prepare("INSERT INTO recuperacao_senha (user_id, token, expiracao) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $token, $expiracao]);

    // Salva token na sessão
    $_SESSION['recupera_senha_token'] = $token;

    // Redireciona para validar_senha.php
    header('Location: ../user/validar_senha.php');
    exit;
}
?>