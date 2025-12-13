<?php
session_start();
include '../conexao.php';

// Verifica se o ID do produto foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$produto_id = $conn->real_escape_string($_GET['id']);

// Busca as informações do produto
$sql = "SELECT * FROM produtos WHERE id = '$produto_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Brick-Up</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="detalhes.css"> </head>
<body>

    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <div class="header-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span class="user-greeting">Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!</span>
                    <a href="../CARRINHO/carrinho.php" class="btn-secondary">Carrinho</a>
                    <a href="index.php" class="btn-primary">produtos</a>
                <?php else: ?>
                    <a href="../LOGIN/login.php" class="btn-secondary">Login</a>
                    <a href="../CADASTRO/cadastro.php" class="btn-primary">Cadastre-se</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container detalhes-container">
        <div class="detalhes-imagem">
            <img src="imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
        </div>

        <div class="detalhes-info">
            <h2 class="produto-titulo"><?php echo htmlspecialchars($produto['nome']); ?></h2>
            
            <div class="avaliacao">
                ⭐⭐⭐⭐⭐ (4.8) </div>

            <hr class="divisor">

            <p class="preco-destaque">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <p class="pagamento-info">Pagamento somente no pix</p>

            <div class="descricao-produto">
                <h3>Sobre este item:</h3>
                <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
            </div>
        </div>

        <div class="detalhes-compra">
            <div class="buy-box">
                <p class="preco-total">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                
                <div class="aviso-retirada">
                    <span class="icone-loja">🏪</span>
                    <p><strong>Retirada SOMENTE na Loja.</strong></p>
                    <p class="subtexto">Este item não está disponível para entrega residencial.</p>
                </div>

                <p class="estoque-status">Em Estoque.</p>

                <div class="acoes-compra">
                    <button class="btn-amazon-add btn-add-cart" data-id="<?php echo $produto['id']; ?>">
                        Adicionar ao carrinho
                    </button>

                    <button class="btn-amazon-buy btn-buy-now" data-id="<?php echo $produto['id']; ?>">
                        Comprar agora
                    </button>
                </div>
                
                <p class="seguranca-texto">🔒 Transação Segura</p>
                <p class="vendedor-info">Vendido por <strong>Brick-Up Oficial</strong></p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Brick-Up. Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script> 
</body>
</html>
<?php $conn->close(); ?>