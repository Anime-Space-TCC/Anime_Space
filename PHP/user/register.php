<?php 
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/usuarios.php';
require __DIR__ . '/../shared/auth.php'; 
require_once __DIR__ . '/../shared/register.php';

$errors = [];
$result = null;

// Cadastro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário aceitou os termos
    if (empty($_POST['aceitar_termos'])) {
        $errors[] = "Você deve aceitar os Termos de Uso antes de se cadastrar.";
    } else {
        $result = registrarUsuario(
            $pdo,
            $_POST['username'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? '',
            $_POST['password_confirm'] ?? ''
        );

        if ($result['success']) {
            header('Location: login.php');
            exit;
        } else {
            $errors = $result['errors'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" /> 
<title>Cadastro - Anime Space</title> 
<link rel="stylesheet" href="../../CSS/style.css" /> 
<link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body class="login"> 
<div class="login-container">
  <div class="login-box">
    <h2>Cadastro</h2> 
    <?php if ($errors): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form action="register.php" method="post">
      <div class="campo-input">
        <input type="text" name="username" placeholder="Nome de usuário" required 
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="campo-input">
        <input type="email" name="email" placeholder="Email" required 
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div class="campo-input">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <div class="campo-input">
        <input type="password" name="password_confirm" placeholder="Confirme a senha" required />
      </div>

      <div class="checkbox-termos">
        <label>
          <input type="checkbox" name="aceitar_termos" value="1" required>
          Li e aceito os <a href="termos_uso.php">Termos de Uso</a>.
        </label>
      </div>
      <br>
      <input type="submit" value="Cadastrar" class="botao-login" />
    </form>

    <div class="links">
      <a href="../../PHP/user/login.php">Esqueceu a senha?</a>
      <a href="../../PHP/user/login.php">Já tem conta? Faça login</a>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
