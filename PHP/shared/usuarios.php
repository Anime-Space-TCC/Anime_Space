<?php
require_once __DIR__ . '/conexao.php';

// ===============================
// Funcões relacionadas a usuários
// ===============================

// Busca um usuário pelo ID
function buscarUsuarioPorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT id, username, email, tipo, foto_perfil FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retorna null se não encontrar nada ou o array plano com os dados
    if (!$usuario || !is_array($usuario)) {
        return null;
    }

    return [
        'id' => $usuario['id'],
        'username' => $usuario['username'] ?? '',
        'email' => $usuario['email'] ?? '',
        'tipo' => $usuario['tipo'] ?? 'user',
        'foto_perfil' => $usuario['foto_perfil'] ?? null
    ];
}


// Atualiza username, email e senha de um usuário
function atualizarUsuario(PDO $pdo, int $id, string $username, string $email, ?string $password = null): bool
{
    $username = normalizarTexto($username);
    $email = trim($email);

    if ($password !== null) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $hash, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }
}

// Atualiza a foto de perfil do usuário via upload
function atualizarFotoPerfil(PDO $pdo, int $userId, array $file): array
{
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $nomeOriginal = basename($file['name']);
    $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png'];

    if (!in_array($ext, $permitidos)) {
        return ['sucesso'=>false, 'erro'=>'Formato inválido'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['sucesso'=>false, 'erro'=>'Erro no upload'];
    }

    // Nome único baseado no userId
    $novoNome = $userId . '.' . $ext;
    $destino = $uploadDir . $novoNome;

    // Se já existir, remove o arquivo antigo antes de salvar
    if (file_exists($destino)) {
        unlink($destino);
    }

    if (!move_uploaded_file($file['tmp_name'], $destino)) {
        return ['sucesso'=>false, 'erro'=>'Não foi possível mover o arquivo'];
    }

    // Salva o caminho no banco sempre igual
    $caminhoDB = 'PHP/uploads/' . $novoNome;
    $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE id = ?");
    if (!$stmt->execute([$caminhoDB, $userId])) {
        return ['sucesso'=>false, 'erro'=>'Erro ao salvar no banco'];
    }

    return ['sucesso'=>true, 'novaFoto'=>'/' . $caminhoDB . '?t=' . time()];
}


// Busca a URL da foto de perfil do usuário
function buscarFotoPerfil(PDO $pdo, int $userId): string
{
    $stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $defaultUrl = '/PHP/uploads/default.jpg';

    // Caminho físico absoluto para a pasta uploads no servidor
    $uploadsDir = realpath(__DIR__ . '/../uploads');

    if ($user && !empty($user['foto_perfil'])) {
        $caminho_fisico = $uploadsDir . '/' . basename($user['foto_perfil']);

        if (file_exists($caminho_fisico)) {
            return '/PHP/uploads/' . basename($user['foto_perfil']) . '?t=' . time();
        }
    }

    return $defaultUrl . '?t=' . time();
}



// Verifica se já existe username ou email (para validação)
function usuarioExiste(PDO $pdo, string $username, string $email, ?int $excludeId = null): bool
{
    $username = normalizarTexto($username);
    $email = trim($email);

    $sql = "SELECT COUNT(*) FROM users WHERE (username = :username OR email = :email)";
    $params = [':username' => $username, ':email' => $email];

    if ($excludeId !== null) {
        $sql .= " AND id != :excludeId";
        $params[':excludeId'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() > 0;
}

// Cria um novo usuário
function criarUsuario(PDO $pdo, string $username, string $email, string $password, string $tipo = 'user'): int|false
{
    $username = normalizarTexto($username);
    $email = trim($email);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, tipo) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $hash, $tipo])) {
        return (int) $pdo->lastInsertId();
    }

    return false;
}