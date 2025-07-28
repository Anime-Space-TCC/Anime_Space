<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

// Busca os dados atuais
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['username'];
    $novoEmail = $_POST['email'];

    $update = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $update->execute([$novoNome, $novoEmail, $id]);

    $msg = "Perfil atualizado com sucesso!";
    $user['username'] = $novoNome;
    $user['email'] = $novoEmail;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="../../CSS/style0.css">
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="streaming">
  <section class="editar-perfil">
    <h1>Editar Perfil</h1>

    <?php if (isset($msg)): ?>
      <p class="mensagem-sucesso"><?= $msg ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="username">Nome de usuário:</label>
      <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <button type="submit">Salvar Alterações</button>
    </form>
    <a href="../user/profile.php" class="btn-voltar">Voltar</a>
  </section>
</body>
</html>
