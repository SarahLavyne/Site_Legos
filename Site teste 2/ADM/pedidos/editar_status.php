<?php
// Este arquivo é incluído por ADM/pedidos/pedidos.php

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedido_id == 0) {
    echo '<div class="alerta-global alerta-erro">ID do pedido não especificado.</div>';
    echo '<a href="adm.php?secao=pedidos" class="btn-secondary">Voltar</a>';
    return;
}

// 1. Busca os dados atuais do pedido e o nome do cliente
$sql_pedido = "
    SELECT p.id, p.total, p.status, u.nome AS nome_cliente
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.id = $pedido_id
";
$resultado = $conn->query($sql_pedido);

if ($resultado->num_rows === 0) {
    echo '<div class="alerta-global alerta-erro">Pedido não encontrado.</div>';
    echo '<a href="adm.php?secao=pedidos" class="btn-secondary">Voltar</a>';
    return;
}

$pedido = $resultado->fetch_assoc();

// Lista de status disponíveis para seleção
$status_opcoes = [
    'pendente' => 'Pagamento Pendente',
    'pago' => 'Pago e Processando',
    'enviado' => 'Enviado',
    'entregue' => 'Entregue',
    'cancelado' => 'Cancelado'
];
?>

<h3>Alterar Status do Pedido #<?php echo $pedido['id']; ?></h3>
<p>Cliente: <strong><?php echo htmlspecialchars($pedido['nome_cliente']); ?></strong> | Total: <strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong></p>

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
        </select>
        <small style="margin-top: 10px;">Status atual: **<?php echo htmlspecialchars($status_opcoes[$pedido['status']]); ?>**</small>
    </div>

    <button type="submit" class="btn-primary" style="margin-top: 25px;">
        <i class="fas fa-save"></i> Salvar Novo Status
    </button>
</form>