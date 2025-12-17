<?php
$status_pedidos = [
    'pendente' => ['label' => 'Pagamento Pendente', 'class' => 'status-pendente'],
    'pago' => ['label' => 'Pago e Processando', 'class' => 'status-pago'],
    'enviado' => ['label' => 'Enviado', 'class' => 'status-enviado'],
    'entregue' => ['label' => 'Entregue', 'class' => 'status-entregue'],
    'cancelado' => ['label' => 'Cancelado', 'class' => 'status-cancelado']
];

$sql_pedidos = "
    SELECT 
        p.id, 
        u.nome AS nome_cliente, 
        p.data_pedido, 
        p.total, 
        p.status
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.data_pedido DESC, FIELD(p.status, 'pendente', 'pago', 'enviado', 'entregue', 'cancelado')
";
$resultado_pedidos = $conn->query($sql_pedidos);
?>

<div class="conteudo-header">
    <h2>📝 Gerenciamento de Pedidos</h2>
</div>

<hr>

<?php if ($resultado_pedidos->num_rows > 0): ?>

    <table class="tabela-admin">
        <thead>
            <tr>
                <th>ID do Pedido</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($pedido = $resultado_pedidos->fetch_assoc()): 
                // Define a classe CSS com base no status
                $status_info = $status_pedidos[$pedido['status']] ?? ['label' => 'Desconhecido', 'class' => 'status-desconhecido'];
            ?>
            <tr>
                <td><?php echo $pedido['id']; ?></td>
                <td><?php echo htmlspecialchars($pedido['nome_cliente']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                <td>
                    <span class="status-tag <?php echo $status_info['class']; ?>">
                        <?php echo $status_info['label']; ?>
                    </span>
                </td>
                <td class="coluna-acoes">
                   <a href="adm.php?secao=pedidos&acao=editar_status&id=<?php echo $pedido['id']; ?>" class="btn-editar">
                        Editar Status
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="alerta-global alerta-aviso">
        Não há pedidos registrados no momento.
    </div>
<?php endif; ?>