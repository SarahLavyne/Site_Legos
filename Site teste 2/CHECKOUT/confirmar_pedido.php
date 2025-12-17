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
$itens_selecionados = $_SESSION['temp_itens']; // IDs do carrinho para remover

// Define o status inicial
$status = ($metodo_pagamento === 'pix_online') ? 'Pago (Aguardando Retirada)' : 'Pendente (Pagar na Loja)';

// 2. INSERIR NA TABELA DE PEDIDOS
// Certifique-se que sua tabela pedidos tenha essas colunas
$sql_pedido = "INSERT INTO pedidos (usuario_id, total, metodo_pagamento, codigo_retirada, status, data_pedido) 
               VALUES ('$usuario_id', '$total', '$metodo_pagamento', '$codigo_retirada', '$status', NOW())";
if ($conn->query($sql_pedido) === TRUE) {
    $pedido_id = $conn->insert_id;

    // 3. REMOVER ITENS DO CARRINHO (Apenas os que foram comprados)
    $ids_remover = implode(',', array_map('intval', $itens_selecionados));
    $sql_limpar_carrinho = "DELETE FROM carrinho WHERE id IN ($ids_remover) AND usuario_id = '$usuario_id'";
    $conn->query($sql_limpar_carrinho);

    // 4. Limpar variáveis temporárias da sessão
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
        .sucesso-container { max-width: 600px; margin: 50px auto; text-align: center; padding: 30px; border: 2px solid #28a745; border-radius: 15px; background: #f4fff4; }
        .codigo-box { font-size: 2.5rem; font-weight: bold; color: #007bff; background: #fff; border: 2px dashed #007bff; padding: 20px; margin: 20px 0; letter-spacing: 5px; }
        .instrucoes { text-align: left; background: #fff; padding: 20px; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="sucesso-container">
        <h1 style="color: #28a745;">✅ Pedido Realizado!</h1>
        <p>Seu pedido foi registrado com sucesso em nosso sistema.</p>

        <p>Apresente o código abaixo na loja para retirada:</p>
        <div class="codigo-box">
            <?php echo $codigo_retirada; ?>
        </div>

        <div class="instrucoes">
            <h4>Próximos passos:</h4>
            <ul>
                <li><strong>Pagamento:</strong> <?php echo ($metodo_pagamento === 'pix_online') ? 'Identificamos seu PIX. Vá direto ao balcão.' : 'Realize o pagamento no balcão (Cartão ou PIX).'; ?></li>
                <li><strong>Horário:</strong> Retiradas permitidas até as 16h de hoje.</li>
                <li><strong>Documento:</strong> Leve um documento com foto.</li>
            </ul>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px; justify-content: center;">
            <a href="../PERFIL/meus_pedidos.php" class="btn-secondary">Ver Meus Pedidos</a>
            <a href="../INICIO/index.php" class="btn-primary">Voltar para a Loja</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>