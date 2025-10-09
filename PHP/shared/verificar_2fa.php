<?php
require_once __DIR__ . '/../shared/auth.php';


// Inicializa variáveis
$erro = '';
$sucesso = false;

// Processa submissão do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');

    if (verificarCodigo2FA($codigo)) {
        // Sucesso → redireciona para página inicial
        header("Location: ../../PHP/user/profile.php");
        exit;
    } else {
        $erro = "Código inválido ou expirado. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação em Duas Etapas</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="twofa-page">
    <div class="twofa-container">
        <div class="twofa-box">
            <h2>Verificação 2FA</h2>
            <form method="POST" action="">
                <div class="twofa-textbox">
                    <input type="text" name="codigo" placeholder="Digite o código" required>
                </div>
                <button type="submit" class="twofa-btn">Confirmar</button>
            </form>

            <?php if ($erro): ?>
                <p class="twofa-error"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <div class="twofa-links">
                <a href="logout.php">Cancelar e sair</a>
            </div>
        </div>
    </div>
</body>
</html>
