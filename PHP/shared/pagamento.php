<?php
require_once __DIR__ . '/conexao.php';

//======================================
// Registra um novo pagamento no sistema
//======================================
function registrarPagamento(PDO $pdo, int $user_id, int $produto_id, string $metodo): ?array
{
    // Verifica se o produto existe e está ativo
    $stmt = $pdo->prepare("SELECT nome, preco, sku FROM produtos WHERE id = ? AND ativo = 1");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto)
        return null;

    $valor = $produto['preco'];
    $codigo = strtoupper(uniqid("PGT-"));
    $status = 'pendente';

    // Insere no banco
    $sql = "INSERT INTO pagamentos (user_id, produto_id, valor, metodo, status, codigo_referencia)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $produto_id, $valor, $metodo, $status, $codigo]);

    $pagamento = [
        'codigo' => $codigo,
        'status' => $status,
        'valor' => number_format($valor, 2, ',', '.'),
        'sku' => $produto['sku'],
    ];

    // ==============================================
   // Escolha do método de pagamento (simulado)
   // ==============================================
    if ($metodo === 'pix') {
        $pagamento['tipo'] = 'pix';
    } elseif ($metodo === 'boleto') {
        $pagamento['tipo'] = 'boleto';
        $pagamento['linha_digitavel'] = rand(10000, 99999) . '.' . rand(10000, 99999) . ' ' .
            rand(10000, 99999) . '.' . rand(10000, 99999) . ' ' .
            rand(10000, 99999) . ' 00000000000000';
        $pagamento['vencimento'] = date('d/m/Y', strtotime('+3 days'));
    } elseif ($metodo === 'cartao') {
        $pagamento['tipo'] = 'cartao';
    }

    return $pagamento;
}

// ==============================
// Cancela um pagamento (simulado)
// ==============================
function cancelarPagamento(PDO $pdo, int $user_id, string $codigo_referencia): bool
{
    $stmt = $pdo->prepare("SELECT id, status FROM pagamentos WHERE codigo_referencia = ? AND user_id = ?");
    $stmt->execute([$codigo_referencia, $user_id]);
    $pagamento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pagamento)
        return false;

    if ($pagamento['status'] !== 'pendente')
        return false;

    $stmt = $pdo->prepare("UPDATE pagamentos SET status = 'cancelado', data_cancelamento = NOW() WHERE id = ?");
    $stmt->execute([$pagamento['id']]);

    return true;
}
?>