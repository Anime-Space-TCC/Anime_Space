<?php
require_once __DIR__ . '/conexao.php';

// =========================
// FUNÇÕES DE USUÁRIOS
// =========================

// Busca um usuário pelo ID
function buscarUsuarioPorId(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// Atualiza username, email e senha de um usuário
function atualizarUsuario(PDO $pdo, int $id, string $username, string $email, ?string $password = null): bool {
    if ($password !== null) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $password, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }
}

// Atualiza a foto de perfil do usuário
function atualizarFotoPerfil(PDO $pdo, int $userId, array $file): string|bool {
    $diretorio_destino = __DIR__ . '/../../uploads/';
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    $nome_original = basename($file['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $tipos_permitidos = ['jpg', 'jpeg', 'png'];

    if (!in_array($extensao, $tipos_permitidos)) {
        return "Erro: Apenas arquivos JPG, JPEG e PNG são permitidos.";
    }

    if ($file['size'] > 500000) {
        return "Erro: O arquivo é muito grande (máx. 500KB).";
    }

    $nome_arquivo_unico = $userId . '.' . $extensao;
    $caminho_completo = $diretorio_destino . $nome_arquivo_unico;

    if (!move_uploaded_file($file['tmp_name'], $caminho_completo)) {
        return "Erro ao mover o arquivo.";
    }

    $caminho_relativo_db = 'uploads/' . $nome_arquivo_unico;

    $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE id = ?");
    if (!$stmt->execute([$caminho_relativo_db, $userId])) {
        return "Erro ao salvar caminho no banco.";
    }

    return true;
}

// Busca a foto de perfil do usuário
function buscarFotoPerfil(PDO $pdo, int $userId): string {
    $stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['foto_perfil'])) {
        $foto = __DIR__ . '/../../' . $user['foto_perfil'];
        if (file_exists($foto)) {
            return '../../' . $user['foto_perfil'];
        }
    }

    // fallback para foto padrão
    $default = '../../img/default.jpg';
    if (file_exists($default)) {
        return $default;
    }

    return 'https://placehold.co/150x150/FFF/000?text=Sem+Foto';
}

// Verifica se já existe username ou email
function usuarioExiste(PDO $pdo, string $username, string $email): bool {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->fetchColumn() > 0;
}

// Cria um novo usuário
function criarUsuario(PDO $pdo, string $username, string $email, string $password): int|false {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $hash])) {
        return (int) $pdo->lastInsertId();
    }

    return false;
}
