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
        font-family: 'Segoe UI', Tahoma, sans-serif;
        padding: 20px;
        background-color: #f8fafc;
    }

    .card-faturamento {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.2);
        margin-bottom: 30px;
    }

    .card-faturamento h1 { font-size: 3rem; margin: 5px 0; }

    .relatorio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .card-grafico {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }

    /* AJUSTE AQUI: Container com altura fixa para o gráfico não esticar */
    .chart-container {
        position: relative;
        height: 300px; /* Define a altura máxima do gráfico */
        width: 100%;
    }

    .card-grafico h3 {
        color: #1e293b;
        margin-bottom: 15px;
        font-size: 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }

    .sem-dados {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-style: italic;
    }
</style>

<div class="adm-relatorios">
    <div class="card-faturamento">
        <span>💰 Faturamento Total Acumulado</span>
        <h1>R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></h1>
    </div>

    <div class="relatorio-grid">
        <div class="card-grafico">
            <h3>🏆 Top 5 Produtos (Qtd)</h3>
            <?php if($tem_dados_produtos): ?>
                <div class="chart-container">
                    <canvas id="graficoProdutos"></canvas>
                </div>
            <?php else: ?>
                <div class="sem-dados">Aguardando vendas...</div>
            <?php endif; ?>
        </div>

        <div class="card-grafico">
            <h3>👥 Maiores Compradores (R$)</h3>
            <?php if($tem_dados_clientes): ?>
                <div class="chart-container">
                    <canvas id="graficoClientes"></canvas>
                </div>
            <?php else: ?>
                <div class="sem-dados">Sem histórico...</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Configuração Global para manter o tamanho
    Chart.defaults.maintainAspectRatio = false;

    <?php if($tem_dados_produtos): ?>
    new Chart(document.getElementById('graficoProdutos'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($nomes_produtos); ?>,
            datasets: [{
                label: 'Unidades',
                data: <?php echo json_encode($qtd_vendas); ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
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
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
    <?php endif; ?>
</script>