<?php
require __DIR__ . '/conexao.php';

// =======================
// Inicialização de sessão
// =======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// =====================================
// Registra acessos dos usuários ao site
// =====================================
try {
    $userId = $_SESSION['user_id'] ?? null;
    $tipo = $_SESSION['tipo'] ?? 'visitante';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
    $pagina = $_SERVER['REQUEST_URI'] ?? 'desconhecida';
    $origem = $_SERVER['HTTP_REFERER'] ?? 'Direto';

    // ===== Evita duplicação rápida =====
    // Registra apenas 1 acesso por página a cada 60 segundos por usuário/IP
    $tempoLimite = 60; // segundos
    $ultimoAcesso = $pdo->prepare("
        SELECT data_acesso 
        FROM acessos 
        WHERE user_id " . ($userId ? "= ?" : "IS NULL") . " AND pagina = ?
        ORDER BY data_acesso DESC LIMIT 1
    ");
    $ultimoAcesso->execute($userId ? [$userId, $pagina] : [$pagina]);
    $ultimo = $ultimoAcesso->fetchColumn();

    // Se não houve acesso recente, registra o novo acesso
    if (!$ultimo || (time() - strtotime($ultimo)) > $tempoLimite) {
        $stmt = $pdo->prepare("
            INSERT INTO acessos (user_id, ip, pagina, origem, tipo, data_acesso)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$userId, $ip, $pagina, $origem, $tipo]);
    }

} catch (PDOException $e) {
    // Loga erro no servidor, mas não quebra a página
    error_log("Erro ao registrar acesso: " . $e->getMessage());
}
