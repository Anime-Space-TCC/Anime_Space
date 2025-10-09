<?php
session_start();
require __DIR__ . '/../shared/suporte.php';
require_once __DIR__ . '/../shared/auth.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Indica se a mensagem de suporte foi enviada com sucesso.
$mensagem_enviada = false;

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (enviarMensagemSuporte($nome, $email, $mensagem)) {
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
    <?php
        $current_page = 'busca'; 
        include __DIR__ . '/navbar.php'; 
    ?>
    <main class="page-content">
    <div class="suporte">
        <h1>Suporte - Anime Space</h1>
        <p>Bem-vindo ao suporte do Anime Space! Use o formulário abaixo para entrar em contato com nossa equipe.</p>
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
            <p>📱 WhatsApp: <a target="_blank" alt="Chat on WhatsApp" href="https://wa.me/5561991585929?text=Ola%20tenho%20interesse%20em%20falar%20sobre%20animes">
                Clique para enviar mensagem!</a>
            </p>
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
    </main>
    <?php include __DIR__ . '/rodape.php'; ?>
</body>
</html>
