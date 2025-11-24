<?php
require_once __DIR__ . '/conexao.php';

// =======================
// Funções de Notificações
// =======================
function getNotificacoes(int $userId): array
{
    global $pdo;

    // =======================
    // NOTIFICAÇÕES DE XP
    // =======================
    $xpQuery = $pdo->prepare("
        SELECT titulo, mensagem 
        FROM notificacoes 
        WHERE user_id = ? AND tipo = 'xp' 
        ORDER BY data_criacao DESC 
        LIMIT 10
    ");
    $xpQuery->execute([$userId]);
    $xp = $xpQuery->fetchAll(PDO::FETCH_ASSOC);

    // =======================
    // PROMOÇÕES
    // =======================
    $promoQuery = $pdo->prepare("
        SELECT 
            id,
            nome,
            preco,
            preco_promocional,
            imagem
        FROM produtos
        WHERE promocao = 1 AND ativo = 1
        ORDER BY data_atualizacao DESC
        LIMIT 6
    ");
    $promoQuery->execute();
    $promoList = $promoQuery->fetchAll(PDO::FETCH_ASSOC);
    $promocoes = array_map(function ($p) {
        return [
            "id" => $p['id'],
            "nome" => $p['nome'],
            "preco" => $p['preco'],
            "preco_promocional" => $p['preco_promocional'],
            "imagem" => $p['imagem'],
            "url" => "../../PHP/user/loja.php?id=" . $p['id']
        ];
    }, $promoList);

    // =======================
    // HISTÓRICO DE COMPRAS
    // =======================
    $histQuery = $pdo->prepare("
        SELECT pr.nome, pr.preco
        FROM pagamentos p
        JOIN produtos pr ON p.produto_id = pr.id
        WHERE p.user_id = ? AND p.status = 'aprovado'
        ORDER BY p.data_pagamento DESC
        LIMIT 8
    ");
    $histQuery->execute([$userId]);
    $hist = $histQuery->fetchAll(PDO::FETCH_ASSOC);

    // ============================
    // Conta notificações não lidas
    // ============================
    $countQuery = $pdo->prepare("
        SELECT COUNT(*) 
        FROM notificacoes 
        WHERE user_id = ? AND lida = 0
    ");
    $countQuery->execute([$userId]);
    $naoLidas = $countQuery->fetchColumn();

    return [
        "xp" => $xp,
        "promocoes" => $promocoes,
        "historico" => $hist,
        "naoLidas" => $naoLidas
    ];
}

// =====================
// Criar uma notificação
// =====================
function criarNotificacao(PDO $pdo, int $userId, string $titulo, string $mensagem, string $tipo = 'geral', ?int $referenciaId = null): void
{
    $stmt = $pdo->prepare("
        INSERT INTO notificacoes 
        (user_id, titulo, mensagem, tipo, referencia_id, lida, data_criacao) 
        VALUES (?, ?, ?, ?, ?, 0, NOW())
    ");

    $stmt->execute([
        $userId,
        $titulo,
        $mensagem,
        $tipo,
        $referenciaId
    ]);
}
