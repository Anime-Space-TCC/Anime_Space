<?php
// Inicia a sessão para acessar as variáveis de sessão existentes
session_start();

// Destrói todas as variáveis e dados da sessão
session_unset();
session_destroy();

// Redireciona o usuário para a página de login
header('Location: ../user/login.php');

exit();
?>