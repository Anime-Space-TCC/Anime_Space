<?php
session_start();
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/usuarios.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recupera os dados do formulário, removendo espaços extras com trim
    $username = trim($_POST['username'] ?? ''); // Nome de usuário
    $email = trim($_POST['email'] ?? '');       // E-mail do usuário
    $password = $_POST['password'] ?? '';       // Senha
    $password_confirm = $_POST['password_confirm'] ?? ''; // Confirmação de senha

    // =====================
    // Validações dos campos
    // =====================

    // Verifica se o username foi preenchido
    if (!$username) $errors[] = "Informe um nome de usuário.";

    // Verifica se o e-mail é válido
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Informe um e-mail válido.";

    // Verifica se a senha tem pelo menos 6 caracteres
    if (strlen($password) < 6) $errors[] = "A senha deve ter ao menos 6 caracteres.";

    // Verifica se a senha e a confirmação conferem
    if ($password !== $password_confirm) $errors[] = "As senhas não conferem.";

    // =====================
    // Verifica duplicidade
    // =====================
    // Se não houver erros até aqui, checa se já existe usuário ou e-mail no banco
    if (empty($errors) && usuarioExiste($username, $email)) {
        $errors[] = "Usuário ou e-mail já cadastrado.";
    }

    // =====================
    // Criação do usuário
    // =====================
    if (empty($errors)) {
        // Cria novo usuário no banco
        $novoId = criarUsuario($username, $email, $password);

        // Se cadastro for bem-sucedido
        if ($novoId) {
            // Inicia sessão do usuário recém-criado
            $_SESSION['user_id'] = $novoId;
            $_SESSION['username'] = $username;

            // Redireciona para a página de perfil
            header('Location: profile.php');
            exit;
        } else {
            // Se algo deu errado no cadastro, adiciona erro
            $errors[] = "Erro ao cadastrar usuário.";
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
    
    <!-- Exibe mensagens de erro, se houver -->
    <?php if ($errors): ?>
      <ul style="color: #f00; margin-bottom: 15px;">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- Formulário de cadastro -->
    <form action="register.php" method="post">
      <div class="textbox">
        <input type="text" name="username" placeholder="Nome de usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div class="textbox">
        <input type="password" name="password" placeholder="Senha" required />
      </div>
      <div class="textbox">
        <input type="password" name="password_confirm" placeholder="Confirme a senha" required />
      </div>
      <input type="submit" value="Cadastrar" class="btn" />
    </form>

    <div class="links">
      <a href="../../PHP/user/login.php">Já tem conta? Faça login</a>
      <a href="../../PHP/user/index.php">Voltar</a>
    </div>
  </div>
</div>
</body>
</html>
