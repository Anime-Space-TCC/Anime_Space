<?php
session_start();

// Verifica se o usuário está logado, caso contrário redireciona para login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Inclui a conexão com o banco de dados
require_once '../shared/conexao.php';

// Variáveis de controle
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$mensagem = ''; // Para exibir mensagens de erro ou sucesso
$fotoPerfil = ''; // Caminho da foto de perfil

/* ==========================================================
   LÓGICA DE UPLOAD DA FOTO DE PERFIL
========================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $diretorio_destino = '../../uploads/';
    
    // Cria o diretório caso não exista
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    $arquivo_temporario = $_FILES['foto']['tmp_name'];
    $nome_original = basename($_FILES['foto']['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

    // Tipos de arquivos permitidos
    $tipos_permitidos = ['jpg', 'jpeg', 'png'];

    // Validações do upload
    if (!in_array($extensao, $tipos_permitidos)) {
        $mensagem = "Erro: Apenas arquivos JPG, JPEG e PNG são permitidos.";
    } elseif ($_FILES['foto']['size'] > 500000) {
        $mensagem = "Erro: O arquivo é muito grande. Tamanho máximo é 500KB.";
    } else {
        // Define nome único para o arquivo (ID do usuário + extensão)
        $nome_arquivo_unico = $userId . '.' . $extensao;
        $caminho_completo = $diretorio_destino . $nome_arquivo_unico;

        // Move o arquivo para a pasta de uploads
        if (move_uploaded_file($arquivo_temporario, $caminho_completo)) {
            $caminho_relativo_db = 'uploads/' . $nome_arquivo_unico;

            // Atualiza o caminho da foto no banco de dados
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
/* ==========================================================
   FIM DA LÓGICA DE UPLOAD
========================================================== */

/* ==========================================================
   BUSCA FOTO DE PERFIL NO BANCO
========================================================== */
if (empty($fotoPerfil)) {
    $sql_select = "SELECT foto_perfil FROM users WHERE id = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$userId]);
    $userData = $stmt_select->fetch();

    // Caminho físico no servidor
    $caminho_fisico = __DIR__ . '/../../img/default.jpg';

    if (!file_exists($caminho_fisico)) {
       $mensagem = "Erro: O arquivo de imagem não foi encontrado em: " . htmlspecialchars($caminho_fisico);
       $fotoPerfil = 'https://placehold.co/150x150/FFF/000?text=Sem+Foto';
    } else {
       $fotoPerfil = '../../img/default.jpg';
    }
}

/* ==========================================================
   FALLBACK CASO A IMAGEM NÃO EXISTA NO SERVIDOR
========================================================== */
if (!file_exists($fotoPerfil)) {
    $mensagem = "Erro: O arquivo de imagem não foi encontrado em: " . htmlspecialchars($fotoPerfil);
    // Usa imagem de placeholder como último recurso
    $fotoPerfil = 'https://placehold.co/150x150/FFF/000?text=Sem+Foto';
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
                <a href="../../PHP/user/stream.php">📺 Streaming</a>
                <a href="../../PHP/user/editar_perfil.php">✏️ Editar Perfil</a>
            </div>

            <!-- Formulário para logout -->
            <form action="../shared/logout.php" method="post">
                <input type="submit" value="Sair da Conta" />
            </form>
        </div>
    </div>
</body>
</html>
