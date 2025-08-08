<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui conexão com o banco

// Recebe dados enviados via POST, usando operador null coalescing para valores padrão
$id       = $_POST['id']       ?? null;
$imagem   = $_POST['imagem']   ?? '';
$nome     = $_POST['nome']     ?? '';
$generos  = $_POST['generos']  ?? '';
$nota     = $_POST['nota']     ?? 0;

if ($id) {  // Se existe ID, é atualização (UPDATE)
  $sql = "UPDATE animes SET imagem=?, nome=?, generos=?, nota=? WHERE id=?";
  $pdo->prepare($sql)->execute([$imagem, $nome, $generos, $nota, $id]);
} else {    // Senão, é inserção de novo registro (INSERT)
  $sql = "INSERT INTO animes (imagem,nome,generos,nota) VALUES (?,?,?,?)";
  $pdo->prepare($sql)->execute([$imagem, $nome, $generos, $nota]);
}

// Redireciona para a lista de animes após salvar
header('Location: anime_list.php');
exit;
