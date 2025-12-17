<?php
// Este arquivo é incluído por ADM/pedidos/pedidos.php

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedido_id == 0) {
    echo '<div class="alerta-global alerta-erro">ID do pedido não especificado.</div>';
    echo '<a href="adm.php?secao=pedidos" class="btn-secondary">Voltar</a>';
    return;
}

// 1. Busca os dados atuais do pedido. 
// CORREÇÃO: Alterado u.usuario_nome para u.nome conforme o erro da imagem
$sql_pedido = "
    SELECT p.id, p.total, p.status, p.codigo_retirada, u.nome AS nome_cliente
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.id = $pedido_id
";
$resultado = $conn->query($sql_pedido);

if (!$resultado || $resultado->num_rows === 0) {
    echo '<div class="alerta-global alerta-erro">Pedido não encontrado.</div>';
    echo '<a href="adm.php?secao=pedidos" class="btn-secondary">Voltar</a>';
    return;
}

$pedido = $resultado->fetch_assoc();

// 2. Lista de status disponíveis (Chaves devem bater exatamente com o que está no banco)
$status_opcoes = [
    'Pendente' => 'Aguardando Pagamento na Loja',
    'Pago (Aguardando Retirada)' => 'Pago - Pronto para Retirada',
    'Entregue' => 'Entregue / Finalizado',
    'Cancelado' => 'Cancelado'
];
?>

<h3>Alterar Status do Pedido #<?php echo $pedido['id']; ?></h3>
<p>
    Cliente: <strong><?php echo htmlspecialchars($pedido['nome_cliente']); ?></strong> | 
    Total: <strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong>
</p>
<p>Código de Retirada: <strong style="color: #007bff;"><?php echo $pedido['codigo_retirada']; ?></strong></p>

<hr>

<form action="../processa_adm.php" method="POST" class="form-admin">
    <input type="hidden" name="acao" value="editar_status_pedido">
    <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">

    <div class="form-group" style="max-width: 400px;">
        <label for="status">Novo Status:</label>
        <select id="status" name="status" required>
            <?php foreach ($status_opcoes as $valor => $label): ?>
                <option value="<?php echo $valor; ?>" 
                        <?php echo ($pedido['status'] === $valor ? 'selected' : ''); ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
            
            <?php 
            // CORREÇÃO: Se o status atual não estiver no array, exibe ele para evitar o erro de "Undefined array key"
            if (!array_key_exists($pedido['status'], $status_opcoes) && !empty($pedido['status'])): ?>
                <option value="<?php echo htmlspecialchars($pedido['status']); ?>" selected>
                    <?php echo htmlspecialchars($pedido['status']); ?> (Atual)
                </option>
            <?php endif; ?>
        </select>

        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
            Status atual: <strong><?php echo htmlspecialchars($pedido['status'] ?: 'Não definido'); ?></strong>
        </div>
    </div>

    <button type="submit" class="btn-primary" style="margin-top: 25px;">
        Salvar Novo Status
    </button>
    <a href="../adm.php?secao=pedidos" class="btn-secondary">Cancelar</a>
</form> 