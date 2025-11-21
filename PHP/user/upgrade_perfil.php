<?php
session_start();
require_once __DIR__ . '/../shared/conexao.php';
require_once __DIR__ . '/../shared/gamificacao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Atualiza idade e nacionalidade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idade = $_POST['idade'] ?? null;
    $nacionalidade = $_POST['nacionalidade'] ?? null;

    // Atualiza os dados do usuário
    $stmt = $pdo->prepare("UPDATE users SET idade = :idade, nacionalidade = :nacionalidade WHERE id = :id");
    $stmt->execute([
        ':idade' => $idade ?: null,
        ':nacionalidade' => $nacionalidade ?: null,
        ':id' => $user_id
    ]);

    // Verifica e dá o XP de bônus se ainda não foi concedido
    verificarBonusCompletarPerfil($pdo, $user_id);

    header('Location: perfil.php?msg=Perfil atualizado com sucesso!');
    exit();
}

// Busca informações atuais
$stmt = $pdo->prepare("SELECT idade, nacionalidade FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Marca perfil como completo
$stmt = $pdo->prepare("UPDATE users SET perfil_completo = 1 WHERE id = ?");
$stmt->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Upgrade de Perfil</title>
    <link rel="stylesheet" href="../../CSS/perfil.css">
    <link rel="icon" href="../../../img/slogan3.png" type="image/png">
</head>

<body class="perfil">
    <div class="upgrade-container">
        <h1>🚀 Upgrade de Perfil</h1>
        <p>Complete seu perfil para ganhar <strong>100 XP</strong> e aumentar sua experiência no site!</p>
        <form method="POST">
            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade" min="10" max="120"
                value="<?= htmlspecialchars($dados['idade'] ?? '') ?>" required>

            <label for="nacionalidade">Nacionalidade:</label>
            <input type="text" id="nacionalidade" name="nacionalidade"
                value="<?= htmlspecialchars($dados['nacionalidade'] ?? '') ?>" required>

            <button type="submit">Atualizar Perfil</button>
        </form>
    </div>
</body>

</html>