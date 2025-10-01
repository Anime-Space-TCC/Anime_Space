<?php
require_once __DIR__ . '/conexao.php';
require __DIR__ . '/../../vendor/autoload.php'; // PHPMailer autoload
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

// Função para envio do código 2FA por e-mail via PHPMailer
if (!function_exists('enviarCodigo2FA')) {
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
           $mail->CharSet = 'UTF-8'; // garante que acentos funcionem
           $mail->Subject = 'Código de verificação - Anime Space';

            // Corpo HTML da mensagem
            $mail->Body = '
            <html>
            <head>
                <style>
                    /* Corpo do e-mail */
                    body { 
                        font-family: Arial, sans-serif; 
                        background-color: #eee; 
                        padding: 20px; 
                    }

                    /* Títulos e textos */
                    h2, p, a {
                        color: #eee;
                    }

                    /* Container principal */
                    .container { 
                        background-color: #000000ff; 
                        padding: 30px; 
                        border-radius: 10px; 
                        text-align: center; 
                        max-width: 500px; 
                        margin: auto; 
                    }

                    /* Código 2FA */
                    .codigo { 
                        font-size: 28px; 
                        font-weight: bold; 
                        color: #ff9f00; 
                        margin: 20px 0; 
                    }

                    /* Botão de ação */
                    .btn { 
                        display: inline-block; 
                        padding: 12px 25px; 
                        background-color: #ff9f00; 
                        color: #fff;
                        text-decoration: none; 
                        border-radius: 5px; 
                        font-weight: bold; 
                        margin-top: 20px; 
                        transition: all 0.3s ease;
                    }

                    .btn:hover {
                        background-color: #cc7f00; 
                        box-shadow: 0 0 10px #ff9f00;
                    }

                    /* Rodapé */
                    .footer { 
                        font-size: 12px; 
                        color: #bbb; 
                        margin-top: 25px; 
                    }
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
            </html>
            ';

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
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
        $paginaAtual = basename($_SERVER['PHP_SELF']);
        $paginasPublicas = ['login.php', 'register.php', 'verificar-2fa.php'];

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

            // Se usa 2FA → gerar código
            $codigo = random_int(100000, 999999); 
            $_SESSION['aguardando_2fa'] = true;
            $_SESSION['2fa_user'] = $user;
            $_SESSION['2fa_code'] = $codigo;
            $_SESSION['2fa_expires'] = time() + 300; // 5 minutos

            // Enviar código via e-mail
            enviarCodigo2FA($user['email'], $codigo);

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
}

// Realiza logout
if (!function_exists('logout')) {
    function logout(): void {
        session_unset();
        session_destroy();
    }
}

// Validar senha forte
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
