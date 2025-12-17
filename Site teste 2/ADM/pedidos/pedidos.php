<?php
$sql_pedidos = "SELECT p.*, u.nome FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_pedido DESC";
$resultado_pedidos = $conn->query($sql_pedidos);
?>

<table class="tabela-admin">
    <thead>
        <tr>
            <th>ID do Pedido</th>
            <th>Cliente</th>
            <th>Data</th>
            <th>Total</th>
            <th>Status</th> <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while($ped = $resultado_pedidos->fetch_assoc()): ?>
        <tr>
            <td><?php echo $ped['id']; ?></td>
            <td><?php echo htmlspecialchars($ped['nome']); ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($ped['data_pedido'])); ?></td>
            <td>R$ <?php echo number_format($ped['total'], 2, ',', '.'); ?></td>
            
            <td>
                <span class="badge-status">
                    <?php echo htmlspecialchars($ped['status'] ?: 'Pendente'); ?>
                </span>
            </td>

            <td>
                <a href="pedidos/editar_status.php?secao=pedidos&acao=editar_status&id=<?php echo $ped['id']; ?>" class="btn-editar">
                    Editar Status
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>