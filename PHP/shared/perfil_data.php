<?php
// =======================
// Inicialização de sessão
// =======================
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once '../shared/auth.php';
require_once '../shared/usuarios.php';
require_once '../shared/perfil.php';
require_once '../shared/gamificacao.php';

// =======================
// Carrega dados do perfil
// =======================
$response = [
    'sucesso' => false,
    'erro' => '',
    'favoritos' => [],
    'historico' => [],
    'recomendacoes' => [],
    'nivel' => 0,
    'tituloNivel' => '',
    'xp' => 0,
    'xpNecessario' => 100,
    'porcentagem' => 0
];

// =======================================
// Verificação de login e Dados do Usuario
// =======================================
try {
    verificarLogin();
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Dados do usuário
    $favoritos = buscarFavoritos($userId) ?? [];
    $historico = buscarHistoricoAnimes($pdo, $userId) ?? [];
    $recomendacoes = buscarRecomendacoes($userId) ?? [];
    $dadosXP = getXP($pdo, $userId);

    $nivel = $dadosXP['nivel'] ?? 1;
    $xp = $dadosXP['xp'] ?? 0;
    $xpNecessario = $nivel * 100;
    $porcentagem = min(100, ($xp / $xpNecessario) * 100);

    $response = [
        'sucesso' => true,
        'favoritos' => $favoritos,
        'historico' => $historico,
        'recomendacoes' => $recomendacoes,
        'nivel' => $nivel,
        'tituloNivel' => tituloNivel($nivel),
        'xp' => $xp,
        'xpNecessario' => $xpNecessario,
        'porcentagem' => $porcentagem
    ];
} catch (Exception $e) {
    $response['erro'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
