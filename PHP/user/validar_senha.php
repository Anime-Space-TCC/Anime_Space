<?php
session_start();
$token = $_SESSION['recupera_senha_token'] ?? '';

if (!$token) {
    header('Location: recupera_senha.php');
    exit;
}

$link = "nova_senha.php"; // link para redefinir a senha
$mensagem = 'Use o token abaixo para redefinir a senha:';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Validar Token - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <div class="recupera">
        <div class="recupera-container">
            <div class="recupera-box">
                <h2>Recuperar Senha</h2>

                <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
                <p class="token"><?= htmlspecialchars($token) ?></p>
                <p class="link-nova-senha"><a href="<?= $link ?>">Ir para a página de redefinir senha</a></p>
                <div class="links">
                    <a href="login.php">Voltar ao login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>