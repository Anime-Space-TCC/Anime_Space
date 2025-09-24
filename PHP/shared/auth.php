<?php
require_once __DIR__ . '/conexao.php';

// ==============================
// Inicialização de sessão
// ==============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================
// Funções de Autenticação
// ==============================

// Verifica se o usuário está logado
if (!function_exists('usuarioLogado')) {
    function usuarioLogado(): bool {
        return isset($_SESSION['user_id']) && empty($_SESSION['aguardando_2fa']);
    }
}

// Verifica login e redireciona para login.php se não estiver logado
if (!function_exists('verificarLogin')) {
    function verificarLogin(): void {
        $paginaAtual = basename($_SERVER['PHP_SELF']); // pega só o arquivo atual
        $paginasPublicas = ['login.php', 'register.php', 'verificar-2fa.php']; // páginas abertas

        if (!usuarioLogado() && !in_array($paginaAtual, $paginasPublicas)) {
            header("Location: login.php");
            exit;
        }
    }
}

// Retorna o ID do usuário logado
if (!function_exists('obterUsuarioAtualId')) {
    function obterUsuarioAtualId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }
}

// Realiza login do usuário (primeira etapa)
if (!function_exists('login')) {
    function login(PDO $pdo, string $username, string $password): array {
        $username = trim($username);

        if (!$username || !$password) {
            return ['success' => false, 'error' => 'Preencha usuário e senha.', '2fa' => false];
        }

        $stmt = $pdo->prepare("SELECT id, username, password, tipo, email, uses_2fa FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Se não usa 2FA, login direto
            if (!$user['uses_2fa']) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['tipo']     = $user['tipo'];
                return ['success' => true, 'error' => null, '2fa' => false];
            }

            // Se usa 2FA → enviar código
            $codigo = random_int(100000, 999999); // mais seguro que rand()
            $_SESSION['aguardando_2fa'] = true;
            $_SESSION['2fa_user'] = $user;
            $_SESSION['2fa_code'] = $codigo;
            $_SESSION['2fa_expires'] = time() + 300; // expira em 5 min

            // Enviar o código por e-mail (aqui simplificado)
            mail($user['email'], "Seu código de verificação", "Seu código 2FA é: $codigo");

            return ['success' => true, 'error' => null, '2fa' => true];
        }

        return ['success' => false, 'error' => 'Usuário ou senha inválidos.', '2fa' => false];
    }
}

// Verifica o código 2FA
if (!function_exists('verificarCodigo2FA')) {
    function verificarCodigo2FA(string $codigo): bool {
        if (!isset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'])) {
            return false;
        }

        if (time() > $_SESSION['2fa_expires']) {
            // Código expirou
            unset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['aguardando_2fa']);
            return false;
        }

        if ($codigo == $_SESSION['2fa_code']) {
            // Finaliza login
            $user = $_SESSION['2fa_user'];
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['tipo']     = $user['tipo'];

            // Limpar sessão temporária
            unset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['aguardando_2fa']);

            return true;
        }

        return false;
    }
}

// Realiza logout
if (!function_exists('logout')) {
    function logout(): void {
        session_unset();
        session_destroy();
    }
}

// Validar senha Forte
if (!function_exists('validarSenhaForte')) {
    function validarSenhaForte(string $pwd): ?string {
        if (strlen($pwd) < 8) return "Senha deve ter ao menos 8 caracteres.";
        if (!preg_match('/[A-Z]/', $pwd)) return "Inclua pelo menos 1 letra maiúscula.";
        if (!preg_match('/[a-z]/', $pwd)) return "Inclua pelo menos 1 letra minúscula.";
        if (!preg_match('/\d/', $pwd)) return "Inclua ao menos 1 número.";
        if (!preg_match('/[\W]/', $pwd)) return "Inclua ao menos 1 símbolo (ex: !@#).";
        return null;
    }
}
