<?php

if (!function_exists('criarNotificacao')) {
    require_once __DIR__ . '/notificacoes.php';
}

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/produtos.php';


// Envia uma notificação única sobre promoções ativas por dia.
function notificarPromocoesParaUsuario(int $userId): bool
{
    global $pdo;

    // Verifica se já existe notificação de promoções HOJE
    $sqlExiste = "SELECT id 
                  FROM notificacoes 
                  WHERE user_id = ? 
                  AND tipo = 'promocao'
                  AND DATE(data_criacao) = CURDATE()";

    $stmt = $pdo->prepare($sqlExiste);
    $stmt->execute([$userId]);

    if ($stmt->fetch()) {
        return false; // Já notificou hoje
    }

    // Busca produtos em promoção (da tabela produtos)
    $produtos = buscarProdutosPromocao(); // Deve retornar array de produtos

    if (empty($produtos)) {
        return false; // Nenhuma promoção ativa
    }

    $quantidade = count($produtos);

    // Título e mensagem padrão
    $titulo = "Promoções Ativas!";
    $mensagem = "Temos {$quantidade} produtos com preços promocionais! Confira na loja.";

    criarNotificacao(
        $pdo,
        $userId,
        $titulo,
        $mensagem,
        "promocao"
    );

    return true;
}


?>