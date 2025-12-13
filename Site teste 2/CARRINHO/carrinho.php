<?php
session_start();
// O include precisa subir dois níveis: CARRINHO/ -> SITE TESTE 2/
include '../conexao.php'; 

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para login se não estiver logado
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$total_carrinho = 0; // Inicializa o contador total

// 2. Consulta para obter os itens do carrinho e os detalhes do produto
$sql = "SELECT c.id AS carrinho_id, c.quantidade, 
               p.nome, p.preco, p.imagem_url, p.id AS produto_id
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = '$usuario_id'";

$resultado = $conn->query($sql);

// Para a seção de sugestões, precisamos dos IDs de produtos no carrinho, caso haja
$produtos_no_carrinho = [];
if ($resultado->num_rows > 0) {
    // Move o ponteiro de volta ao início para usar os dados no loop principal
    $resultado->data_seek(0); 
    while($item_carrinho = $resultado->fetch_assoc()) {
        $produtos_no_carrinho[] = $item_carrinho['produto_id'];
    }
    // Volta o ponteiro para o início para o loop HTML
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
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            
            <div class="header-actions">
                <span class="user-greeting">
                    Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!
                </span>
                <a href="../INICIO/index.php" class="btn-primary">Produtos</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h2>Seu Carrinho de Compras</h2>

        <div class="carrinho-itens">
            
            <?php if ($resultado->num_rows > 0): ?>

                <div class="carrinho-tabela-wrapper">
                    <table class="carrinho-tabela">
                        <thead>
                            <tr>
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
                                $total_carrinho += $subtotal;
                            ?>
                            
                            <tr>
                                <td>
                                    <img src="../INICIO/imagens/<?php echo htmlspecialchars($item['imagem_url']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" width="50">
                                    <?php echo htmlspecialchars($item['nome']); ?>
                                </td>
                                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                
                                <td data-carrinho-id="<?php echo $item['carrinho_id']; ?>" data-preco="<?php echo $item['preco']; ?>">
                                    <div class="quantidade-controle">
                                        <button type="button" class="btn-qty-minus">-</button>
                                        <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" 
                                               class="qty-input" required>
                                        <button type="button" class="btn-qty-plus">+</button>
                                    </div>
                                </td>
                                
                                <td class="subtotal-display">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                
                                <td>
                                    <form action="processa_carrinho.php" method="POST">
                                        <input type="hidden" name="carrinho_id" value="<?php echo $item['carrinho_id']; ?>">
                                        <button type="submit" name="acao" value="remover" class="btn-remover">X</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="carrinho-resumo">
                    <h3>Total da Compra: <strong>R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></strong></h3>
                    <a href="../CHECKOUT/checkout.php" class="btn-primary btn-checkout">Finalizar Compra</a>
                </div>

            <?php else: ?>
                <p>Seu carrinho está vazio. <a href="../INICIO/index.php">Clique aqui para explorar produtos!</a></p>
            <?php endif; ?>

        </div>
        
        <section class="sugestoes-section">
            <h2 class="section-title">Você também pode gostar...</h2>
            <div class="sugestoes-grid">
                <?php
                // --- Lógica de Sugestões ---
                
                // Converte a lista de IDs em uma string para usar na consulta SQL (ex: 1, 5, 10)
                $ids_para_excluir = !empty($produtos_no_carrinho) ? implode(',', $produtos_no_carrinho) : '0';
                
                // Query para buscar produtos que NÃO estão no carrinho (máximo 4 sugestões aleatórias)
                $sql_sugestoes = "SELECT id, nome, preco, imagem_url 
                                  FROM produtos 
                                  WHERE id NOT IN ($ids_para_excluir) 
                                  ORDER BY RAND() 
                                  LIMIT 4";
                                  
                $resultado_sugestoes = $conn->query($sql_sugestoes);

                if ($resultado_sugestoes->num_rows > 0) {
                    while($sugestao = $resultado_sugestoes->fetch_assoc()) {
                        ?>
                        <div class="sugestao-card">
                            <img src="../INICIO/imagens/<?php echo htmlspecialchars($sugestao['imagem_url']); ?>" alt="<?php echo htmlspecialchars($sugestao['nome']); ?>">
                            <h4><?php echo htmlspecialchars($sugestao['nome']); ?></h4>
                            <span class="price">R$ <?php echo number_format($sugestao['preco'], 2, ',', '.'); ?></span>
                            
                            <button class="btn-add-cart-sugestao" data-id="<?php echo $sugestao['id']; ?>">
                                + Adicionar
                            </button>
                        </div>
                        <?php
                    }
                } else {
                    // Mensagem caso não haja mais produtos além dos que já estão no carrinho
                }
                ?>
            </div>
        </section>
        
    </main>

    <footer class="footer">
        </footer>

    <script src="carrinho.js"></script>
</body>
</html>

<?php $conn->close(); ?>