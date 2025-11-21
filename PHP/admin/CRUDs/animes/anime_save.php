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
$descricao = trim($_POST['descricao'] ?? '');
$ano = intval($_POST['ano'] ?? 0);
$generosSelecionados = $_POST['generos'] ?? [];

// Upload da capa (se enviada)
$capa = null;
if (!empty($_FILES['capa']['name'])) {
    $capa = time() . "_" . $_FILES['capa']['name'];
    move_uploaded_file($_FILES['capa']['tmp_name'], "../../../../img/" . $capa);
}

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
    $sql = "UPDATE animes 
            SET nome=?, nota=?, capa=IF(?, ?, capa), descricao=?, ano=? 
            WHERE id=?";
    $pdo->prepare($sql)->execute([$nome, $nota, $capa, $capa, $descricao, $ano, $id]);

    // Limpa os gêneros atuais
    $pdo->prepare("DELETE FROM anime_generos WHERE anime_id=?")->execute([$id]);

    // Insere os gêneros selecionados
    foreach ($generosSelecionados as $genero_id) {
        if (!empty($genero_id)) {
            $pdo->prepare("INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)")->execute([$id, $genero_id]);
        }
    }

} else {
    // Insere novo anime
    $sql = "INSERT INTO animes (nome, nota, capa, descricao, ano) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$nome, $nota, $capa ?? 'default.jpg', $descricao, $ano]);

    // Pega o ID do anime inserido
    $anime_id = $pdo->lastInsertId();

    // Insere os gêneros selecionados
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