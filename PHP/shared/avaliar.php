<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/gamificacao.php';

// =======================
// Inicialização de sessão
// =======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =================================
// Verifica se o usuário está logado
// =================================
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["sucesso" => false, "erro" => "Você precisa estar logado para avaliar"]);
    exit;
}

$userId = $_SESSION['user_id'];
$animeId = $_POST['anime_id'] ?? null;
$avaliacao = $_POST['avaliacao'] ?? null;

// ======================
// Validações de entrada
// ======================

// Avaliações válidas: 0 a 10
if (!$animeId || !is_numeric($avaliacao) || $avaliacao < 0 || $avaliacao > 10) {
    echo json_encode(["sucesso" => false, "erro" => "Avaliação inválida"]);
    exit;
}

// Converte para float
$nota = floatval($avaliacao);

// Verifica se já existe avaliação anterior
$stmtCheck = $pdo->prepare("SELECT nota FROM avaliacoes WHERE user_id = ? AND anime_id = ?");
$stmtCheck->execute([$userId, $animeId]);
$avaliacaoExistente = $stmtCheck->fetchColumn();

// Salva ou atualiza avaliação
$stmt = $pdo->prepare("
    INSERT INTO avaliacoes (user_id, anime_id, nota) 
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE nota = ?
");
$stmt->execute([$userId, $animeId, $nota, $nota]);

// Dá XP só se for a primeira vez avaliando
if (!$avaliacaoExistente) {
    // Verifica se já tem log de XP pra essa ação
    $stmtLog = $pdo->prepare("
        SELECT COUNT(*) FROM xp_logs 
        WHERE user_id = ? AND tipo_acao = 'avaliacao' AND referencia_id = ?
    ");
    $stmtLog->execute([$userId, $animeId]);
    $jaGanhouXP = $stmtLog->fetchColumn() > 0;

    if (!$jaGanhouXP) {
        adicionarXP($pdo, $userId, 25);

        // Registra log
        $log = $pdo->prepare("
            INSERT INTO xp_logs (user_id, tipo_acao, referencia_id, xp_ganho)
            VALUES (?, 'avaliacao', ?, 25)
        ");
        $log->execute([$userId, $animeId]);
    }
}

echo json_encode(["sucesso" => true, "nota" => round($nota)]);
?>