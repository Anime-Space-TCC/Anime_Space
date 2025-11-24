<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/gamificacao.php';

// =======================
// Inicialização de sessão
// =======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =======================
// Verificação de login
// =======================
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["sucesso" => false, "erro" => "Você precisa estar logado para favoritar"]);
    exit;
}

// ================================
// Lógica de favoritar/desfavoritar
// ================================
$userId = $_SESSION['user_id'];
$animeId = $_POST['anime_id'] ?? null;

if (!$animeId) {
    echo json_encode(["sucesso" => false, "erro" => "Anime inválido"]);
    exit;
}

// Verifica se já está favoritado
$stmt = $pdo->prepare("SELECT * FROM favoritos WHERE user_id = ? AND anime_id = ?");
$stmt->execute([$userId, $animeId]);
$existe = $stmt->fetch();

if ($existe) {
    // Remove favorito (sem XP)
    $pdo->prepare("DELETE FROM favoritos WHERE user_id = ? AND anime_id = ?")
        ->execute([$userId, $animeId]);
    echo json_encode(["sucesso" => true, "favoritado" => false]);
} else {
    // Adiciona favorito
    $pdo->prepare("INSERT INTO favoritos (user_id, anime_id) VALUES (?, ?)")
        ->execute([$userId, $animeId]);

    // Verifica se já ganhou XP antes por esse anime
    $stmtLog = $pdo->prepare("
        SELECT COUNT(*) FROM xp_logs
        WHERE user_id = ? AND tipo_acao = 'favorito' AND referencia_id = ?
    ");
    $stmtLog->execute([$userId, $animeId]);
    $jaGanhouXP = $stmtLog->fetchColumn() > 0;

    if (!$jaGanhouXP) {
        // Ganha XP apenas na primeira vez
        adicionarXP($pdo, $userId, 20);

        // Registra no log de XP
        $log = $pdo->prepare("
            INSERT INTO xp_logs (user_id, tipo_acao, referencia_id, xp_ganho)
            VALUES (?, 'favorito', ?, 20)
        ");
        $log->execute([$userId, $animeId]);
    }

    echo json_encode(["sucesso" => true, "favoritado" => true]);
}
?>