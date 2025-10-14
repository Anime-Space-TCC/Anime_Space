<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../../PHP/admin/CRUDs/episodes/admin_episodes.php');
    exit();
}

// Coleta e valida os dados do formulário
$id             = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
$anime_id       = isset($_POST['anime_id']) ? (int)$_POST['anime_id'] : 0;
$temporada      = isset($_POST['temporada']) ? (int)$_POST['temporada'] : 1;
$numero         = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;
$titulo         = trim($_POST['titulo'] ?? '');
$descricao      = trim($_POST['descricao'] ?? '');
$duracao        = trim($_POST['duracao'] ?? '');
$data_lanc      = trim($_POST['data_lancamento'] ?? '');
$miniatura      = trim($_POST['miniatura'] ?? '');
$video_url      = trim($_POST['video_url'] ?? '');
$linguagem      = trim($_POST['linguagem'] ?? '');

// Valida obrigatórios
// validaCamposObrigatorios
if ($anime_id <= 0 || $temporada <= 0 || $numero <= 0 || $titulo === '' || $video_url === '') {
    die("Campos obrigatórios ausentes. <a href='../../../PHP/admin/CRUDs/episodes/admin_episodes.php'>Voltar</a>");
}

// Normaliza opcionais para NULL quando vazios
// normalizaCamposOpcionais
$descricao     = ($descricao === '') ? null : $descricao;
$duracao       = ($duracao === '' ? null : (int)$duracao);
$data_lanc     = ($data_lanc === '' ? null : $data_lanc);
$miniatura     = ($miniatura === '' ? null : $miniatura);
$linguagem     = ($linguagem === '' ? null : $linguagem);

try {
    // salvaEpisodio
    if ($id) {
        // UPDATE
        $sql = "UPDATE episodios
                   SET anime_id = ?, temporada = ?, numero = ?, titulo = ?, descricao = ?, 
                       duracao = ?, data_lancamento = ?, miniatura = ?, video_url = ?, linguagem = ?
                 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $anime_id, $temporada, $numero, $titulo, $descricao,
            $duracao, $data_lanc, $miniatura, $video_url, $linguagem, $id
        ]);
    } else {
        // INSERT
        $sql = "INSERT INTO episodios
                    (anime_id, temporada, numero, titulo, descricao, duracao, data_lancamento, miniatura, video_url, linguagem)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $anime_id, $temporada, $numero, $titulo, $descricao,
            $duracao, $data_lanc, $miniatura, $video_url, $linguagem
        ]);
    }

    header('Location: ../../../PHP/admin/CRUDs/episodes/admin_episodes.php');
    exit();

} catch (PDOException $e) {
    // trataErroBanco
    if ($e->getCode() === '23000') {
        echo "Já existe um episódio com esse número para este anime e temporada. ";
        echo "<a href='../../../PHP/admin/CRUDs/episodes/admin_episodes.php'>Voltar</a>";
        exit();
    }
    echo "Erro ao salvar episódio: " . htmlspecialchars($e->getMessage()) . " ";
    echo "<a href='../../../PHP/admin/CRUDs/episodes/admin_episodes.php'>Voltar</a>";
    exit();
}
