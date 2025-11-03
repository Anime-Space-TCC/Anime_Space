<?php
require __DIR__ . '/../../../shared/conexao.php';
session_start();

// ==============================
// Verifica se o usuário é admin
// ==============================
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../../../PHP/user/login.php');
    exit();
}

// ==============================
// Recebe o ID (edição) ou cria novo
// ==============================
$id = $_GET['id'] ?? null;

// ==============================
// Campos padrão do episódio
// ==============================
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

// ==============================
// Lista de animes (dropdown)
// ==============================
$animes = $pdo->query("SELECT id, nome FROM animes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// ==============================
// Caso de edição: busca o episódio
// ==============================
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    $episodio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$episodio) {
        die("Episódio não encontrado.");
    }
}

// ==============================
// Processa envio do formulário
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anime_id = $_POST['anime_id'] ?? '';
    $temporada = $_POST['temporada'] ?? 1;
    $numero = $_POST['numero'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $duracao = $_POST['duracao'] ?? null;
    $data_lancamento = $_POST['data_lancamento'] ?? null;
    $video_url = $_POST['video_url'] ?? '';
    $linguagem = $_POST['linguagem'] ?? '';

    // Upload da miniatura (se enviada)
    $miniatura = null;
    if (!empty($_FILES['miniatura']['name'])) {
        $miniatura = time() . "_" . basename($_FILES['miniatura']['name']);
        $uploadPath = "../../../../img/" . $miniatura;

        if (!move_uploaded_file($_FILES['miniatura']['tmp_name'], $uploadPath)) {
            die("Erro ao fazer upload da miniatura.");
        }
    }

    if ($id) {
        // ==========================
        // Atualiza episódio existente
        // ==========================
        $sql = "UPDATE episodios 
                SET anime_id=?, temporada=?, numero=?, titulo=?, descricao=?, duracao=?, 
                    data_lancamento=?, miniatura=IF(?, ?, miniatura), video_url=?, linguagem=? 
                WHERE id=?";
        $pdo->prepare($sql)->execute([
            $anime_id, $temporada, $numero, $titulo, $descricao,
            $duracao, $data_lancamento, $miniatura, $miniatura, $video_url, $linguagem, $id
        ]);
    } else {
        // ==========================
        // Verifica duplicidade antes de inserir
        // ==========================
        $check = $pdo->prepare("
            SELECT COUNT(*) FROM episodios 
            WHERE anime_id = ? AND temporada = ? AND numero = ?
        ");
        $check->execute([$anime_id, $temporada, $numero]);
        $existe = $check->fetchColumn();

        if ($existe > 0) {
            die("<h3 style='color:red;text-align:center;margin-top:50px;'>
                ⚠️ Episódio já existe para este anime, temporada e número.
                <br><a href='admin_episodes.php'>Voltar</a>
            </h3>");
        }

        // ==========================
        // Insere novo episódio
        // ==========================
        $sql = "INSERT INTO episodios 
                (anime_id, temporada, numero, titulo, descricao, duracao, data_lancamento, miniatura, video_url, linguagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([
            $anime_id, $temporada, $numero, $titulo, $descricao,
            $duracao, $data_lancamento, $miniatura ?? 'default.jpg', $video_url, $linguagem
        ]);
    }

    header('Location: ../../../../PHP/admin/CRUDs/episodes/admin_episodes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <title><?= $id ? "Editar Episódio" : "Novo Episódio" ?></title> 
    <link rel="stylesheet" href="../../../../CSS/style.css?v=2" />
    <link rel="icon" href="../../../../img/slogan3.png" type="image/png">
</head>
<body class="admin-cruds">
    <div class="admin-links">
        <h1><?= $id ? "Editar Episódio" : "Cadastrar Novo Episódio" ?></h1> 
        <nav>
            <a href="../../../../PHP/admin/CRUDs/episodes/admin_episodes.php" class="admin-btn">Voltar</a>
            <a href="../../../../PHP/shared/logout.php" class="admin-btn">Sair</a>
        </nav>
    </div>

    <main class="admin-form">
        <form method="POST" enctype="multipart/form-data">
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

          <label>URL do Vídeo:</label><br>
          <input type="text" name="video_url" value="<?= htmlspecialchars($episodio['video_url']) ?>" required><br><br>

          <label>Linguagem:</label><br>
          <input type="text" name="linguagem" value="<?= htmlspecialchars($episodio['linguagem']) ?>"><br><br>

          <label>Mini-imagem:</label><br>
          <input type="file" name="miniatura"><br>
          <?php if (!empty($episodio['miniatura'])): ?>
              <img src="../../../../img/<?= htmlspecialchars($episodio['miniatura']) ?>" 
                   alt="Miniatura do Episódio" width="150" style="margin-top:10px;"><br>
          <?php endif; ?>
          <br>

          <input type="submit" value="Salvar" class="admin-btn"> 
        </form>
    </main>
</body>
</html>
