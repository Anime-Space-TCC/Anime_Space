<?php
require_once '../shared/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Animes Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body>
    <div class="recupera">
        <div class="recupera-container">
            <div class="recupera-box">
                <h2>Recuperar Senha</h2>

                <form action="../shared/processa_recupera.php" method="post">
                    <div class="campo-input">
                        <input type="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>
                    <button type="submit" class="botao-recuperar">Enviar Link</button>
                </form>

                <div class="links">
                    <a href="login.php">Voltar ao login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>