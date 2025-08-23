<?php
require_once '../shared/auth.php';
require_once '../shared/usuarios.php';
require_once '../shared/perfil.php';

// Garante login
verificarLogin();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$mensagem = "";

// Upload de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $resultado = atualizarFotoPerfil($userId, $_FILES['foto']);
    if ($resultado === true) {
        $mensagem = "Foto atualizada com sucesso!";
    } else {
        $mensagem = $resultado; // mensagem de erro
    }
}

// Busca foto do perfil
$fotoPerfil = buscarFotoPerfil($userId);

// Busca dados do perfil
$favoritos = buscarFavoritos($userId);
$historico = buscarHistorico($userId);
$recomendacoes = buscarRecomendacoes($userId);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Anime Space</title>
    <link rel="stylesheet" href="../../CSS/stylePerf.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body class="perfil">

<div class="login-container">
    <div class="login-box">
        <h2>Olá, <?= htmlspecialchars($username) ?>!</h2>

        <?php if (!empty($mensagem)): ?>
            <p class="message error"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <!-- Foto de perfil -->
        <div id="foto-perfil">
            <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de perfil">
        </div>

        <!-- Formulário para upload de foto de perfil -->
        <form action="perfil.php" method="post" enctype="multipart/form-data">
            <label for="foto">Alterar foto de perfil:</label>
            <input type="file" name="foto" id="foto" required>
            <input type="submit" value="Salvar Foto">
        </form>

        <!-- Menu de navegação -->
        <div class="links">
            <a href="../../PHP/user/index.php" class="perfil-btn" aria-label="Página Inicial">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
                </svg>
                Início
            </a>
            <a href="../../PHP/user/stream.php">📺 Streaming</a>
            <a href="../../PHP/user/editar_perfil.php">✏️ Editar Perfil</a>
        </div>

        <!-- Favoritos -->
        <h3>Favoritos</h3>
        <div class="perfil-section">
            <?php if ($favoritos): ?>
                <?php foreach ($favoritos as $f): ?>
                    <div class="item">
                        <img src="../../<?= htmlspecialchars($f['capa']) ?>" alt="<?= htmlspecialchars($f['nome']) ?>" title="<?= htmlspecialchars($f['nome']) ?>">
                        <p><?= htmlspecialchars($f['nome']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você ainda não adicionou favoritos.</p>
            <?php endif; ?>
        </div>

        <!-- Histórico -->
        <h3>Histórico</h3>
        <div class="perfil-section">
            <?php if ($historico): ?>
                <?php foreach ($historico as $h): ?>
                    <div class="item">
                        <img src="../../<?= htmlspecialchars($h['miniatura']) ?>" alt="<?= htmlspecialchars($h['titulo']) ?>" title="<?= htmlspecialchars($h['titulo']) ?>">
                        <p><?= htmlspecialchars($h['titulo']) ?></p>
                        <small><?= date('d/m/Y H:i', strtotime($h['data_assistido'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum episódio assistido recentemente.</p>
            <?php endif; ?>
        </div>

        <!-- Recomendações -->
        <h3>Recomendações</h3>
        <div class="perfil-section">
            <?php if ($recomendacoes): ?>
                <?php foreach ($recomendacoes as $r): ?>
                    <div class="item">
                        <img src="../../<?= htmlspecialchars($r['capa']) ?>" alt="<?= htmlspecialchars($r['nome']) ?>" title="<?= htmlspecialchars($r['nome']) ?>">
                        <p><?= htmlspecialchars($r['nome']) ?></p>
                        <?php if (!empty($r['motivo'])): ?>
                            <small><?= htmlspecialchars($r['motivo']) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sem recomendações no momento.</p>
            <?php endif; ?>
        </div>

        <!-- Formulário para logout -->
        <form action="../shared/logout.php" method="post">
            <input type="submit" value="Sair da Conta" class="logout-btn">
        </form>
    </div>
</div>

</body>
</html>
