<?php

if (!isset($conn)) {
    include_once __DIR__ . '/../../conexao.php';
}

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedido_id == 0) {
    echo '<div class="card-status erro">ID do pedido não especificado.</div>';
    return;
}

$sql_pedido = "SELECT p.*, u.nome AS nome_cliente FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = $pedido_id";
$resultado = $conn->query($sql_pedido);
$pedido = $resultado->fetch_assoc();

$status_opcoes = [
    'Pendente' => 'Aguardando Pagamento na Loja',
    'Pago (Aguardando Retirada)' => 'Pago - Pronto para Retirada',
    'Entregue' => 'Entregue / Finalizado',
    'Cancelado' => 'Cancelado'
];
?>

<style>
    .adm-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        max-width: 700px;
        margin: 20px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-edit {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 15px;
    }

    .header-edit h3 { margin: 0; color: #1a202c; }

    .btn-voltar {
        text-decoration: none;
        color: #4a5568;
        font-size: 0.9rem;
        padding: 8px 15px;
        background: #edf2f7;
        border-radius: 6px;
        transition: 0.3s;
    }

    .btn-voltar:hover { background: #e2e8f0; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .info-item label { display: block; font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: bold; }
    .info-item span { font-size: 1.1rem; color: #1e293b; font-weight: 600; }

    .codigo-destaque {
        grid-column: span 2;
        background: #ebf8ff;
        border: 2px dashed #4299e1;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
    }

    .codigo-destaque span { color: #2b6cb0; font-family: monospace; font-size: 1.5rem; }

    .form-edit { margin-top: 20px; }

    .form-group { margin-bottom: 20px; }

    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }

    .select-modern {
        width: 100%;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        outline: none;
        transition: 0.3s;
        background: #fff;
    }

    .select-modern:focus { border-color: #4299e1; }

    .footer-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-salvar {
        background: #38a169;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        flex: 1;
        transition: 0.3s;
    }

    .btn-salvar:hover { background: #2f855a; transform: translateY(-2px); }

    .btn-cancelar {
        background: #e53e3e;
        color: white;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: bold;
        text-align: center;
        flex: 1;
        transition: 0.3s;
    }

    .btn-cancelar:hover { background: #c53030; }
</style>

<div class="adm-container">
    <div class="header-edit">
        <h3>Editar Pedido #<?php echo $pedido['id']; ?></h3>
        <a href="../adm.php?secao=pedidos" class="btn-voltar">← Voltar</a>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <label>Cliente</label>
            <span><?php echo htmlspecialchars($pedido['nome_cliente']); ?></span>
        </div>
        <div class="info-item">
            <label>Valor Total</label>
            <span style="color: #2f855a;">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
        </div>
        <div class="codigo-destaque">
            <label>Código de Retirada</label>
            <span><?php echo $pedido['codigo_retirada']; ?></span>
        </div>
    </div>

    <form action="../processa_adm.php" method="POST" class="form-edit">
        <input type="hidden" name="acao" value="editar_status_pedido">
        <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">

        <div class="form-group">
            <label for="status">Atualizar Situação</label>
            <select id="status" name="status" class="select-modern" required>
                <?php foreach ($status_opcoes as $valor => $label): ?>
                    <option value="<?php echo $valor; ?>" <?php echo ($pedido['status'] === $valor ? 'selected' : ''); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="footer-buttons">
            <button type="submit" class="btn-salvar">Confirmar Alteração</button>
            <a href="../adm.php?secao=pedidos" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>