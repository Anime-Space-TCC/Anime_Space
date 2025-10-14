<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// Recebe o ID para edição
$id = $_GET['id'] ?? null;

// Campos padrão
$episodio = [
    'anime_id' => '',
    'temporada' => 1,
    'numero' => '',
    'titulo' => '',
    'descricao' => '',
    'duracao' => '',
    'data_lancamento' => '',
    'miniatura' => '',
    'video_url' => '',
    'linguagem' => ''
];

// Busca lista de animes (para dropdown)
$animes = $pdo->query("SELECT id, nome FROM animes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Se for edição, busca os dados do episódio
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    $episodio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$episodio) {
        die("Episódio não encontrado.");
    }
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anime_id = $_POST['anime_id'] ?? '';
    $temporada = $_POST['temporada'] ?? 1;
    $numero = $_POST['numero'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $duracao = $_POST['duracao'] ?? null;
    $data_lancamento = $_POST['data_lancamento'] ?? null;
    $miniatura = $_POST['miniatura'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $linguagem = $_POST['linguagem'] ?? '';

    if ($id) {
        // Atualiza
        $sql = "UPDATE episodios 
                SET anime_id=?, temporada=?, numero=?, titulo=?, descricao=?, duracao=?, data_lancamento=?, miniatura=?, video_url=?, link_download=?, linguagem=? 
                WHERE id=?";
        $pdo->prepare($sql)->execute([$anime_id, $temporada, $numero, $titulo, $descricao, $duracao, $data_lancamento, $miniatura, $video_url, $link_download, $linguagem, $id]);
    } else {
        // Insere novo
        $sql = "INSERT INTO episodios 
                (anime_id, temporada, numero, titulo, descricao, duracao, data_lancamento, miniatura, video_url, link_download, linguagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$anime_id, $temporada, $numero, $titulo, $descricao, $duracao, $data_lancamento, $miniatura, $video_url, $link_download, $linguagem]);
    }

    header('Location: admin_episodios.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Episódio" : "Novo Episódio" ?></title> 
    <link rel="stylesheet" href="../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../img/slogan3.png" type="image/png"> 
</head>
<body class="admin">
    <div class="admin-links">
        <h1><?= $id ? "Editar Episódio" : "Cadastrar Novo Episódio" ?></h1> 
        <nav>
            <a href="../../../PHP/admin/CRUDs/episodes/admin_episodes.php" class="admin-btn">Voltar</a>
            <a href="../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="post" action="../../../PHP/admin/CRUDs/episodes/episodes_save.php">
          <?php if (!empty($id)): ?>
            <input type="hidden" name="id" value="<?= (int)$id ?>">
          <?php endif; ?>
            <label>Anime:</label><br>
            <select name="anime_id" required>
                <option value="">-- Selecione --</option>
                <?php foreach($animes as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= $episodio['anime_id'] == $a['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Temporada:</label><br>
            <input type="number" name="temporada" value="<?= htmlspecialchars($episodio['temporada']) ?>" required><br><br>

            <label>Número do Episódio:</label><br>
            <input type="number" name="numero" value="<?= htmlspecialchars($episodio['numero']) ?>" required><br><br>

            <label>Título:</label><br>
            <input type="text" name="titulo" value="<?= htmlspecialchars($episodio['titulo']) ?>" required><br><br>

            <label>Descrição:</label><br>
            <textarea name="descricao" rows="4"><?= htmlspecialchars($episodio['descricao']) ?></textarea><br><br>

            <label>Duração (minutos):</label><br>
            <input type="number" name="duracao" value="<?= htmlspecialchars($episodio['duracao']) ?>"><br><br>

            <label>Data de Lançamento:</label><br>
            <input type="date" name="data_lancamento" value="<?= htmlspecialchars($episodio['data_lancamento']) ?>"><br><br>

            <label>Arquivo da Miniatura:</label><br>
            <input type="text" name="miniatura" value="<?= htmlspecialchars($episodio['miniatura']) ?>"><br><br>

            <label>URL do Vídeo:</label><br>
            <input type="text" name="video_url" value="<?= htmlspecialchars($episodio['video_url']) ?>" required><br><br>

            <label>Linguagem:</label><br>
            <input type="text" name="linguagem" value="<?= htmlspecialchars($episodio['linguagem']) ?>"><br><br>

            <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
