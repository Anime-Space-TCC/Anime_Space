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
$anime_id = intval($_POST['anime_id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$nivel_minimo = intval($_POST['nivel_minimo'] ?? 1);
$ativo = isset($_POST['ativo']) ? 1 : 0;

// Validação básica
if ($titulo === '') {
    die("O campo Título é obrigatório.");
}
if ($anime_id <= 0) {
    die("É necessário selecionar um anime.");
}
if ($nivel_minimo < 1 || $nivel_minimo > 10) {
    die("O nível mínimo deve estar entre 1 e 10.");
}

// Upload de capa (mantém a existente se não for enviada)
$capa = $_POST['capa_atual'] ?? '';

if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = uniqid('quiz_') . '.' . $ext;
    $destino = __DIR__ . '/../../../../img/' . $nomeArquivo;
    move_uploaded_file($_FILES['capa']['tmp_name'], $destino);
    $capa = $nomeArquivo;
}

// Atualiza ou insere o quiz
if ($id) {
    // Atualiza quiz existente
    $sql = "UPDATE quizzes 
            SET anime_id=?, titulo=?, descricao=?, nivel_minimo=?, capa=?, ativo=? 
            WHERE id=?";
    $pdo->prepare($sql)->execute([$anime_id, $titulo, $descricao, $nivel_minimo, $capa, $ativo, $id]);
} else {
    // Insere novo quiz
    $sql = "INSERT INTO quizzes (anime_id, titulo, descricao, nivel_minimo, capa, ativo)
            VALUES (?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$anime_id, $titulo, $descricao, $nivel_minimo, $capa, $ativo]);
}

// Redireciona após salvar
header('Location: admin_quiz.php');
exit();
?>