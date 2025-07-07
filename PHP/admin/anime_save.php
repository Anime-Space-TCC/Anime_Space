<?php
require __DIR__ . '/../shared/conexao.php';

$id       = $_POST['id']       ?? null;
$imagem   = $_POST['imagem']   ?? '';
$nome     = $_POST['nome']     ?? '';
$generos  = $_POST['generos']  ?? '';
$nota     = $_POST['nota']     ?? 0;

if ($id) {                    // UPDATE
  $sql = "UPDATE animes SET imagem=?, nome=?, generos=?, nota=? WHERE id=?";
  $pdo->prepare($sql)->execute([$imagem, $nome, $generos, $nota, $id]);
} else {                      // INSERT
  $sql = "INSERT INTO animes (imagem,nome,generos,nota) VALUES (?,?,?,?)";
  $pdo->prepare($sql)->execute([$imagem, $nome, $generos, $nota]);
}

header('Location: anime_list.php');
exit;
