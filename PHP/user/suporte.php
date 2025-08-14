<?php
session_start();
require __DIR__ . '/../shared/conexao.php';

// Se quiser permitir apenas usuários logados, descomente:
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: login.php');
//     exit();
// }

// Processa envio do formulário
$mensagem_enviada = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['mensagem']);

    if ($nome && $email && $mensagem) {
        $stmt = $pdo->prepare("INSERT INTO suporte (nome, email, mensagem, data_envio) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$nome, $email, $mensagem]);
        $mensagem_enviada = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Suporte - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body>
<div class="suporte">
    <h1>Suporte - Anime Space</h1>
    <p>Bem-vindo ao suporte do Anime Space! Use o formulário abaixo para entrar em contato com nossa equipe.</p>
    <header class="links">
    <nav>
      <a href="../../PHP/user/index.php">Home</a> <!-- Link para home -->
      <a href="login.php">Login</a> <!-- Link para login -->
    </nav>
    </header>
    <?php if ($mensagem_enviada): ?>
        <div class="msg-sucesso">✅ Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.</div>
    <?php endif; ?>

    <div class="form-suporte">
        <form method="POST">
            <label for="nome">Seu nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">Seu e-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="mensagem">Mensagem:</label>
            <textarea name="mensagem" id="mensagem" rows="5" required></textarea>

            <button type="submit">Enviar Mensagem</button>
        </form>
    </div>

    <h2>Informações de Contato</h2>
    <p>📧 E-mail: suporte@animespace.com</p>
    <p>📱 WhatsApp: (61) 99999-9999</p>
    <p>📍 Endereço: Brasília - DF</p>

    <div class="faq">
        <h2>FAQ - Perguntas Frequentes</h2>
        <h3>1. Não consigo assistir aos episódios, o que fazer?</h3>
        <p>Verifique sua conexão com a internet e tente novamente. Caso persista, entre em contato pelo formulário acima.</p>

        <h3>2. Como criar uma conta?</h3>
        <p>Basta acessar a página de cadastro e preencher seus dados. Você poderá salvar episódios e fazer comentários.</p>

        <h3>3. Como reportar um erro no site?</h3>
        <p>Use o formulário de suporte, descrevendo o problema e enviando prints se possível.</p>
    </div>
</div>

</body>
</html>