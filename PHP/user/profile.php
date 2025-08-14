<?php
session_start();

// Verifica se o usuário está logado, caso contrário redireciona para login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Inclui a conexão com o banco de dados
// O caminho foi ajustado para refletir que 'conexao.php' está em '../shared/'
require_once '../shared/conexao.php';

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$mensagem = ''; // Variável para exibir mensagens ao usuário

// --- LÓGICA DE UPLOAD DA FOTO ---
// Este bloco só é executado quando o formulário de upload é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    // Define o diretório onde as fotos serão salvas
    $diretorio_destino = '../../uploads/';
    
    // Cria o diretório se ele não existir
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    $arquivo_temporario = $_FILES['foto']['tmp_name'];
    $nome_original = basename($_FILES['foto']['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

    // Validações básicas (tipo e tamanho do arquivo)
    $tipos_permitidos = ['jpg', 'jpeg', 'png'];
    if (!in_array($extensao, $tipos_permitidos)) {
        $mensagem = "Erro: Apenas arquivos JPG, JPEG e PNG são permitidos.";
    } elseif ($_FILES['foto']['size'] > 500000) { // Limite de 500KB
        $mensagem = "Erro: O arquivo é muito grande. Tamanho máximo é 500KB.";
    } else {
        // Gera um nome único para o arquivo, usando o ID do usuário
        $nome_arquivo_unico = $userId . '.' . $extensao;
        $caminho_completo = $diretorio_destino . $nome_arquivo_unico;

        // Move o arquivo temporário para o diretório final
        if (move_uploaded_file($arquivo_temporario, $caminho_completo)) {
            // Caminho que será salvo no banco de dados (relativo ao diretório 'animespace/Anime_Space/')
            $caminho_relativo_db = 'uploads/' . $nome_arquivo_unico;
            
            // Atualiza o caminho da foto na tabela 'users'
            $sql_update = "UPDATE users SET foto_perfil = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            
            if ($stmt_update->execute([$caminho_relativo_db, $userId])) {
                $mensagem = "Foto de perfil atualizada com sucesso!";
                $fotoPerfil = '../../' . $caminho_relativo_db;
            } else {
                $mensagem = "Erro ao salvar o caminho da foto no banco de dados.";
            }
        } else {
            $mensagem = "Erro ao mover o arquivo para o servidor.";
        }
    }
}

// --- FIM DA LÓGICA DE UPLOAD ---

// Busca a foto de perfil atual do usuário no banco de dados para exibição
// Este bloco será ignorado se a foto foi atualizada no bloco acima
if (empty($fotoPerfil)) {
    $sql_select = "SELECT foto_perfil FROM users WHERE id = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$userId]);
    $userData = $stmt_select->fetch();

    if ($userData) {
        $fotoPerfil = !empty($userData['foto_perfil']) ? '../../' . $userData['foto_perfil'] : '../../img/default.jpg';
    } else {
        $fotoPerfil = '../../img/default.jpg';
    }
}

// Verifica se o caminho da imagem existe no servidor para depuração
$caminho_real_imagem = realpath($fotoPerfil);
if ($caminho_real_imagem === false) {
    $mensagem = "Erro: O arquivo de imagem não foi encontrado em: " . htmlspecialchars($fotoPerfil);
    // Adiciona um placeholder para a imagem não encontrada
    $fotoPerfil = 'https://placehold.co/150x150/FFF/000?text=Foto+n%C3%A3o+encontrada';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Perfil - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/style.css" />
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="perfil">
    <div class="login-container">
        <div class="login-box">
            <h2>Olá, <?= htmlspecialchars($username) ?>!</h2>

            <?php if (!empty($mensagem)): ?>
                <p style="color: red;"><?= htmlspecialchars($mensagem) ?></p>
            <?php endif; ?>
            
            <!-- Foto de perfil -->
            <div>
                <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de perfil" style="width:150px; height:150px;">
            </div>

            <!-- Formulário para upload de foto de perfil -->
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <label for="foto">Alterar foto de perfil:</label>
                <input type="file" name="foto" id="foto" required>
                <input type="submit" value="Salvar Foto">
            </form>

            <p>Seja bem-vindo ao seu perfil. Aqui você poderá visualizar e editar seus dados futuramente.</p>

            <div>
                <a href="../../PHP/user/index.php">🏠 Home</a>
                <a href="stream.php">📺 Streaming</a>
                <a href="editar_perfil.php">✏️ Editar Perfil</a>
            </div>

            <!-- Formulário para logout -->
            <form action="../shared/logout.php" method="post">
                <input type="submit" value="Sair da Conta" />
            </form>
        </div>
    </div>
</body>
</html>
