<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui conexão com o banco

// Recebe dados enviados via POST, usando operador null coalescing para valores padrão
$id       = $_POST['id']       ?? null;
$capa   = $_POST['capa']   ?? '';
$nome     = $_POST['nome']     ?? '';
$generos  = $_POST['generos']  ?? '';
$nota     = $_POST['nota']     ?? 0;

if ($id) {  // Se existe ID, é atualização (UPDATE)
  $sql = "UPDATE animes SET capa=?, nome=?, generos=?, nota=? WHERE id=?";
  $pdo->prepare($sql)->execute([$capa, $nome, $generos, $nota, $id]);
} else {    // Senão, é inserção de novo registro (INSERT)
  $sql = "INSERT INTO animes (capa,nome,generos,nota) VALUES (?,?,?,?)";
  $pdo->prepare($sql)->execute([$capa, $nome, $generos, $nota]);
}

// Redireciona para a lista de animes após salvar
header('Location: ../../PHP/admin/admin_animes.php');
exit;
