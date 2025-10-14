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
        // gera hash da senha antes de atualizar
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $hash, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }
}

// Atualiza a foto de perfil do usuário via AJAX
function atualizarFotoPerfil(PDO $pdo, int $userId, array $file): array {
    $diretorio_destino = __DIR__ . '/../uploads/';
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    $nome_original = basename($file['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $tipos_permitidos = ['jpg', 'jpeg', 'png'];

    if (!in_array($extensao, $tipos_permitidos)) {
        return ['sucesso' => false, 'erro' => "Apenas JPG, JPEG e PNG são permitidos."];
    }

    if ($file['size'] > 500000) {
        return ['sucesso' => false, 'erro' => "Arquivo muito grande (máx. 500KB)."];
    }

    $nome_arquivo_unico = $userId . '.' . $extensao;
    $caminho_completo = $diretorio_destino . $nome_arquivo_unico;

    if (!move_uploaded_file($file['tmp_name'], $caminho_completo)) {
        return ['sucesso' => false, 'erro' => "Erro ao mover o arquivo."];
    }

    $caminho_relativo_db = '/PHP/uploads/' . $nome_arquivo_unico;

    $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE id = ?");
    if (!$stmt->execute([$caminho_relativo_db, $userId])) {
        return ['sucesso' => false, 'erro' => "Erro ao salvar no banco."];
    }

    return [
        'sucesso' => true,
        'novaFoto' => $caminho_relativo_db . '?t=' . time()
    ];
}

function buscarFotoPerfil(PDO $pdo, int $userId): string {
    $stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $default = '/../../uploads/default.jpg';

    if ($user && !empty($user['foto_perfil'])) {
        $caminho_fisico = __DIR__ . '/../uploads/' . basename($user['foto_perfil']);
        if (file_exists($caminho_fisico)) {
            return $user['foto_perfil'] . '?t=' . time();
        }
    }

    return $default . '?t=' . time();
}

// Verifica se já existe username ou email
function usuarioExiste(PDO $pdo, string $username, string $email): bool {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->fetchColumn() > 0;
}

// Cria um novo usuário
function criarUsuario(PDO $pdo, string $username, string $email, string $password, string $tipo = 'user'): int|false {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, tipo) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $hash, $tipo])) {
        return (int) $pdo->lastInsertId();
    }

    return false;
}
