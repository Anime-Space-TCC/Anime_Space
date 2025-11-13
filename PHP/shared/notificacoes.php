<?php
require_once __DIR__ . '/conexao.php';

function getNotificacoes($userId) {
    global $pdo;

    // XP
    $xp = $pdo->prepare("SELECT titulo, mensagem, data_criacao 
                         FROM notificacoes 
                         WHERE user_id = ? AND tipo = 'xp' 
                         ORDER BY data_criacao DESC 
                         LIMIT 10");
    $xp->execute([$userId]);
    $xp = $xp->fetchAll(PDO::FETCH_ASSOC);

    // PROMOÇÕES
    $promo = $pdo->prepare("SELECT n.titulo, n.mensagem AS url, p.imagem 
                            FROM notificacoes n
                            LEFT JOIN produtos p ON n.referencia_id = p.id
                            WHERE n.user_id = ? AND n.tipo = 'promocao'
                            ORDER BY n.data_criacao DESC
                            LIMIT 6");
    $promo->execute([$userId]);
    $promo = $promo->fetchAll(PDO::FETCH_ASSOC);

    // HISTÓRICO DE COMPRAS
    $hist = $pdo->prepare("SELECT p.data_pagamento, pr.nome, pr.preco
                           FROM pagamentos p
                           JOIN produtos pr ON p.produto_id = pr.id
                           WHERE p.user_id = ? AND p.status = 'aprovado'
                           ORDER BY p.data_pagamento DESC
                           LIMIT 8");
    $hist->execute([$userId]);
    $hist = $hist->fetchAll(PDO::FETCH_ASSOC);

    // CONTAR NÃO LIDAS
    $count = $pdo->prepare("SELECT COUNT(*) 
                            FROM notificacoes 
                            WHERE user_id = ? AND lida = 0");
    $count->execute([$userId]);
    $naoLidas = $count->fetchColumn();

    return [
        "xp" => $xp,
        "promocoes" => $promo,
        "historico" => $hist,
        "naoLidas" => $naoLidas
    ];
}
