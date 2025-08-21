<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../../../PHP/admin/temporadas/admin_temporadas.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM temporadas WHERE id = ?");
    $stmt->execute([$id]);
    // Episódios relacionados são removidos por ON DELETE CASCADE
    header('Location: ../../../PHP/admin/temporadas/admin_temporadas.php');
    exit();
} catch (PDOException $e) {
    echo "Erro ao excluir temporada: " . htmlspecialchars($e->getMessage());
    echo "<a href='../../../PHP/admin/temporadas/admin_temporadas.php'>Voltar</a>";
    exit();
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM temporadas WHERE id = ?");
$stmt->execute([$id]);
if ($stmt->fetchColumn() == 0) {
    echo "Temporada não encontrada.";
    echo "<a href='../../../PHP/admin/temporadas/admin_temporadas.php'>Voltar</a>";
    exit();
}
