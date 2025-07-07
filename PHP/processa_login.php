<?php
session_start();

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

// Login simplificado (exemplo)
if ($usuario === 'admin' && $senha === '123') {
    $_SESSION['usuario'] = 'admin';
    header('Location: ./admin/admin_animes.php');
    exit();
} else {
    // Usuário comum
    $_SESSION['usuario'] = 'user';
    header('Location: ./user/stream.php');
    exit();
}
?>
