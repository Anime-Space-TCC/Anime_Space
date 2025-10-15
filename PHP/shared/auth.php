<?php
require_once __DIR__ . '/conexao.php';
require __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ==============================
// Inicialização de sessão
// ==============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================
// Funções auxiliares
// ==============================

function enviarCodigo2FA(string $email, int $codigo): bool {
    $config = require __DIR__ . '/config.php';
    $smtp   = $config['smtp'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $smtp['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp['username'];
        $mail->Password   = $smtp['password'];
        $mail->SMTPSecure = $smtp['secure'];
        $mail->Port       = $smtp['port'];

        $mail->setFrom($smtp['from'], $smtp['fromName']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Código de verificação - Anime Space';

        $mail->Body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #eee; padding: 20px; }
                h2, p, a { color: #eee; }
                .container { background-color: #000000ff; padding: 30px; border-radius: 10px; text-align: center; max-width: 500px; margin: auto; }
                .codigo { font-size: 28px; font-weight: bold; color: #ff9f00; margin: 20px 0; }
                .btn { display: inline-block; padding: 12px 25px; background-color: #ff9f00; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; transition: all 0.3s ease; }
                .btn:hover { background-color: #cc7f00; box-shadow: 0 0 10px #ff9f00; }
                .footer { font-size: 12px; color: #bbb; margin-top: 25px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Verificação em duas etapas</h2>
                <p>Olá! Para continuar, use o código abaixo:</p>
                <div class="codigo">' . $codigo . '</div>
                <p>Este código expira em 5 minutos.</p>
                <a href="#" class="btn">Não compartilhar este código</a>
                <div class="footer">
                    Se você não solicitou este e-mail, ignore-o.<br>
                    Anime Space © ' . date('Y') . '
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erro PHPMailer: {$mail->ErrorInfo}");
        return false;
    }
}

// ==============================
// Funções de Autenticação
// ==============================

function usuarioLogado(): bool {
    return isset($_SESSION['user_id']) && empty($_SESSION['aguardando_2fa']);
}
// Redireciona se não estiver logado
function verificarLogin(): void {
    $paginaAtual = basename($_SERVER['PHP_SELF']);
    $paginasPublicas = ['login.php', 'register.php', 'verificar-2fa.php'];

    if (!usuarioLogado() && !in_array($paginaAtual, $paginasPublicas)) {
        header("Location: login.php");
        exit;
    }
}
// Retorna o ID do usuario logado
function obterUsuarioAtualId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

// ------------------------------
// Função principal de login
// ------------------------------
function login(PDO $pdo, string $username, string $password): array {
    $username = trim($username);
    $password = trim($password);

    if ($username === '' || $password === '') {
        return ['success' => false, 'error' => 'Informe usuário e senha.'];
    }

    // Normaliza espaços invisíveis e acentuação
    $usernameNormalized = normalizarTexto($username);

    // Busca direta no banco (collation já é case-insensitive)
    $stmt = $pdo->prepare("SELECT id, username, password, tipo FROM users WHERE username COLLATE utf8mb4_unicode_ci = :username LIMIT 1");
    $stmt->execute([':username' => $usernameNormalized]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return ['success' => false, 'error' => 'Usuário ou senha inválidos.'];
    }

    // Verifica se o hash é reconhecido (bcrypt)
    $hashInfo = password_get_info($user['password']);
    if ($hashInfo['algo'] !== 0) {
        $valid = password_verify($password, $user['password']);
    } else {
        $valid = hash_equals($user['password'], $password);
    }

    if (!$valid) {
        return ['success' => false, 'error' => 'Usuário ou senha inválidos.'];
    }

    // Sucesso: inicializa sessão
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['tipo']     = $user['tipo'];

    return ['success' => true];
}

/*
function login(PDO $pdo, string $username, string $password): array {
    $username = trim($username);
    $password = trim($password);

    if ($username === '' || $password === '') {
        return ['success' => false, 'error' => 'Informe usuário e senha.', '2fa' => false];
    }

    // Normaliza espaços invisíveis e acentuação
    $usernameNormalized = normalizarTexto($username);

    // Busca usuário no banco
    $stmt = $pdo->prepare("SELECT id, username, password, tipo, email, uses_2fa FROM users WHERE username COLLATE utf8mb4_unicode_ci = :username LIMIT 1");
    $stmt->execute([':username' => $usernameNormalized]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return ['success' => false, 'error' => 'Usuário ou senha inválidos.', '2fa' => false];
    }

    // Verifica senha
    $hashInfo = password_get_info($user['password']);
    if ($hashInfo['algo'] !== 0) {
        $valid = password_verify($password, $user['password']);
    } else {
        $valid = hash_equals($user['password'], $password);
    }

    if (!$valid) {
        return ['success' => false, 'error' => 'Usuário ou senha inválidos.', '2fa' => false];
    }

    // Se não usa 2FA, login direto
    if (!$user['uses_2fa']) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['tipo']     = $user['tipo'];
        return ['success' => true, '2fa' => false];
    }

    // Se usa 2FA → gerar código e salvar sessão temporária
    $codigo = random_int(100000, 999999);
    $_SESSION['aguardando_2fa'] = true;
    $_SESSION['2fa_user']       = $user;
    $_SESSION['2fa_code']       = $codigo;
    $_SESSION['2fa_expires']    = time() + 300; // 5 minutos

    enviarCodigo2FA($user['email'], $codigo);

    return ['success' => true, 'error' => null, '2fa' => true];
}
*/

// Normaliza texto (username/email) 
if (!function_exists('normalizarTexto')) {
    function normalizarTexto(string $texto): string {
        $texto = trim($texto);
        $texto = mb_strtolower($texto, 'UTF-8');
        return $texto;
    }
}

// Inicia processo 2FA
function verificarCodigo2FA(string $codigo): bool {
    if (!isset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'])) {
        return false;
    }

    if (time() > $_SESSION['2fa_expires']) {
        unset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['aguardando_2fa']);
        return false;
    }

    if ($codigo == $_SESSION['2fa_code']) {
        $user = $_SESSION['2fa_user'];
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['tipo']     = $user['tipo'];

        unset($_SESSION['2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expires'], $_SESSION['aguardando_2fa']);
        return true;
    }

    return false;
}

// Função de logout
function logout(): void {
    session_unset();
    session_destroy();
}

// Valida a força da senha
function validarSenhaForte(string $pwd): ?string {
    if (strlen($pwd) < 8) return "Senha deve ter ao menos 8 caracteres.";
    if (!preg_match('/[A-Z]/', $pwd)) return "Inclua pelo menos 1 letra maiúscula.";
    if (!preg_match('/[a-z]/', $pwd)) return "Inclua pelo menos 1 letra minúscula.";
    if (!preg_match('/\d/', $pwd)) return "Inclua ao menos 1 número.";
    if (!preg_match('/[\W]/', $pwd)) return "Inclua ao menos 1 símbolo (ex: !@#).";
    return null;
}
