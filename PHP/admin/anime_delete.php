<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui a conexão com o banco de dados
session_start(); // Inicia a sessão para controle de autenticação

// Verifica se o usuário está logado como admin, se não, redireciona para login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../../PHP/user/login.php');
    exit();
}

// Obtém o ID do anime pela query string
$id = $_GET['id'] ?? null;
// Verifica se o ID foi passado, caso contrário encerra com mensagem
if (!$id) {
    die("ID inválido.");
}

// Prepara e executa a query para deletar o anime com o ID fornecido
$stmt = $pdo->prepare("DELETE FROM animes WHERE id = ?");
$stmt->execute([$id]);

// Redireciona para a página de gerenciamento de animes após exclusão
header("Location: admin_animes.php");
exit();
?>
