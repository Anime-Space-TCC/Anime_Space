<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

$id = $_GET['id'] ?? null;

// Recebe e valida os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$nota = floatval($_POST['nota'] ?? 0);
$capa = trim($_POST['capa'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$ano = intval($_POST['ano'] ?? 0);
$generosSelecionados = $_POST['generos'] ?? [];


// Validação básica
if ($nome === '') {
    die("O campo Nome é obrigatório.");
}
if ($nota < 0 || $nota > 10) {
    die("A nota deve estar entre 0 e 10.");
}

// Atualiza ou insere o anime
if ($id) {
    // Atualiza o anime
    $sql = "UPDATE animes SET nome=?, nota=?, capa=?, descricao=?, ano=? WHERE id=?";
    $pdo->prepare($sql)->execute([$nome, $nota, $capa, $descricao, $ano, $id]);

    // Limpa os gêneros atuais
    $pdo->prepare("DELETE FROM anime_generos WHERE anime_id=?")->execute([$id]);

    // Insere os gêneros selecionados (evita vazios)
    foreach ($generosSelecionados as $genero_id) {
        if (!empty($genero_id)) {
            $pdo->prepare("INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)")->execute([$id, $genero_id]);
        }
    }

} else {
    // Insere novo anime
    $sql = "INSERT INTO animes (nome, nota, capa, descricao, ano) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$nome, $nota, $capa, $descricao, $ano]);

    // Pega o ID do anime inserido
    $anime_id = $pdo->lastInsertId();

    // Insere os gêneros selecionados (evita vazios)
    foreach ($generosSelecionados as $genero_id) {
        if (!empty($genero_id)) {
            $pdo->prepare("INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)")->execute([$anime_id, $genero_id]);
        }
    }
}

// Redireciona após salvar
header('Location: admin_animes.php');
exit();
?>
