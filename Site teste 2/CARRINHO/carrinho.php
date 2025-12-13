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
// JOIN entre as tabelas 'carrinho' e 'produtos'
$sql = "SELECT c.id AS carrinho_id, c.quantidade, 
               p.nome, p.preco, p.imagem_url, p.id AS produto_id
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = '$usuario_id'";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - Brick-Up</title>
    <link rel="stylesheet" href="../INICIO/styles.css"> 
    <link rel="stylesheet" href="carrinho.css"> </head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <nav class="nav">
                <a href="../INICIO/index.php">Início</a>
                <a href="../INICIO/index.php#produtos">Produtos</a>
            </nav>
            
            <div class="header-actions">
                <span class="user-greeting">
                    Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!
                </span>
                <a href="../INICIO/perfil.php" class="btn-secondary">Meu Perfil</a>
                <a href="../INICIO/logout.php" class="btn-secondary">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h2>Seu Carrinho de Compras</h2>

        <section class="carrinho-itens">
            <?php if ($resultado->num_rows > 0): ?>
                
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
                        <tr data-carrinho-id="<?php echo $item['carrinho_id']; ?>">
                            <td>
                                <img src="../INICIO/imagens/<?php echo htmlspecialchars($item['imagem_url']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" width="50">
                                <?php echo htmlspecialchars($item['nome']); ?>
                            </td>
                            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <form action="processa_carrinho.php" method="POST" class="form-quantidade">
                                    <input type="hidden" name="carrinho_id" value="<?php echo $item['carrinho_id']; ?>">
                                    <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="input-quantidade" required>
                                    <button type="submit" name="acao" value="atualizar" class="btn-update-qty">Atualizar</button>
                                </form>
                            </td>
                            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
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
                
                <div class="carrinho-resumo">
                    <h3>Total da Compra: R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></h3>
                    <a href="../CHECKOUT/checkout.php" class="btn-primary btn-checkout">Finalizar Compra</a>
                </div>

            <?php else: ?>
                <p>Seu carrinho está vazio. <a href="../INICIO/index.php">Clique aqui para explorar produtos!</a></p>
            <?php endif; ?>
        </section>
        </section>

        <section class="sugestoes-section">
            <h2 class="section-title">Você também pode gostar...</h2>
            <div class="sugestoes-grid">
                <?php
                // --- Lógica de Sugestões ---
                
                // 1. Identificar produtos que JÁ estão no carrinho
                $produtos_no_carrinho = [];
                // Se houver resultados (o carrinho não está vazio)
                if ($resultado->num_rows > 0) { 
                    // Rebobinar o resultado para poder usar os dados novamente
                    $resultado->data_seek(0); 
                    while($item_carrinho = $resultado->fetch_assoc()) {
                        $produtos_no_carrinho[] = $item_carrinho['produto_id'];
                    }
                }
                
                // Converte a lista de IDs em uma string para usar na consulta SQL (ex: 1, 5, 10)
                $ids_para_excluir = !empty($produtos_no_carrinho) ? implode(',', $produtos_no_carrinho) : '0';
                
                // 2. Query para buscar produtos que NÃO estão no carrinho (máximo 4 sugestões)
                $sql_sugestoes = "SELECT id, nome, descricao, preco, imagem_url 
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
                    echo "<p>Não há mais produtos para sugerir. Que pena!</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        </footer>

    <script src="../INICIO/script.js"></script>
</body>
</html>

<?php $conn->close(); ?>