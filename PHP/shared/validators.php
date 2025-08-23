<?php
// /PHP/shared/validators.php

function existeUsuario(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return (bool) $stmt->fetch();
}

function existeEpisodio(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("SELECT id FROM episodios WHERE id = ?");
    $stmt->execute([$id]);
    return (bool) $stmt->fetch();
}
