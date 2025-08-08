<?php
// Inicia a sessão para acessar as variáveis de sessão existentes
session_start();

// Encerra a sessão atual, removendo todos os dados da sessão
session_destroy();

// Redireciona o usuário para a página de login
header('Location: ../HTML/login.html');

// Garante que o script seja encerrado após o redirecionamento
exit();
?>
