<?php
session_start(); // Inicia a sessão para armazenar dados do usuário

// Obtém os valores enviados pelo formulário via POST, ou define vazio caso não existam
$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

// Login simplificado para exemplo:
// Se usuário e senha forem 'admin' e '123' respectivamente
if ($usuario === 'admin' && $senha === '123') {
    $_SESSION['usuario'] = 'admin'; // Define sessão como administrador
    header('Location: ./admin/admin_animes.php'); // Redireciona para área de admin
    exit();
} else {
    // Para qualquer outro usuário, considera como usuário comum
    $_SESSION['usuario'] = 'user'; // Define sessão como usuário comum
    header('Location: ./user/stream.php'); // Redireciona para área do usuário
    exit();
}
?>
