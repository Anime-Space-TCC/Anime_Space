<?php
// Inicia a sessão para acessar as variáveis de sessão existentes
session_start();

// Destrói todas as variáveis e dados da sessão
session_unset();
session_destroy();

// Redireciona o usuário para a página de login
// O caminho foi ajustado para subir um nível ('../') e ir para a pasta 'user/'
header('Location: ../user/login.php');

// Garante que o script seja encerrado após o redirecionamento
exit();
?>