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
?>
