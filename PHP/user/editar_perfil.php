<?php
session_start(); // Inicia a sessão para acessar variáveis de sessão
require __DIR__ . '/../shared/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit(); // Encerra o script
}

$id = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

// Busca os dados atuais do usuário no banco
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(); // Armazena os dados retornados na variável $user

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['username']; // Recebe o novo nome do formulário
    $novoEmail = $_POST['email']; // Recebe o novo e-mail do formulário

    // Atualiza os dados do usuário no banco
    $update = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $update->execute([$novoNome, $novoEmail, $id]);

    $msg = "Perfil atualizado com sucesso!"; // Mensagem de sucesso

    // Atualiza os dados locais para refletirem as mudanças
    $user['username'] = $novoNome;
    $user['email'] = $novoEmail;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="../../CSS/style.css"> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="streaming"> 
  <section class="editar-perfil"> 
    <h1>Editar Perfil</h1>

    <!-- Exibe a mensagem de sucesso, se houver -->
    <?php if (isset($msg)): ?>
      <p class="mensagem-sucesso"><?= $msg ?></p>
    <?php endif; ?>

    <!-- Formulário para editar nome de usuário e e-mail -->
    <form method="POST">
      <label for="username">Nome de usuário:</label>
      <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <button type="submit">Salvar Alterações</button>
    </form>

    <!-- Botão para voltar à página de perfil -->
    <a href="../user/profile.php" class="btn-voltar">Voltar</a>
  </section>
</body>
</html>
