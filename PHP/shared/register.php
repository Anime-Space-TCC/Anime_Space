<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/usuarios.php';
require_once __DIR__ . '/auth.php';

// ===========================================================
// Função responsável por registrar um novo usuário no sistema.
// ===========================================================
function registrarUsuario(PDO $pdo, string $username, string $email, string $password, string $password_confirm): array {
    $errors = [];

    // Normalização dos dados
    $username = trim($username ?? '');
    $email = trim($email ?? '');
    $password = $password ?? '';
    $password_confirm = $password_confirm ?? '';

    // Validação de campos obrigatórios
    if (empty($username)) {
        $errors[] = "Informe um nome de usuário.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Informe um e-mail válido.";
    }

    if (empty($password) || empty($password_confirm)) {
        $errors[] = "Informe e confirme a senha.";
    }

    // Validação da força da senha (usa função do auth.php)
    if (empty($errors)) {
        $erroSenha = validarSenhaForte($password);
        if ($erroSenha) {
            $errors[] = $erroSenha;
        }
    }

    // Confirmação das senhas
    if ($password !== $password_confirm) {
        $errors[] = "As senhas não conferem.";
    }

    // Verificação de usuário/e-mail duplicado
    if (empty($errors) && usuarioExiste($pdo, $username, $email)) {
        $errors[] = "Usuário ou e-mail já cadastrado.";
    }

    // Criação do usuário se tudo estiver certo
    if (empty($errors)) {
        // ⚡ IMPORTANTE: criarUsuario já aplica password_hash, não precisa fazer novamente
        $novoId = criarUsuario($pdo, $username, $email, $password);

        if ($novoId) {
            return [
                'success'  => true,
                'user_id'  => $novoId,
                'username' => $username
            ];
        } else {
            $errors[] = "Erro ao cadastrar usuário. Tente novamente.";
        }
    }

    // Retorno em caso de erro
    return [
        'success' => false,
        'errors'  => $errors
    ];
}
