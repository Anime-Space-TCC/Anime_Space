<?php
require_once __DIR__ . '/conexao.php';

function getNotificacoes($userId)
{
    global $pdo;

    // =======================
    // 🔹 NOTIFICAÇÕES DE XP
    // =======================
    $xp = $pdo->prepare("
        SELECT titulo, mensagem 
        FROM notificacoes 
        WHERE user_id = ? AND tipo = 'xp' 
        ORDER BY data_criacao DESC 
        LIMIT 10
    ");
    $xp->execute([$userId]);
    $xp = $xp->fetchAll(PDO::FETCH_ASSOC);

    // =======================
    // 🔹 PROMOÇÕES (TABELA PRODUTOS)
    // =======================
        $promo = $pdo->prepare("
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
    $promo->execute();
    $promo = $promo->fetchAll(PDO::FETCH_ASSOC);

    // Adiciona a URL correta para cada promoção
    $promo = array_map(function ($p) {
        return [
            "id" => $p['id'],
            "nome" => $p['nome'],
            "preco" => $p['preco'],
            "preco_promocional" => $p['preco_promocional'],
            "imagem" => $p['imagem'],
            "url" => "../../PHP/user/loja.php?id=" . $p['nome']   
        ];
    }, $promo);


    // =======================
    // 🔹 HISTÓRICO DE COMPRAS
    // =======================
    $hist = $pdo->prepare("
        SELECT pr.nome, pr.preco
        FROM pagamentos p
        JOIN produtos pr ON p.produto_id = pr.id
        WHERE p.user_id = ? AND p.status = 'aprovado'
        ORDER BY p.data_pagamento DESC
        LIMIT 8
    ");
    $hist->execute([$userId]);
    $hist = $hist->fetchAll(PDO::FETCH_ASSOC);

    // =======================
    // 🔹 CONTADOR DE NÃO LIDAS
    // =======================
    $count = $pdo->prepare("
        SELECT COUNT(*) 
        FROM notificacoes 
        WHERE user_id = ? AND lida = 0
    ");
    $count->execute([$userId]);
    $naoLidas = $count->fetchColumn();

    return [
        "xp" => $xp,
        "promocoes" => $promo,
        "historico" => $hist,
        "naoLidas" => $naoLidas
    ];
}

function criarNotificacao($userId, $titulo, $mensagem, $tipo = 'geral', $referenciaId = null)
{
    global $pdo;

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
