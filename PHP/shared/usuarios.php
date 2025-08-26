<?php
require_once __DIR__ . '/conexao.php';

/**
 * =========================
 * FUNÇÕES DE USUÁRIOS
 * =========================
 */

/**
 * Busca um usuário pelo ID
 */
function buscarUsuarioPorId(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Atualiza username e email de um usuário
 */
function atualizarUsuario(PDO $pdo, int $id, string $username, string $email): bool {
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    return $stmt->execute([$username, $email, $id]);
}

/**
 * Atualiza a foto de perfil do usuário
 */
function atualizarFotoPerfil(int $userId, array $file) {
    global $pdo;

    // Pasta física onde os arquivos serão salvos
    $pastaFisica = __DIR__ . '/../uploads/perfil/';
    if (!is_dir($pastaFisica)) mkdir($pastaFisica, 0777, true);

    // Caminho público dinâmico relativo ao projeto
    $projetoBase = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__ . '/..'));
    $pastaPublica = $projetoBase . '/uploads/perfil/';

    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, ['jpg','jpeg','png'])) return "Erro: Apenas JPG/PNG.";
    if ($file['size'] > 500000) return "Erro: Máx. 500KB.";

    $nomeArquivo = $userId.'_'.time().'.'.$extensao;
    $destinoFisico = $pastaFisica . $nomeArquivo;
    $destinoPublico = $pastaPublica . $nomeArquivo;

    if (!move_uploaded_file($file['tmp_name'], $destinoFisico)) return "Erro ao mover arquivo.";

    $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE id = ?");
    if (!$stmt->execute([$destinoPublico, $userId])) return "Erro ao salvar caminho no banco.";

    return true;
}

/**
 * Busca a foto de perfil do usuário
 */
function buscarFotoPerfil(int $userId): string {
    global $pdo;

    $stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $foto = $stmt->fetchColumn();

    if ($foto && file_exists($_SERVER['DOCUMENT_ROOT'] . str_replace('/', DIRECTORY_SEPARATOR, $foto))) {
        return $foto;
    }

    // fallback automático
    $projetoBase = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__ . '/..'));
    return $projetoBase . '/img/default.jpg';
}

/**
 * Verifica se já existe username ou email
 */
function usuarioExiste(string $username, string $email): bool {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Cria um novo usuário
 */
function criarUsuario(string $username, string $email, string $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $hash])) {
        return $pdo->lastInsertId();
    }

    return false;
}
