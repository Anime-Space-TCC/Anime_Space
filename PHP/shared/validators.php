<?php

// ==========================
// Verficadores de existência
// ==========================
// Verifica se um usuário com o ID fornecido existe na tabela 'users'.
function existeUsuario(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return (bool) $stmt->fetch();
}

// Verifica se um episódio com o ID fornecido existe na tabela 'episodios'.
function existeEpisodio(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare("SELECT id FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    return (bool) $stmt->fetch();
}
