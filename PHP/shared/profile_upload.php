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

if (!isset($_FILES['foto'])) {
    echo json_encode(['erro' => 'Nenhum arquivo enviado']);
    exit;
}

// Atualiza foto usando função já existente
$resultado = atualizarFotoPerfil($pdo, $userId, $_FILES['foto']);

if ($resultado === true) {
    $novaFoto = buscarFotoPerfil($pdo, $userId);
    echo json_encode([
        'sucesso' => true,
        'novaFoto' => $novaFoto
    ]);
} else {
    echo json_encode([
        'sucesso' => false,
        'erro' => $resultado
    ]);
}
