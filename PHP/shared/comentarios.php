<?php
// /PHP/shared/comentarios.php

function inserirComentario(PDO $pdo, int $userId, int $episodioId, string $comentario): bool {
    $stmt = $pdo->prepare("
        INSERT INTO comentarios (user_id, episodio_id, comentario, data_comentario)
        VALUES (?, ?, ?, NOW())
    ");
    return $stmt->execute([$userId, $episodioId, $comentario]);
}
