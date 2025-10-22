<?php
require_once '../shared/auth.php';
require_once '../shared/usuarios.php';
require_once '../shared/perfil.php';
require_once '../shared/gamificacao.php';
require __DIR__ . '/../shared/acessos.php';

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
$historicoAnimes = buscarHistoricoAnimes($pdo, $userId);
$recomendacoes = buscarRecomendacoes($userId);

// Busca dados de XP e nível
$dadosXP = getXP($pdo, $userId);
$nivel = $dadosXP['nivel'] ?? 1;
$xp = $dadosXP['xp'] ?? 0;
$xpNecessario = $nivel * 100;
$porcentagem = min(100, ($xp / $xpNecessario) * 100);

// Verifica se o perfil já foi aprimorado
$stmt = $pdo->prepare("SELECT perfil_completo FROM users WHERE id = ?");
$stmt->execute([$userId]);
$perfilCompleto = (bool)$stmt->fetchColumn();

// Contar quizzes perfeitos
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM quiz_resultados qr
    JOIN quizzes q ON qr.quiz_id = q.id
    WHERE qr.user_id = ? AND qr.pontuacao = q.total_perguntas
");
$stmt->execute([$userId]);
$quizzesPerfeitos = $stmt->fetchColumn() ?? 0;

// Atributos baseados nas ações do usuário
$atributos = [
    'Dedicação'    => count($historicoAnimes),       // 1 por episódio assistido
    'Fama'         => count($favoritos),       // 1 por favorito
    'Conhecimento' => $quizzesPerfeitos * 5,  // +5 por quiz perfeito
];

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
<?php
    $current_page = 'perfil';
    include __DIR__ . '/navbar.php';
  ?>
  <main class="page-content">
    <div class="perfil-rpg">
        <div class="perfil-bainha">
            <div class="blocos">

                <!-- Avatar e info -->
                <div class="bloco avatar-section">
                    <div class="avatar-section">
                        <div class="avatar">
                            <img src="<?= '../uploads/' . basename(buscarFotoPerfil($pdo, $userId)) . '?t=' . time() ?>" alt="Foto de perfil">
                        </div>


                        <!-- Botão abaixo da foto -->
                        <form action="../../PHP/user/perfil.php" method="post" enctype="multipart/form-data">
                            <label for="foto" class="btn-upload">Alterar Foto</label>
                            <input type="file" name="foto" id="foto" accept="image/*" style="display:none" onchange="this.form.submit()"><br>
                            <?php if (!$perfilCompleto): ?>
                                <a href="../../PHP/user/upgrade_perfil.php" class="btn-upgrade">🔥 Aprimorar Perfil</a>
                            <?php endif; ?>
                        </form>

                        <div class="info-player">
                            <h2><?= htmlspecialchars($username) ?></h2>
                            <div class="level">Nível: <?= $nivel ?><p class="titulo">
                                    <?= tituloNivel($nivel) ?></p>
                            </div>
                            <div class="exp-bar">
                                <div class="exp-fill" style="width: <?= $porcentagem ?>%;"></div>
                            </div>
                            <p class="xp-text"><?= $xp ?> / <?= $xpNecessario ?> XP</p>
                        </div>
                    </div>
                </div>

                <!-- Ficha RPG -->
                <div class="bloco ficha-rpg-section">
                    <h3>Ficha de Status</h3>

                    <!-- Atributos -->
                    <div class="atributos">
                        <?php foreach ($atributos as $nome => $valor): ?>
                            <div class="atributo">
                                <p class="nome-atributo"><?= htmlspecialchars($nome) ?></p>
                                <div class="circulo">
                                    <span><?= $valor ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Medalhas -->
                    <div class="medalhas-section">
                        <h4>Medalhas de Conquista</h4>
                        <div class="medalhas">
                            <?php if ($quizzesPerfeitos >= 10): ?>
                                <div class="medalha" title="Otaku Mestre — 10 quizzes perfeitos">🧠</div>
                            <?php endif; ?>
                            <?php if (count($favoritos) >= 10): ?>
                                <div class="medalha" title="Colecionador — 20 animes favoritados">⭐</div>
                            <?php endif; ?>
                            <?php if (count($historicoAnimes) >= 10): ?>
                                <div class="medalha" title="Maratonista — 50 episódios assistidos">🔥</div>
                            <?php endif; ?>
                            <?php if ($nivel >= 10): ?>
                                <div class="medalha" title="Veterano — Chegou ao nível 10">🏆</div>
                            <?php endif; ?>
                        </div>
                    </div>
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
                        <?php if (!empty($recomendacoes)): ?>
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

            <!-- Histórico de Animes -->
            <div class="bloco historico-animes-section">
                <h3>Últimos Animes Acessados</h3>
                <div class="cards-container">
                    <?php if ($historicoAnimes): ?>
                        <?php foreach (array_slice($historicoAnimes, 0, 7) as $h): ?>
                            <a href="../../PHP/user/episodes.php?id=<?= $h['id'] ?>" class="card">
                                <img src="../../img/<?= htmlspecialchars($h['capa']) ?>" alt="<?= htmlspecialchars($h['nome']) ?>">
                                <p><?= htmlspecialchars($h['nome']) ?></p>
                                <small><?= date('d/m/Y H:i', strtotime($h['data_acesso'])) ?></small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum anime acessado recentemente.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    </main>
    <script src="../../JS/perfil.js"></script>

</body>

</html>