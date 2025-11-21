<?php

// Configurações do banco de dados
$host = 'localhost';
$db = 'anime_space';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Data Source Name (DSN) - string de conexão usada pelo PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções para a conexão PDO
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // Lança exceções quando ocorrem erros
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Retorna resultados como array associativo
  PDO::ATTR_EMULATE_PREPARES => false,               // Desativa a emulação de prepared statements, usa os nativos do MySQL
];

try {
  // Tenta estabelecer a conexão com o banco de dados usando PDO
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  // Se ocorrer um erro na conexão, exibe a mensagem e encerra o script
  exit('Erro de conexão: ' . $e->getMessage());
}
