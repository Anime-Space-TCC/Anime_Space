<?php
// =======================
// Inicialização de sessão
// =======================
session_start();

require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/usuarios.php';
require_once __DIR__ . '/../shared/perfil.php';

// ==============
// Verifica login
// ==============
if (!usuarioLogado()) {
    header("Location: login.php");
    exit();
}

// ===========================
// Verifica e processa edições
// ===========================
$id = intval(obterUsuarioAtualId());
$user = buscarUsuarioPorId($pdo, $id);
if (!$user) {
    die("Usuário não encontrado.");
}

$msg = '';
$resultado = null;

// =======================
// Processa alterações
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // Atualizar foto
    if ($acao === 'foto' && isset($_FILES['foto'])) {
        $resultado = atualizarFotoPerfil($pdo, $id, $_FILES['foto']);

        if (is_array($resultado) && isset($resultado['erro'])) {
            error_log("ERRO AO ATUALIZAR FOTO: " . $resultado['erro']);
            $msg = "Erro: " . htmlspecialchars($resultado['erro']);
        } elseif (is_array($resultado) && !empty($resultado['sucesso'])) {
            error_log("FOTO ATUALIZADA COM SUCESSO: " . json_encode($resultado));
            $msg = "Foto de perfil atualizada com sucesso!";
        } else {
            error_log("RESULTADO INESPERADO: " . print_r($resultado, true));
            $msg = "Ocorreu um erro inesperado ao atualizar a foto.";
        }
    }

    // Atualizar dados do usuário
    if ($acao === 'dados') {
        $novoNome = trim($_POST['username'] ?? '');
        $novoEmail = trim($_POST['email'] ?? '');
        $novaSenha = trim($_POST['password'] ?? '');

        // Apenas processa se pelo menos um campo tiver sido preenchido
        if ($novoNome === $user['username'] && $novoEmail === $user['email'] && $novaSenha === '') {
            $msg = "Nenhuma alteração detectada.";
        } else {
            // Verifica duplicidade antes de atualizar
            if (usuarioExiste($pdo, $novoNome, $novoEmail, $id)) {
                $msg = "O nome de usuário ou email já está em uso por outro usuário.";
            } else {
                $hashSenha = $novaSenha !== '' ? password_hash($novaSenha, PASSWORD_DEFAULT) : null;
                $atualizou = atualizarUsuario($pdo, $id, $novoNome, $novoEmail, $hashSenha);
                $msg = $atualizou ? "Perfil atualizado com sucesso!" : "Erro ao atualizar perfil.";
            }
        }
    }
}

// === Foto atual ===
$fotoPerfil = buscarFotoPerfil($pdo, $id);
if (!$fotoPerfil) {
    $fotoPerfil = '/PHP/uploads/default.jpg';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../CSS/perfil.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body class="perfil">

    <main class="perfil-container">
        <section class="perfil-editar-card">
            <h1 class="perfil-titulo">Editar Perfil</h1>

            <?php if ($msg !== ''): ?>
                <p class="perfil-mensagem"><?= htmlspecialchars($msg) ?></p>
            <?php endif; ?>

            <!-- Card de imagem -->
            <div class="perfil-foto-section">
                <div class="avatar">
                    <img src="<?= '../uploads/' . basename($fotoPerfil) . '?t=' . time() ?>" alt="Foto de perfil">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="perfil-upload-form">
                    <input type="hidden" name="acao" value="foto">
                    <label for="foto" class="btn-upload">Alterar Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" style="display:none"
                        onchange="this.form.submit()">
                </form>
            </div>

            <!-- Form principal -->
            <form method="POST" class="perfil-form">
                <input type="hidden" name="acao" value="dados">

                <label for="username" class="perfil-label">Nome de usuário:</label>
                <input type="text" name="username" id="username" class="perfil-input"
                    value="<?= htmlspecialchars($user['username']) ?>" placeholder="Opcional">

                <label for="email" class="perfil-label">Email:</label>
                <input type="email" name="email" id="email" class="perfil-input"
                    value="<?= htmlspecialchars($user['email']) ?>" placeholder="Opcional">

                <label for="password" class="perfil-label">Nova Senha:</label>
                <input type="password" name="password" id="password" class="perfil-input"
                    placeholder="Deixe em branco para manter a senha atual">

                <button type="submit" class="perfil-btn">Salvar Alterações</button>
            </form>

            <a href="../user/perfil.php" class="perfil-link-voltar">Voltar ao Perfil</a>
        </section>
    </main>

</body>

</html>