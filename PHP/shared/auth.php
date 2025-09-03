<?php
// /PHP/shared/auth.php

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

/**
 * Verifica se o usuário está logado
 *
 * @return bool
 */
if (!function_exists('usuarioLogado')) {
    function usuarioLogado(): bool {
        return isset($_SESSION['user_id']);
    }
}

/**
 * Verifica login e redireciona para login.php se não estiver logado
 */
if (!function_exists('verificarLogin')) {
    function verificarLogin(): void {
        $paginaAtual = basename($_SERVER['PHP_SELF']); // pega só o arquivo atual
        $paginasPublicas = ['login.php', 'register.php']; // páginas abertas

        if (!usuarioLogado() && !in_array($paginaAtual, $paginasPublicas)) {
            // Caminho relativo correto para o login
            header("Location: login.php");
            exit;
        }
    }
}

/**
 * Retorna o ID do usuário logado
 *
 * @return int|null
 */
if (!function_exists('obterUsuarioAtualId')) {
    function obterUsuarioAtualId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }
}

/**
 * Realiza login do usuário
 *
 * @param PDO $pdo
 * @param string $username
 * @param string $password
 * @return array ['success'=>bool, 'error'=>string|null]
 */
if (!function_exists('login')) {
    function login(PDO $pdo, string $username, string $password): array {
        $username = trim($username);

        if (!$username || !$password) {
            return ['success' => false, 'error' => 'Preencha usuário e senha.'];
        }

        $stmt = $pdo->prepare("SELECT id, username, password, tipo FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['tipo']     = $user['tipo'];

            return ['success' => true, 'error' => null];
        }

        return ['success' => false, 'error' => 'Usuário ou senha inválidos.'];
    }
}

/**
 * Realiza logout
 */
if (!function_exists('logout')) {
    function logout(): void {
        session_unset();
        session_destroy();
    }
}
