<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/conexao.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/usuarios.php';
require __DIR__ . '/perfil.php';

$response = ['sucesso' => false, 'erro' => '', 'novaFoto' => ''];

try {
    if (!usuarioLogado()) {
        throw new Exception("Usuário não logado");
    }

    $id = intval(obterUsuarioAtualId());

    if (!isset($_FILES['foto'])) {
        throw new Exception("Nenhum arquivo enviado");
    }

    $resultado = atualizarFotoPerfil($pdo, $id, $_FILES['foto']);

    if (is_array($resultado) && !empty($resultado['sucesso'])) {
        $response['sucesso'] = true;
        $response['novaFoto'] = $resultado['novaFoto'];
    } elseif (is_array($resultado) && isset($resultado['erro'])) {
        throw new Exception($resultado['erro']);
    } else {
        throw new Exception("Erro inesperado ao atualizar foto");
    }
} catch (Exception $e) {
    $response['erro'] = $e->getMessage();
}

// Garante que nada mais será impresso
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
