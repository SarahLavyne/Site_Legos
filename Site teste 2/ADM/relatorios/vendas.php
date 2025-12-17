<?php
if (!isset($conn)) {
    include_once __DIR__ . '/../../conexao.php';
}

$sql_faturamento = "SELECT SUM(total) as total_geral FROM pedidos WHERE status != 'Cancelado'";
$res_fat = $conn->query($sql_faturamento);
$faturamento = $res_fat->fetch_assoc()['total_geral'] ?? 0;

$sql_top_produtos = "
    SELECT pr.nome, SUM(ip.quantidade) as total_vendas 
    FROM itens_pedido ip 
    JOIN produtos pr ON ip.produto_id = pr.id 
    GROUP BY ip.produto_id 
    ORDER BY total_vendas DESC LIMIT 5";
$res_top_p = $conn->query($sql_top_produtos);

$nomes_produtos = [];
$qtd_vendas = [];
while($row = $res_top_p->fetch_assoc()){
    $nomes_produtos[] = $row['nome'];
    $qtd_vendas[] = (int)$row['total_vendas'];
}

$sql_top_clientes = "
    SELECT u.nome, SUM(p.total) as total_gasto 
    FROM pedidos p 
    JOIN usuarios u ON p.usuario_id = u.id 
    WHERE p.status != 'Cancelado'
    GROUP BY p.usuario_id 
    ORDER BY total_gasto DESC LIMIT 5";
$res_top_c = $conn->query($sql_top_clientes);

$nomes_clientes = [];
$total_clientes = [];
while($row = $res_top_c->fetch_assoc()){
    $nomes_clientes[] = $row['nome'];
    $total_clientes[] = (float)$row['total_gasto'];
}

$tem_dados_produtos = count($nomes_produtos) > 0;
$tem_dados_clientes = count($nomes_clientes) > 0;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .adm-relatorios {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        background-color: #f8fafc;
    }

    .card-faturamento {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.2);
        margin-bottom: 30px;
    }

    .card-faturamento span { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9; }
    .card-faturamento h1 { font-size: 3.5rem; margin: 10px 0; font-weight: 800; }

    .relatorio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .card-grafico {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .card-grafico h3 {
        color: #1e293b;
        margin-bottom: 25px;
        font-size: 1.2rem;
        width: 100%;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
    }

    .sem-dados {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
        font-style: italic;
    }

    canvas { width: 100% !important; max-width: 100%; }
</style>

<div class="adm-relatorios">
    <div class="card-faturamento">
        <span>💰 Faturamento Total Acumulado</span>
        <h1>R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></h1>
        <small>Total bruto de pedidos ativos e entregues</small>
    </div>

    <div class="relatorio-grid">
        <div class="card-grafico">
            <h3>🏆 Produtos Mais Vendidos (Qtd)</h3>
            <?php if($tem_dados_produtos): ?>
                <canvas id="graficoProdutos"></canvas>
            <?php else: ?>
                <div class="sem-dados">Aguardando vendas para gerar estatísticas.</div>
            <?php endif; ?>
        </div>

        <div class="card-grafico">
            <h3>👥 Maiores Compradores (R$)</h3>
            <?php if($tem_dados_clientes): ?>
                <canvas id="graficoClientes"></canvas>
            <?php else: ?>
                <div class="sem-dados">Nenhum histórico de cliente disponível.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
<?php if($tem_dados_produtos): ?>
    new Chart(document.getElementById('graficoProdutos'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($nomes_produtos); ?>,
            datasets: [{
                label: 'Unidades Vendidas',
                data: <?php echo json_encode($qtd_vendas); ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                hoverBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
<?php endif; ?>

<?php if($tem_dados_clientes): ?>
    new Chart(document.getElementById('graficoClientes'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($nomes_clientes); ?>,
            datasets: [{
                data: <?php echo json_encode($total_clientes); ?>,
                backgroundColor: ['#f43f5e', '#3b82f6', '#8b5cf6', '#eab308', '#10b981'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
            }
        }
    });
<?php endif; ?>
</script>