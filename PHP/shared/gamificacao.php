<?php 
function adicionarXP($pdo, $user_id, $xpGanhos) {
    // Busca XP e nível atuais
    $stmt = $pdo->prepare("SELECT xp, nivel FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return;

    $xpAntes = $user['xp'];
    $nivelAntes = $user['nivel'];

    $xpAtual = $xpAntes + $xpGanhos;
    $nivelAtual = $nivelAntes;
    $xpNecessario = $nivelAtual * 100;

    // Verifica se subiu de nível
    while ($xpAtual >= $xpNecessario) {
        $xpAtual -= $xpNecessario;
        $nivelAtual++;
        $xpNecessario = $nivelAtual * 100;
    }

    // Atualiza no banco
    $stmt = $pdo->prepare("UPDATE users SET xp = ?, nivel = ? WHERE id = ?");
    $stmt->execute([$xpAtual, $nivelAtual, $user_id]);

    // -------------------------
    // 🔥 REGISTRA O GANHO DE XP
    // -------------------------
    $stmt = $pdo->prepare("
        INSERT INTO xp_logs (user_id, tipo_acao, xp_ganho)
        VALUES (?, 'ganho_xp', ?)
    ");
    $stmt->execute([$user_id, $xpGanhos]);

    // -------------------------
    // 🔔 CRIA NOTIFICAÇÃO DE XP
    // -------------------------
    criarNotificacao(
        $pdo,
        $user_id,
        "Ganhou XP!",
        "Você ganhou $xpGanhos XP por uma atividade!",
        "xp"
    );

    // -------------------------
    // 🔥 SE SUBIU DE NÍVEL, REGISTRA E NOTIFICA
    // -------------------------
    if ($nivelAtual > $nivelAntes) {
        $stmt = $pdo->prepare("
            INSERT INTO xp_logs (user_id, tipo_acao, xp_ganho)
            VALUES (?, 'subiu_nivel', ?)
        ");
        $stmt->execute([$user_id, $nivelAtual]);

        criarNotificacao(
            $pdo,
            $user_id,
            "Subiu de Nível!",
            "Parabéns! Você alcançou o nível $nivelAtual!",
            "xp"
        );
    }
}

// Retorna XP e nivel do usuario
function getXP($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT xp, nivel FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Retorna o titulo baseado no nivel
function tituloNivel($nivel) {
    if ($nivel < 5) return "Iniciante Otaku";
    if ($nivel < 10) return "Senpai";
    if ($nivel < 20) return "Pro Otaku";
    return "Lendário dos Animes";
}
// Verifica e concede bonus de XP por completar perfil
function verificarBonusCompletarPerfil($pdo, $user_id) {
    // Verifica se o usuário já tem idade e nacionalidade
    $stmt = $pdo->prepare("SELECT idade, nacionalidade, xp FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return;

    // Condição: perfil completo e ainda não ganhou o bônus
    $bonusJaDado = $pdo->prepare("SELECT COUNT(*) FROM xp_logs WHERE user_id = ? AND tipo_acao = 'completar_perfil'");
    $bonusJaDado->execute([$user_id]);

    if ($bonusJaDado->fetchColumn() == 0 && !empty($user['idade']) && !empty($user['nacionalidade'])) {
        // Dá o bônus de 100 XP
        adicionarXP($pdo, $user_id, 100);

        // Registra no log
        $stmt = $pdo->prepare("
            INSERT INTO xp_logs (user_id, tipo_acao, xp_ganho)
            VALUES (?, 'completar_perfil', 100)
        ");
        $stmt->execute([$user_id]);
    }
}

?>
