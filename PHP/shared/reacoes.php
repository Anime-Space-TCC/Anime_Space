<?php
require_once __DIR__ . '/conexao.php';

// =========================
// FUNÇÕES DE REAÇÕES
// =========================

// Busca a reação atual de um usuário em um episódio
function buscarReacaoUsuario(PDO $pdo, int $userId, int $episodioId): ?array
{
    $stmt = $pdo->prepare("
        SELECT * 
        FROM episodio_reacoes 
        WHERE user_id = ? AND episodio_id = ?
        LIMIT 1
    ");
    $stmt->execute([$userId, $episodioId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// Salva ou atualiza uma reação de usuário
function salvarOuAtualizarReacao(PDO $pdo, int $userId, int $episodioId, string $reacao): ?array
{
    $existente = buscarReacaoUsuario($pdo, $userId, $episodioId);

    if ($existente) {
        if ($existente['reacao'] === $reacao) {
            // Remove reação se clicar novamente na mesma
            $stmt = $pdo->prepare("DELETE FROM episodio_reacoes WHERE id = ?");
            $stmt->execute([$existente['id']]);
        } else {
            // Atualiza para a nova reação
            $stmt = $pdo->prepare("UPDATE episodio_reacoes SET reacao = ? WHERE id = ?");
            $stmt->execute([$reacao, $existente['id']]);
        }
    } else {
        // Nova reação
        $stmt = $pdo->prepare("
            INSERT INTO episodio_reacoes (user_id, episodio_id, reacao) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $episodioId, $reacao]);
    }

    // Retorna a reação atualizada ou null se foi removida
    return buscarReacaoUsuario($pdo, $userId, $episodioId);
}

// Conta todas as reações de um episódio
function contarReacoesEpisodio(PDO $pdo, int $episodioId): array
{
    $stmt = $pdo->prepare("
        SELECT reacao, COUNT(*) as total
        FROM episodio_reacoes
        WHERE episodio_id = ?
        GROUP BY reacao
    ");
    $stmt->execute([$episodioId]);

    $contagens = ['like' => 0, 'dislike' => 0];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $tipo = $linha['reacao'];
        $contagens[$tipo] = (int) $linha['total'];
    }

    return $contagens;
}
