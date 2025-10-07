<?php
session_start();
require_once 'conexao.php';
require_once 'perfil.php';
require_once 'gamificacao.php';
header('Content-Type: application/json');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['erro' => 'Usuário não logado']);
    exit;
}

$userId = $_SESSION['user_id'];

// Dados do perfil
$favoritos     = buscarFavoritos($userId);
$historico     = buscarHistorico($userId);
$recomendacoes = buscarRecomendacoes($userId);

// Dados de XP e nível
$dadosXP = getXP($pdo, $userId);
$nivel = $dadosXP['nivel'] ?? 1;
$xp    = $dadosXP['xp'] ?? 0;
$xpNecessario = $nivel * 100;
$porcentagem = min(100, ($xp / $xpNecessario) * 100);

// Retorna JSON completo
echo json_encode([
    'sucesso' => true,
    'favoritos' => $favoritos,
    'historico' => $historico,
    'recomendacoes' => $recomendacoes,
    'nivel' => $nivel,
    'xp' => $xp,
    'xpNecessario' => $xpNecessario,
    'porcentagem' => $porcentagem,
    'tituloNivel' => tituloNivel($nivel)
]);
