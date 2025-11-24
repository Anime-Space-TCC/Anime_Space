<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../../PHP/user/login.php');
    exit();
}

// Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../../../PHP/admin/CRUDs/temporadas/admin_temporadas.php');
    exit();
}

// Recebe os dados do formulário
$id = $_POST['id'] ?? null;
$anime_id = $_POST['anime_id'] ?? null;
$numero = $_POST['numero'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$ano_inicio = $_POST['ano_inicio'] ?? null;
$ano_fim = $_POST['ano_fim'] ?? null;
$qtd_episodios = $_POST['qtd_episodios'] ?? null;
// processa upload
$nomeArquivo = null;

if (!empty($_FILES['capa']['name'])) {
    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = uniqid() . "." . $ext;

    move_uploaded_file($_FILES['capa']['tmp_name'], __DIR__ . "/../../../../img/$nomeArquivo");
} else {
    // Mantém a capa atual se estiver editando
    if ($id) {
        $stmtOld = $pdo->prepare("SELECT capa FROM temporadas WHERE id=?");
        $stmtOld->execute([$id]);
        $nomeArquivo = $stmtOld->fetchColumn();
    }
}

// Valida campos obrigatórios
if (!$anime_id || !$numero) {
    die("Anime e número da temporada são obrigatórios. <a href='../../../../PHP/admin/CRUDs/temporadas/admin_temporadas.php'>Voltar</a>");
}

try {
    // Atualiza temporada se ID existe
    if ($id) {
        // UPDATE temporada
        $sql = "UPDATE temporadas 
                   SET anime_id=?, numero=?, nome=?, ano_inicio=?, ano_fim=?, qtd_episodios=?, capa=? 
                 WHERE id=?";
        $pdo->prepare($sql)->execute([
            $anime_id,
            $numero,
            $nome,
            $ano_inicio,
            $ano_fim,
            $qtd_episodios,
            $nomeArquivo,
            $id
        ]);
    } else {
        // Insere nova temporada
        $sql = "INSERT INTO temporadas (anime_id, numero, nome, ano_inicio, ano_fim, qtd_episodios, capa) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([
            $anime_id,
            $numero,
            $nome,
            $ano_inicio,
            $ano_fim,
            $qtd_episodios,
            $nomeArquivo,
        ]);
    }

    // Redireciona após salvar
    header('Location: ../../../../PHP/admin/CRUDs/temporadas/admin_temporadas.php');
    exit();

} catch (PDOException $e) {
    // Trata erro de duplicidade
    if ($e->getCode() === '23000') {
        echo "⚠️ Já existe uma temporada com esse número para este anime. ";
        echo "<a href='../../../../PHP/admin/CRUDs/temporadas/admin_temporadas.php'>Voltar</a>";
        exit();
    }
    // Trata outros erros
    echo "❌ Erro ao salvar temporada: " . htmlspecialchars($e->getMessage());
    exit();
}
