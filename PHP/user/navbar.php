<?php 
// ===== INÍCIO NAVBAR =====

    // Páginas que não possuem busca
    $paginasSemBusca = ['loja', 'meu-carrinho',
                        'noticias','episodeos',
                        'stream','suporte',
                        'lancamento','temporada',
                        'confirmar_pagamento','quizzes',
                        'perfil']; 
    
// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])):

    // Inclui dependências
    require_once __DIR__ . '/../shared/conexao.php';
    require_once __DIR__ . '/../shared/usuarios.php';
    require_once __DIR__ . '/../shared/gamificacao.php';
    require_once __DIR__ . '/../shared/animes.php';

    // Dados do usuário
    $userId = $_SESSION['user_id'];
    $caminhoFoto = buscarFotoPerfil($pdo, $userId); 
    
    // Dados do carrinho
    $dadosXP = getXP($pdo, $userId);
    $nivel = $dadosXP['nivel'] ?? 1;
    $xp = $dadosXP['xp'] ?? 0;
    $xpNecessario = $nivel * 100;
    $porcentagem = min(100, ($xp / $xpNecessario) * 100);

    // Busca de animes
    $busca = $_GET['busca'] ?? '';
    $lancamentos = $busca !== '' ? buscarAnimePorNome($pdo, $busca) : buscarLancamentos($pdo, 9);


endif;
?>

<!-- ===== NAVBAR HTML ===== -->
<header class="navbar">
    <!-- Lado esquerdo -->
    <div class="nav-left">
        <!-- Botão Menu -->
        <button class="menu-toggle" aria-label="Abrir menu">☰</button>

        <!-- Busca -->
        <div class="busca-container">
            <?php if (!isset($current_page) || !in_array($current_page, $paginasSemBusca)): ?>
                <button class="busca-btn" aria-label="Buscar anime" type="button" id="buscaBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M10 2a8 8 0 105.29 14.29l4.7 4.7a1 1 0 001.42-1.42l-4.7-4.7A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
                    </svg>
                </button>
            <?php endif; ?>
            <!-- Busca expandida -->
            <form method="GET" action="../../PHP/user/stream.php">
                <input type="text" name="busca" placeholder="Digite o anime..." />
                <button type="submit">Ir</button>
            </form>
            <!-- Botão Pagina inicial -->
            <?php if (!isset($current_page) || $current_page !== 'home'): ?>
                <a href="../../PHP/user/index.php" class="sinopse-btn" aria-label="Página Inicial" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lado direito -->
<div class="nav-right">
    <?php if ($current_page === 'loja' || $current_page === 'meu-carrinho'): ?>
        <!-- Botão do Carrinho -->
        <a href="../../PHP/user/meu-carrinho.php" class="nav-carrinho-btn" aria-label="Carrinho">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                <path d="M7 4h-2l-1 2h16l-3 9H8l-1-2H3" />
                <circle cx="10" cy="20" r="2"/>
                <circle cx="18" cy="20" r="2"/>
            </svg>
            <span id="totalCarrinho"><?= $totalCarrinho ?? 0 ?></span>
        </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id']) && $current_page !== 'perfil'): ?>
        <!-- Usuário logado (exceto na página de perfil) -->
        <div class="perfil-card">
            <div class="perfil-area">
                <a href="../../PHP/user/perfil.php" class="perfil-link" aria-label="Ver perfil">
                    <img src="../..<?= htmlspecialchars($caminhoFoto) ?>" alt="Foto de perfil" class="perfil-foto">
                </a>
                <div class="perfil-info">
                    <h2 class="perfil-nome"><?= htmlspecialchars($_SESSION['username'] ?? 'Usuário') ?></h2>
                    <div class="perfil-nivel">Nível: <?= $nivel ?></div>
                    <div class="exp-bar">
                        <div class="exp-fill" style="width: <?= $porcentagem ?>%;"></div>
                    </div>
                    <p class="xp-text"><?= $xp ?> / <?= $xpNecessario ?> XP</p>
                </div>
            </div>
        </div>
    <?php elseif (!isset($_SESSION['user_id'])): ?>
        <a href="../../PHP/user/login.php" class="perfil-btn" aria-label="Entrar ou registrar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 20c0-4 8-4 8-4s8 0 8 4v1H4v-1z" />
            </svg>
        </a>
    <?php endif; ?>
</div>

</header>

<!-- ===== MENU LATERAL ===== -->
<nav class="menu-lateral" id="menuLateral">
    <a href="../../PHP/user/stream.php">Catálogo</a>
    <a href="../../PHP/user/estreias_temporada.php">Estreias</a>
    <a href="../../PHP/user/últimos_episodios.php">Lançamentos</a>
    <a href="../../PHP/user/quizzes.php">Quizzes</a>
    <a href="../../PHP/user/noticias.php">Comunidade</a>
    <a href="../../PHP/user/loja.php">Lojinha</a>

    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
        <a href="../../PHP/admin/index.php">Administrador</a>
    <?php endif; ?>
</nav>


<script src="../../JS/menu.js"></script>
