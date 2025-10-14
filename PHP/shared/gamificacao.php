<?php

function adicionarXP($pdo, $user_id, $xpGanhos) {
    // Busca XP e nível atuais
    $stmt = $pdo->prepare("SELECT xp, nivel FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return;

    $xpAtual = $user['xp'] + $xpGanhos;
    $nivelAtual = $user['nivel'];
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
}

function getXP($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT xp, nivel FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function tituloNivel($nivel) {
    if ($nivel < 5) return "Iniciante Otaku";
    if ($nivel < 10) return "Senpai";
    if ($nivel < 20) return "Pro Otaku";
    return "Lendário dos Animes";
}

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
