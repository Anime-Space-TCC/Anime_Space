<?php
require_once '../shared/conexao.php';

$erro = '';
$token = $_POST['token'] ?? null;

// ================================
// Processa submissão do formulário
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if (!$token) {
        $erro = "O token é obrigatório.";
    } elseif ($senha !== $confirmar || empty($senha)) {
        $erro = "As senhas não coincidem ou estão vazias.";
    } else {
        // Atualiza a senha apenas se o token for válido e não expirado
        $stmt = $pdo->prepare("UPDATE users u
                               INNER JOIN recuperacao_senha r ON u.id = r.user_id
                               SET u.password = :senha
                               WHERE r.token = :token AND r.expiracao >= NOW()");
        $stmt->execute([
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':token' => $token
        ]);

        // Remove token após uso
        $stmt = $pdo->prepare("DELETE FROM recuperacao_senha WHERE token = ?");
        $stmt->execute([$token]);

        echo "<script>alert('Senha atualizada com sucesso!'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Nova Senha - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body>
    <div class="recupera">
        <div class="recupera-container">
            <div class="recupera-box">
                <h2>Nova Senha</h2>

                <?php if (!empty($erro)): ?>
                    <p class="mensagem"><?= htmlspecialchars($erro) ?>
                    </p>
                <?php endif; ?>

                <form method="post">
                    <div class="campo-input">
                        <input type="text" name="token" placeholder="Cole o token aqui" required>
                    </div>
                    <div class="campo-input">
                        <input type="password" name="senha" placeholder="Digite sua nova senha" required>
                    </div>
                    <div class="campo-input">
                        <input type="password" name="confirmar" placeholder="Confirme sua nova senha" required>
                    </div>
                    <button type="submit" class="botao-recuperar">Atualizar Senha</button>
                </form>

                <div class="links">
                    <a href="login.php">Voltar ao login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>