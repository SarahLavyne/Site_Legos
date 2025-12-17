<?php
session_start();
include '../conexao.php';

// 1. Verifica se existem dados da sessão do checkout
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['temp_codigo'])) {
    header("Location: ../CARRINHO/carrinho.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$total = $_SESSION['temp_total'];
$codigo_retirada = $_SESSION['temp_codigo'];
$metodo_pagamento = $_POST['metodo'] ?? 'loja';
$itens_selecionados = $_SESSION['temp_itens']; // IDs da tabela 'carrinho'

// Define o status inicial
$status = ($metodo_pagamento === 'pix_online') ? 'Pago (Aguardando Retirada)' : 'Pendente (Pagar na Loja)';

// 2. INSERIR NA TABELA DE PEDIDOS
$sql_pedido = "INSERT INTO pedidos (usuario_id, total, metodo_pagamento, codigo_retirada, status, data_pedido) 
               VALUES ('$usuario_id', '$total', '$metodo_pagamento', '$codigo_retirada', '$status', NOW())";

if ($conn->query($sql_pedido) === TRUE) {
    $pedido_id = $conn->insert_id; // Pega o ID do pedido que acabou de ser criado

    // 3. SALVAR ITENS NA TABELA 'itens_pedido' PARA O RELATÓRIO
    // Buscamos os detalhes dos produtos que estão no carrinho agora para registrar o histórico
    $ids_string = implode(',', array_map('intval', $itens_selecionados));
    $sql_busca_itens = "SELECT produto_id, quantidade, preco FROM carrinho 
                        INNER JOIN produtos ON carrinho.produto_id = produtos.id 
                        WHERE carrinho.id IN ($ids_string)";
    
    $res_itens = $conn->query($sql_busca_itens);

    if ($res_itens) {
        while ($item = $res_itens->fetch_assoc()) {
            $prod_id = $item['produto_id'];
            $qtd = $item['quantidade'];
            $preco_un = $item['preco'];

            // Insere na sua tabela de itens_pedido
            $sql_insere_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) 
                                VALUES ('$pedido_id', '$prod_id', '$qtd', '$preco_un')";
            $conn->query($sql_insere_item);
        }
    }

    // 4. REMOVER ITENS DO CARRINHO (Apenas os que foram comprados)
    $sql_limpar_carrinho = "DELETE FROM carrinho WHERE id IN ($ids_string) AND usuario_id = '$usuario_id'";
    $conn->query($sql_limpar_carrinho);

    // 5. Limpar variáveis temporárias da sessão
    unset($_SESSION['temp_total']);
    unset($_SESSION['temp_itens']);
    unset($_SESSION['temp_codigo']);
} else {
    die("Erro ao processar pedido: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Brick-Up</title>
    <link rel="stylesheet" href="../INICIO/styles.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .sucesso-container { 
            max-width: 600px; 
            margin: 50px auto; 
            text-align: center; 
            padding: 40px; 
            border-radius: 20px; 
            background: #fff; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .icon-check { font-size: 50px; color: #28a745; margin-bottom: 20px; }
        .codigo-box { 
            font-size: 2.5rem; 
            font-weight: bold; 
            color: #007bff; 
            background: #e7f3ff; 
            border: 2px dashed #007bff; 
            padding: 20px; 
            margin: 25px 0; 
            letter-spacing: 5px;
            border-radius: 10px;
        }
        .instrucoes { 
            text-align: left; 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 12px; 
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }
        .btn-confirmar {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
    </style>
</head>
<body>
    <div class="sucesso-container">
        <div class="icon-check">✅</div>
        <h1 style="color: #1a202c; margin-bottom: 10px;">Pedido Realizado!</h1>
        <p style="color: #64748b;">Seu pedido foi registrado e os gráficos de vendas atualizados.</p>

        <p style="margin-top: 20px; font-weight: 600;">Código de retirada na loja:</p>
        <div class="codigo-box">
            <?php echo $codigo_retirada; ?>
        </div>

        <div class="instrucoes">
            <h4 style="margin-top: 0; color: #334155;">📋 Próximos passos:</h4>
            <ul style="color: #475569; line-height: 1.6;">
                <li><strong>Pagamento:</strong> <?php echo ($metodo_pagamento === 'pix_online') ? 'PIX recebido. Vá direto ao balcão.' : 'Pague no balcão ao retirar (Cartão/PIX).'; ?></li>
                <li><strong>Retirada:</strong> Disponível hoje até as 16:00.</li>
                <li><strong>Validação:</strong> Mostre este código ao atendente.</li>
            </ul>
        </div>

        <div style="margin-top: 35px; display: flex; gap: 15px; justify-content: center;">
            <a href="../PERFIL/meus_pedidos.php" class="btn-secondary btn-confirmar" style="background: #edf2f7; color: #4a5568;">Meus Pedidos</a>
            <a href="../INICIO/index.php" class="btn-primary btn-confirmar" style="background: #007bff; color: white;">Voltar à Loja</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>