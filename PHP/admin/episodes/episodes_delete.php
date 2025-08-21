<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: ../../../PHP/admin/episodes/admin_episodes.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    // Comentários e reações são removidos por ON DELETE CASCADE
    header('Location: ../../../PHP/admin/episodes/admin_episodes.php');
    exit();
} catch (PDOException $e) {
    echo "Erro ao excluir episódio: " . htmlspecialchars($e->getMessage()) . " ";
    echo "<a href='../../../PHP/admin/episodes/admin_episodes.php'>Voltar</a>";
    exit();
}
