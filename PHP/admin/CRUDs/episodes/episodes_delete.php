<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}
// Verifica o ID do episódio
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Redireciona se o ID for inválido
if ($id <= 0) {
    header('Location: ../../../../PHP/admin/CRUDs/episodes/admin_episodes.php');
    exit();
}

// Exclui o episódio do banco de dados
try {
    $stmt = $pdo->prepare("DELETE FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ../../../../PHP/admin/CRUDs/episodes/admin_episodes.php');
    exit();
} catch (PDOException $e) {
    // Exibe erro ao excluir episódio
    echo "Erro ao excluir episódio: " . htmlspecialchars($e->getMessage()) . " ";
    echo "<a href='../../../../PHP/admin/CRUDs/episodes/admin_episodes.php'>Voltar</a>";
    exit();
}
