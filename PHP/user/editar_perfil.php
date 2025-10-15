<?php
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/usuarios.php';

// 1. Verifica login
if (!usuarioLogado()) {
    header("Location: login.php");
    exit();
}

// Obtém o ID do usuário atualmente autenticado
$id = obterUsuarioAtualId();

// 2. Busca usuário
$user = buscarUsuarioPorId($pdo, $id);
if (!$user) {
    die("Usuário não encontrado.");
}

// 3. Processa envio do formulário
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome  = trim($_POST['username'] ?? '');
    $novoEmail = trim($_POST['email'] ?? '');
    $novaSenha = trim($_POST['password'] ?? '');

    // Validação básica
    if ($novoNome === '' || $novoEmail === '') {
        $msg = "Preencha os campos de nome e email.";
    } else {
        if ($novaSenha !== '') {
            // Atualizar com a nova senha
            $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
            $atualizou = atualizarUsuario($pdo, $id, $novoNome, $novoEmail, $hashSenha);
        } else {
            // Atualizar apenas nome e email
            $atualizou = atualizarUsuario($pdo, $id, $novoNome, $novoEmail, null);
        }

        $msg = $atualizou ? "Perfil atualizado com sucesso!" : "Erro ao atualizar perfil.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../CSS/stylePerf.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="perfil">

    <main class="perfil-container">
        <section class="perfil-editar-card">
            <h1 class="perfil-titulo">Editar Perfil</h1>

            <?php if ($msg !== ''): ?>
                <p class="perfil-mensagem"><?= htmlspecialchars($msg) ?></p>
            <?php endif; ?>

            <form method="POST" class="perfil-form">
                <label for="username" class="perfil-label">Nome de usuário:</label>
                <input type="text" name="username" id="username" class="perfil-input"
                    value="<?= htmlspecialchars($user['username']) ?>" required>

                <label for="email" class="perfil-label">Email:</label>
                <input type="email" name="email" id="email" class="perfil-input"
                    value="<?= htmlspecialchars($user['email']) ?>" required>

                <label for="password" class="perfil-label">Nova Senha:</label>
                <input type="password" name="password" id="password" class="perfil-input"
                    placeholder="Deixe em branco para manter a senha atual">

                <button type="submit" class="perfil-btn">Salvar Alterações</button>
            </form>

            <a href="../user/perfil.php" class="perfil-link-voltar">Voltar</a>
        </section>
    </main>

</body>
</html>
