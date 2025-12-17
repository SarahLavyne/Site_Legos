<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['selecionados'])) {
    header("Location: ../CARRINHO/carrinho.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$selecionados = $_POST['selecionados']; // IDs do carrinho selecionados
$ids_string = implode(',', array_map('intval', $selecionados));

// Busca os detalhes apenas dos produtos selecionados
$sql = "SELECT c.quantidade, p.nome, p.preco 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id 
        WHERE c.id IN ($ids_string) AND c.usuario_id = '$usuario_id'";

$resultado = $conn->query($sql);
$total_final = 0;
$itens = [];

while ($row = $resultado->fetch_assoc()) {
    $total_final += ($row['preco'] * $row['quantidade']);
    $itens[] = $row;
}

// Gera um código de retirada simulado (Ex: BRICK-7492)
$codigo_retirada = "BRICK-" . rand(1000, 9999);
$_SESSION['temp_total'] = $total_final;
$_SESSION['temp_itens'] = $selecionados;
$_SESSION['temp_codigo'] = $codigo_retirada;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - Brick-Up</title>
    <link rel="stylesheet" href="../INICIO/styles.css">
    <style>
        .checkout-container { max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background: #fff; }
        .pagamento-opcoes { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        .opcao-card { border: 2px solid #eee; padding: 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .opcao-card:hover { border-color: #007bff; background: #f0f7ff; }
        .opcao-card input { margin-right: 10px; }
        .pix-area { display: none; text-align: center; margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .qr-code-simulado { width: 200px; height: 200px; background: #333; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: #fff; }
        .copia-cola { width: 100%; padding: 10px; background: #eee; border: 1px dashed #999; font-family: monospace; font-size: 0.8rem; word-break: break-all; }
        .alerta-retirada { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin-top: 20px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Resumo do Pedido</h2>
        <hr>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($itens as $item): ?>
                <li style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span><?php echo $item['quantidade']; ?>x <?php echo htmlspecialchars($item['nome']); ?></span>
                    <strong>R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
        <div style="font-size: 1.5rem; text-align: right; margin-top: 20px;">
            Total: <strong>R$ <?php echo number_format($total_final, 2, ',', '.'); ?></strong>
        </div>

        <h3>Escolha como pagar:</h3>
        <form action="confirmar_pedido.php" method="POST" id="form-pagamento">
            <div class="pagamento-opcoes">
                <label class="opcao-card">
                    <input type="radio" name="metodo" value="pix_online" onclick="mostrarPix()">
                    <strong>Pagar agora com PIX (Online)</strong>
                    <p style="font-size: 0.8rem; color: #666;">Liberação imediata para retirada.</p>
                </label>

                <label class="opcao-card">
                    <input type="radio" name="metodo" value="loja" onclick="esconderPix()">
                    <strong>Pagar na retirada (Loja Física)</strong>
                    <p style="font-size: 0.8rem; color: #666;">Cartão de Crédito, Débito ou PIX.</p>
                </label>
            </div>

            <div id="area-pix" class="pix-area">
                <h4>Escaneie o QR Code</h4>
                <div class="qr-code-simulado">
                    [ QR CODE SIMULADO ]
                </div>
                <p>Ou use o código Copia e Cola:</p>
                <div class="copia-cola" id="pix-code">
                    00020126580014BR.GOV.BCB.PIX0136BRICKUP-RANDOM-KEY-<?php echo time(); ?>5204000053039865405<?php echo $total_final; ?>5802BR5913BRICKUP LOJA6008RIO BRANCO62070503***6304
                </div>
                <button type="button" class="btn-secondary" style="margin-top: 10px; width: auto; padding: 5px 10px;" onclick="copiarPix()">Copiar Código</button>
            </div>

            <div class="alerta-retirada">
                ℹ️ Lembre-se: Retiradas até as <strong>16:00</strong>. Após este horário, o agendamento será cancelado.<br> Endereço: Bairro Villa da Alegria, Rua Peixinho, Número 532
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 20px; padding: 15px;">Confirmar e Gerar Código de Retirada</button>
        </form>
    </div>

    <script>
        function mostrarPix() {
            document.getElementById('area-pix').style.display = 'block';
        }
        function esconderPix() {
            document.getElementById('area-pix').style.display = 'none';
        }
        function copiarPix() {
            const code = document.getElementById('pix-code').innerText;
            navigator.clipboard.writeText(code);
            alert('Código PIX copiado!');
        }
    </script>
</body>
</html>