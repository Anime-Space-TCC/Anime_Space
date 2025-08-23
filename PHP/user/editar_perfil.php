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

$id = obterUsuarioAtualId();

// 2. Busca usuário
$user = buscarUsuarioPorId($pdo, $id);
if (!$user) {
    die("Usuário não encontrado.");
}

// 3. Se formulário enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome  = trim($_POST['username']);
    $novoEmail = trim($_POST['email']);

    if (atualizarUsuario($pdo, $id, $novoNome, $novoEmail)) {
        $msg = "Perfil atualizado com sucesso!";
        // Atualiza dados locais
        $user['username'] = $novoNome;
        $user['email']    = $novoEmail;
    } else {
        $msg = "Erro ao atualizar perfil.";
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
<body class="streaming"> 
  <section class="editar-perfil"> 
    <h1>Editar Perfil</h1>

    <?php if (isset($msg)): ?>
      <p class="mensagem-sucesso"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="username">Nome de usuário:</label>
      <input type="text" name="username" id="username" 
             value="<?= htmlspecialchars($user['username']) ?>" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" 
             value="<?= htmlspecialchars($user['email']) ?>" required>

      <button type="submit">Salvar Alterações</button>
    </form>

    <a href="../user/profile.php" class="btn-voltar">Voltar</a>
  </section>
</body>
</html>
