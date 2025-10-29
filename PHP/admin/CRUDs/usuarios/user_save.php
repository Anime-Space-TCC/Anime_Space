<?php
session_start();
require __DIR__ . '/../../../shared/conexao.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    exit("Acesso negado");
}

$id = $_POST['id'] ?? null;
$username = $_POST['username'];
$email = $_POST['email'];
$tipo = $_POST['tipo'];
$password = $_POST['password'];

// Upload da foto (se enviada)
$foto = null;
if (!empty($_FILES['foto_perfil']['name'])) {
    $foto = time() . "_" . $_FILES['foto_perfil']['name'];
    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], "../../../uploads/" . $foto);
}

// Editar
if ($id) {
    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, email=?, tipo=?, password=?, foto_perfil=IF(?, ?, foto_perfil) WHERE id=?";
        $pdo->prepare($sql)->execute([$username, $email, $tipo, $hash, $foto, $foto, $id]);
    } else {
        $sql = "UPDATE users SET username=?, email=?, tipo=?, foto_perfil=IF(?, ?, foto_perfil) WHERE id=?";
        $pdo->prepare($sql)->execute([$username, $email, $tipo, $foto, $foto, $id]);
    }
} 
// Novo
else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, tipo, foto_perfil) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$username, $email, $hash, $tipo, $foto ?? 'default.jpg']);
}

header("Location: admin_user.php");
exit();
