<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/usuarios.php';
require_once __DIR__ . '/auth.php';

/**
 * Função responsável por registrar um novo usuário no sistema.
 */
function registrarUsuario($pdo, $username, $email, $password, $password_confirm) {
    $errors = [];

    $username = trim($username ?? '');
    $email = trim($email ?? '');
    $password = $password ?? '';
    $password_confirm = $password_confirm ?? '';

    // Verifica se o nome de usuário foi informado
    if (!$username) {
        $errors[] = "Informe um nome de usuário.";
    }

    // Verifica se o e-mail foi informado e se é válido
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Informe um e-mail válido.";
    }

    // Valida se a senha é forte
    if ($err = validarSenhaForte($password)) {
        $errors[] = $err;
    }

    // Verifica se as senhas coincidem
    if ($password !== $password_confirm) {
        $errors[] = "As senhas não conferem.";
    }

    // Verifica se o usuário ou e-mail já está cadastrado
    if (empty($errors) && usuarioExiste($pdo, $username, $email)) {
        $errors[] = "Usuário ou e-mail já cadastrado.";
    }

    // Se não houver erros, cria o usuário
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $novoId = criarUsuario($pdo, $username, $email, $hash);

        if ($novoId) {
            return ['success' => true, 'user_id' => $novoId, 'username' => $username];
        } else {
            $errors[] = "Erro ao cadastrar usuário.";
        }
    }

    return ['success' => false, 'errors' => $errors];
}
