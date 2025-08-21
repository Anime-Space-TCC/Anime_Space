<?php
require __DIR__ . '/../../shared/conexao.php';
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../../PHP/admin/temporadas/admin_temporadas.php');
    exit();
}

$id            = $_POST['id'] ?? null;
$anime_id      = $_POST['anime_id'] ?? null;
$numero        = $_POST['numero'] ?? null;
$nome          = trim($_POST['nome'] ?? '');
$ano_inicio    = $_POST['ano_inicio'] ?? null;
$ano_fim       = $_POST['ano_fim'] ?? null;
$qtd_episodios = $_POST['qtd_episodios'] ?? null;
$capa          = trim($_POST['capa'] ?? ''); // pode ser URL ou caminho do arquivo

if (!$anime_id || !$numero) {
    die("Anime e número da temporada são obrigatórios. <a href='../../../PHP/admin/temporadas/admin_temporadas.php'>Voltar</a>");
}

try {
    if ($id) {
        // UPDATE
        $sql = "UPDATE temporadas 
                   SET anime_id=?, numero=?, nome=?, ano_inicio=?, ano_fim=?, qtd_episodios=?, capa=? 
                 WHERE id=?";
        $pdo->prepare($sql)->execute([
            $anime_id, $numero, $nome, $ano_inicio, $ano_fim, $qtd_episodios, $capa, $id
        ]);
    } else {
        // INSERT
        $sql = "INSERT INTO temporadas (anime_id, numero, nome, ano_inicio, ano_fim, qtd_episodios, capa) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([
            $anime_id, $numero, $nome, $ano_inicio, $ano_fim, $qtd_episodios, $capa
        ]);
    }

    header('Location: ../../../PHP/admin/temporadas/admin_temporadas.php');
    exit();

} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo "⚠️ Já existe uma temporada com esse número para este anime. ";
        echo "<a href='../../../PHP/admin/temporadas/admin_temporadas.php'>Voltar</a>";
        exit();
    }
    echo "❌ Erro ao salvar temporada: " . htmlspecialchars($e->getMessage());
    exit();
}
