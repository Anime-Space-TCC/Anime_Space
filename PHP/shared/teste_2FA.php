<?php
session_start();

// ==============================
// Gera código 2FA de teste
// ==============================
$codigo = rand(100000, 999999);
$_SESSION['2fa_code'] = $codigo;
$_SESSION['2fa_expires'] = time() + 300; // 5 minutos
$_SESSION['aguardando_2fa'] = true;

?>

<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
    <meta charset="UTF-8">
    <title>Teste 2FA</title>
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
    </head>
        <body>
        <div class="box">
            <h2>Código 2FA de Teste</h2>
            <div class="code"><?= $codigo ?></div>
            <p>Expira em 5 minutos.</p>
            <p>Use esse código na página de verificação.</p>
        </div>
        </body>
</html>
