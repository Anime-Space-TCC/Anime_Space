<?php
session_start();
require __DIR__ . '/../shared/auth.php';
require __DIR__ . '/../shared/suporte.php';
require __DIR__ . '/../shared/acessos.php';

// Bloqueia acesso se não estiver logado
verificarLogin();

// Obtém o ID do usuário logado
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("Usuário não encontrado. Faça login novamente.");
}

$mensagem_enviada = false;
$erro_formulario = '';
$nome = '';
$email = '';
$mensagem = '';

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Validação básica
    if (!empty($nome) && !empty($email) && !empty($mensagem) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $mensagem = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');

        // Envia a mensagem e dá XP
        if (enviarMensagemSuporte($userId, $nome, $email, $mensagem)) {
            $mensagem_enviada = true;
            $nome = $email = $mensagem = ''; // Limpa os campos
        } else {
            $erro_formulario = "Erro ao enviar a mensagem. Tente novamente mais tarde.";
        }
    } else {
        $erro_formulario = "Por favor, preencha todos os campos corretamente.";
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
        $current_page = 'suporte';
        include __DIR__ . '/navbar.php';
    ?>
    <main class="page-content">
        <div class="suporte">
            <h1>Suporte - Anime Space</h1>
            <p>Bem-vindo ao suporte do Anime Space! Use o formulário abaixo para entrar em contato com nossa equipe.</p>

            <?php if ($mensagem_enviada): ?>
                <div class="msg-sucesso">✅ Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.</div>
            <?php elseif (!empty($erro_formulario)): ?>
                <div class="msg-erro">❌ <?= $erro_formulario ?></div>
            <?php endif; ?>

            <div class="form-suporte">
                <form method="POST" novalidate>
                    <label for="nome">Seu nome:</label>
                    <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($nome) ?>">

                    <label for="email">Seu e-mail:</label>
                    <input type="email" name="email" id="email" required value="<?= htmlspecialchars($email) ?>">

                    <label for="mensagem">Mensagem:</label>
                    <textarea name="mensagem" id="mensagem" rows="5" required><?= htmlspecialchars($mensagem) ?></textarea>

                    <button type="submit">Enviar Mensagem</button>
                </form>
            </div>

            <h2>Informações de Contato</h2>
            <p>📧 E-mail: <a href="mailto:suporte@animespace.com">suporte@animespace.com</a></p>
            <p>📱 WhatsApp: <a target="_blank" href="https://wa.me/5561991585929?text=Ola%20tenho%20interesse%20em%20falar%20sobre%20animes">Clique para enviar mensagem!</a></p>
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
