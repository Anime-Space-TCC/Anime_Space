<?php
require __DIR__ . '/../../../shared/conexao.php'; 
session_start(); 

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Obtém o ID do anime
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID inválido.");
}

try {
    // Inicia uma transação
    $pdo->beginTransaction();

    // Exclui os gêneros relacionados primeiro (evita erro de integridade referencial)
    $stmt = $pdo->prepare("DELETE FROM anime_generos WHERE anime_id = ?");
    $stmt->execute([$id]);

    // Agora exclui o anime
    $stmt = $pdo->prepare("DELETE FROM animes WHERE id = ?");
    $stmt->execute([$id]);

    // Confirma a transação
    $pdo->commit();

    // Redireciona após exclusão
    header("Location: admin_animes.php");
    exit();

} catch (Exception $e) {
    // Em caso de erro, reverte as operações
    $pdo->rollBack();
    die("Erro ao excluir o anime: " . $e->getMessage());
}
?>
