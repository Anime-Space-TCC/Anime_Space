<?php
require_once '../shared/auth.php';
require_once '../shared/usuarios.php';
require_once '../shared/perfil.php';
require_once '../shared/gamificacao.php';

// Garante login
verificarLogin();

//Recupera informações do usuário da sessão atual.
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$userTipo = $_SESSION['tipo'] ?? 'user';
$mensagem = "";

// Upload da foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $resultado = atualizarFotoPerfil($pdo, $userId, $_FILES['foto']);
    $mensagem = $resultado === true ? "Foto de perfil atualizada com sucesso!" : $resultado;
}

// Busca a foto do perfil
$fotoPerfil = buscarFotoPerfil($pdo, $userId);

// Busca dados do perfil
$favoritos = buscarFavoritos($userId);
$historico = buscarHistorico($userId);
$recomendacoes = buscarRecomendacoes($userId);

// Busca dados de XP e nível
$dadosXP = getXP($pdo, $userId);
$nivel = $dadosXP['nivel'] ?? 1;
$xp = $dadosXP['xp'] ?? 0;
$xpNecessario = $nivel * 100;
$porcentagem = min(100, ($xp / $xpNecessario) * 100);
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
<div class="menu-lateral">
    <a href="../../PHP/user/index.php" class="home-btn" aria-label="Página Inicial" role="button" tabindex="0">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
            <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
        </svg>
    </a>
    <a href="../../PHP/user/stream.php">Catálogo</a>
    <a href="../../PHP/user/editar_perfil.php">Editar Perfil</a>

    <?php if ($userTipo === 'admin'): ?>
        <a href="../../PHP/admin/index.php">Administrador</a>
    <?php endif; ?>

    <form action="../shared/logout.php" method="post" class="form-logout">
        <input type="submit" value="Sair">
    </form>
</div>

<div class="perfil-rpg">
    <div class="perfil-bainha">
        <div class="blocos">

            <!-- Avatar e info -->
            <div class="bloco avatar-section">
                <div class="avatar-section">
                   <div class="avatar">
                        <img src="../../uploads/<?= htmlspecialchars($fotoPerfil) ?>" alt="Avatar de <?= htmlspecialchars($username) ?>">
                    </div>


                    <!-- Botão abaixo da foto -->
                    <form action="../../PHP/user/perfil.php" method="post" enctype="multipart/form-data">
                      <label for="foto" class="btn-upload">Alterar Foto</label>
                      <input type="file" name="foto" id="foto" accept="image/*" style="display:none" onchange="this.form.submit()">
                    </form>

                    <div class="info-player">
                        <h2><?= htmlspecialchars($username) ?></h2>
                        <div class="level">Nível: <?= $nivel ?><p class="titulo"><?= tituloNivel($nivel) ?></p></div>
                        <div class="exp-bar">
                            <div class="exp-fill" style="width: <?= $porcentagem ?>%;"></div>
                        </div>
                        <p class="xp-text"><?= $xp ?> / <?= $xpNecessario ?> XP</p>
                    </div>
                </div>
            </div>

            <!-- Status rápido -->
            <div class="bloco stats-section">
                <h3>Status</h3>
                <ul>
                    <li>Favoritos: <span><?= count($favoritos) ?></span></li>
                    <li>Histórico: <span><?= count($historico) ?></span></li>
                    <li>Recomendações: <span><?= count($recomendacoes) ?></span></li>
                </ul>
            </div>

        </div>
        <div class="blocos">
            <!-- Favoritos -->
            <div class="favoritos-section">
                <h3>Favoritos</h3>
                <div class="cards-container">
                    <?php if ($favoritos): ?>
                        <?php foreach ($favoritos as $f): ?>
                            <a href="../../PHP/user/episodes.php?id=<?= $f['id'] ?>" class="card">
                                <img src="../../img/<?= htmlspecialchars($f['capa']) ?>" alt="<?= htmlspecialchars($f['nome']) ?>">
                                <p><?= htmlspecialchars($f['nome']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum favorito ainda.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recomendações -->
            <div class="bloco recomendacoes-section">
                <h3>Recomendações</h3>
                <div class="cards-container">
                    <?php if ($recomendacoes): ?>
                        <?php foreach ($recomendacoes as $r): ?>
                            <div class="card">
                                <img src="../../img/<?= htmlspecialchars($r['capa']) ?>" alt="<?= htmlspecialchars($r['nome']) ?>">
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
            </div>

        </div>

        <!-- Histórico continua sozinho -->
        <div class="bloco historico-section">
            <h3>Histórico</h3>
            <div class="cards-container">
                <?php if ($historico): ?>
                    <?php foreach ($historico as $h): ?>
                        <div class="card">
                            <img src="../../img/<?= htmlspecialchars($h['miniatura']) ?>" alt="<?= htmlspecialchars($h['titulo']) ?>">
                            <p><?= htmlspecialchars($h['titulo']) ?></p>
                            <small><?= date('d/m/Y H:i', strtotime($h['data_assistido'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum episódio assistido recentemente.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
// ---------- Carrega e atualiza todos os dados do perfil ----------
function carregarPerfil() {
    fetch('../../PHP/shared/perfil_data.php', { cache: 'no-store' })
        .then(res => res.json())
        .then(data => {
            if (!data.sucesso) {
                console.error('Erro ao carregar perfil:', data.erro);
                return;
            }

            // ---------- Favoritos ----------
            const favContainer = document.querySelector('.favoritos-section .cards-container');
            favContainer.innerHTML = '';
            if (data.favoritos.length) {
                data.favoritos.forEach(f => {
                    const card = document.createElement('a');
                    card.href = `../../PHP/user/episodes.php?id=${f.id}`;
                    card.className = 'card';
                    card.innerHTML = `<img src="../../img/${f.capa}" alt="${f.nome}"><p>${f.nome}</p>`;
                    favContainer.appendChild(card);
                });
            } else {
                favContainer.innerHTML = '<p>Nenhum favorito ainda.</p>';
            }

            // ---------- Histórico ----------
            const histContainer = document.querySelector('.historico-section .cards-container');
            histContainer.innerHTML = '';
            if (data.historico.length) {
                data.historico.forEach(h => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `<img src="../../img/${h.miniatura}" alt="${h.titulo}">
                                      <p>${h.titulo}</p>
                                      <small>${new Date(h.data_assistido).toLocaleString('pt-BR')}</small>`;
                    histContainer.appendChild(card);
                });
            } else {
                histContainer.innerHTML = '<p>Nenhum episódio assistido recentemente.</p>';
            }

            // ---------- Recomendações ----------
            const recContainer = document.querySelector('.recomendacoes-section .cards-container');
            recContainer.innerHTML = '';
            if (data.recomendacoes.length) {
                data.recomendacoes.forEach(r => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `<img src="../../img/${r.capa}" alt="${r.nome}">
                                      <p>${r.nome}</p>
                                      ${r.motivo ? `<small>${r.motivo}</small>` : ''}`;
                    recContainer.appendChild(card);
                });
            } else {
                recContainer.innerHTML = '<p>Sem recomendações no momento.</p>';
            }

            // ---------- XP e Nível ----------
            document.querySelector('.level').innerHTML = `Nível: ${data.nivel}<p class="titulo">${data.tituloNivel}</p>`;
            const expFill = document.querySelector('.exp-fill');
            expFill.style.width = data.porcentagem + '%';
            document.querySelector('.xp-text').textContent = `${data.xp} / ${data.xpNecessario} XP`;
        })
        .catch(err => console.error('Falha ao carregar perfil:', err));
}

// ---------- Inicializa ----------
carregarPerfil();

// ---------- Atualização da foto via AJAX ----------
document.getElementById('foto').addEventListener('change', function() {
    const arquivo = this.files[0];
    if (!arquivo) return;

    const formData = new FormData();
    formData.append('foto', arquivo);

    fetch('../../PHP/shared/profile_upload.php', {
        method: 'POST',
        body: formData,
        cache: 'no-store' // evita problemas de cache
    })
    .then(res => res.json())
    .then(data => {
        if(data.sucesso){
            // Atualiza a imagem do avatar sem precisar recarregar
            const avatarImg = document.querySelector('.avatar img');
            avatarImg.src = data.novaFoto + '?t=' + new Date().getTime();

            // Atualiza todos os dados do perfil (XP, nível, favoritos, etc.)
            carregarPerfil();
        } else {
            console.error('Erro ao atualizar foto:', data.erro);
            alert(data.erro || 'Erro ao atualizar foto.');
        }
    })
    .catch(err => console.error('Falha na requisição:', err));
});


</script>

</body>
</html>