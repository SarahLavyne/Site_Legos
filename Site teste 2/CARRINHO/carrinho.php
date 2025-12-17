<?php
session_start();
include '../conexao.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT c.id AS carrinho_id, c.quantidade, 
               p.nome, p.preco, p.imagem_url, p.id AS produto_id
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = '$usuario_id'";

$resultado = $conn->query($sql);

$produtos_no_carrinho = [];
if ($resultado->num_rows > 0) {
    while($item_carrinho = $resultado->fetch_assoc()) {
        $produtos_no_carrinho[] = $item_carrinho['produto_id'];
    }
    $resultado->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - Brick-Up</title>
    <link rel="stylesheet" href="../INICIO/styles.css"> 
    <link rel="stylesheet" href="carrinho.css"> 
    <style>
        .link-produto-nome { text-decoration: none; color: #333; font-weight: 600; }
        .link-produto-nome:hover { color: #007bff; text-decoration: underline; }
        .carrinho-resumo { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: right; }
        .check-item { transform: scale(1.5); cursor: pointer; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo"><h1>🧱 BRICK-UP</h1></div>
            <div class="header-actions">
                <a href="../INICIO/index.php" class="btn-secondary">Continuar Comprando</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h2>Seu Carrinho de Compras</h2>

        <?php if ($resultado->num_rows > 0): ?>
            <form action="../CHECKOUT/checkout.php" method="POST" id="form-checkout">
                <div class="carrinho-tabela-wrapper">
                    <table class="carrinho-tabela">
                        <thead>
                            <tr>
                                <th>Selecionar</th>
                                <th>Produto</th>
                                <th>Preço Unit.</th>
                                <th>Quantidade</th>
                                <th>Subtotal</th>
                                <th>Remover</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $resultado->fetch_assoc()): 
                                $subtotal = $item['quantidade'] * $item['preco'];
                            ?>
                            <tr class="linha-produto">
                                <td style="text-align: center;">
                                    <input type="checkbox" name="selecionados[]" value="<?php echo $item['carrinho_id']; ?>" class="check-item" checked>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="../INICIO/imagens/<?php echo htmlspecialchars($item['imagem_url']); ?>" width="60" style="border-radius: 4px;">
                                        <a href="../INICIO/detalhes_produto.php?id=<?php echo $item['produto_id']; ?>" class="link-produto-nome">
                                            <?php echo htmlspecialchars($item['nome']); ?>
                                        </a>
                                    </div>
                                </td>
                                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td data-carrinho-id="<?php echo $item['carrinho_id']; ?>" data-preco="<?php echo $item['preco']; ?>">
                                    <div class="quantidade-controle">
                                    <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="qty-input" style="width: 45px; text-align: center;" readonly>
                                    </div>
                                </td>
                                <td class="subtotal-display" data-valor="<?php echo $subtotal; ?>">
                                    R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                                </td>
                                <td>
                                    <button type="button" class="btn-remover" onclick="removerItem(<?php echo $item['carrinho_id']; ?>)">X</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="carrinho-resumo">
                    <h3>Total Selecionado: <strong id="total-selecionado">R$ 0,00</strong></h3>
                    <button type="submit" class="btn-primary btn-large">Finalizar Itens Selecionados</button>
                </div>
            </form>

            <?php 
            $resultado->data_seek(0);
            while($item = $resultado->fetch_assoc()): 
            ?>
                <form id="form-remover-<?php echo $item['carrinho_id']; ?>" action="processa_carrinho.php" method="POST" style="display:none;">
                    <input type="hidden" name="carrinho_id" value="<?php echo $item['carrinho_id']; ?>">
                    <input type="hidden" name="acao" value="remover">
                </form>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="alerta-global alerta-aviso">
                Seu carrinho está vazio. <a href="../INICIO/index.php">Clique aqui para explorar produtos!</a>
            </div>
        <?php endif; ?>

        <section class="sugestoes-section" style="margin-top: 50px;">
            <h2 class="section-title">Você também pode gostar...</h2>
            <div class="products-grid">
                <?php
                $ids_para_excluir = !empty($produtos_no_carrinho) ? implode(',', $produtos_no_carrinho) : '0';
                $sql_sugestoes = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id NOT IN ($ids_para_excluir) ORDER BY RAND() LIMIT 4";
                $res_sug = $conn->query($sql_sugestoes);
                while($sug = $res_sug->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../INICIO/imagens/<?php echo $sug['imagem_url']; ?>" alt="<?php echo $sug['nome']; ?>">
                        <h4><?php echo $sug['nome']; ?></h4>
                        <p>R$ <?php echo number_format($sug['preco'], 2, ',', '.'); ?></p>
                        <a href="../INICIO/detalhes.php?id=<?php echo $sug['id']; ?>" class="btn-secondary">Ver Mais</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <script>
        function atualizarTotal() {
            let total = 0;
            document.querySelectorAll('.check-item:checked').forEach(checkbox => {
                const linha = checkbox.closest('tr');
                const subtotal = parseFloat(linha.querySelector('.subtotal-display').getAttribute('data-valor'));
                total += subtotal;
            });
            document.getElementById('total-selecionado').innerText = total.toLocaleString('pt-br', {style: 'currency', currency: 'BRL'});
        }

        document.querySelectorAll('.check-item').forEach(el => {
            el.addEventListener('change', atualizarTotal);
        });

        function removerItem(id) {
            if(confirm('Remover este item do carrinho?')) {
                document.getElementById('form-remover-' + id).submit();
            }
        }

        atualizarTotal();
    </script>
</body>
</html>
<?php $conn->close(); ?>