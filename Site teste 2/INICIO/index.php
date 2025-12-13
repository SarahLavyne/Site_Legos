<?php 
    session_start(); // 1. ESSENCIAL: Inicia a sessão UMA ÚNICA VEZ (Antes de qualquer HTML)

    // Inclui a conexão com o banco de dados
    include '../conexao.php'; 

    // Prepara a query para buscar os produtos
    $sql = "SELECT id, nome, descricao, preco, categoria, imagem_url FROM produtos ORDER BY id DESC";
    $resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brick-Up - Sua Loja de LEGO</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <nav class="nav">
                <a href="#produtos">Produtos</a>
                <a href="#categorias">Categorias</a>
                <a href="#sobre">Sobre</a>
                <a href="#contato">Contato</a>
            </nav>
            <div class="header-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span class="user-greeting">
                        Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!
                    </span>
                    <a href="../perfil.php" class="btn-secondary">Meu Perfil</a>
                    <a href="../carrinho.php" class="btn-primary">Carrinho</a>
                    <a href="logout.php" class="btn-secondary">Sair</a>
                <?php else: ?>
                    <a href="../login.php" class="btn-secondary">Entrar</a>
                    <a href="../login.php" class="btn-primary">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h2>Construa Seus Sonhos com LEGO</h2>
                <p>Descubra milhares de sets e peças para dar vida à sua imaginação. Da criança ao adulto colecionador!</p>
                <div class="hero-buttons">
                    <button class="btn-primary btn-large">Explorar Produtos</button>
                    <button class="btn-secondary btn-large">Ver Lançamentos</button>
                </div>
            </div>

        </div>
    </section>

    <!-- Filtros -->
    <section class="filtros-section">
        <div class="container">
            <div class="filtros-container">
                <div class="filtros-categorias">
                    <button class="filtro-btn ativo" data-categoria="todos">Todos</button>
                    <button class="filtro-btn" data-categoria="carros">Carros</button>
                    <button class="filtro-btn" data-categoria="personagens">Personagens</button>
                    <button class="filtro-btn" data-categoria="construcoes">Construções</button>
                </div>
                <button class="ordenar-preco" id="btnOrdenarPreco">
                    <span>Preço</span>
                    <span class="setas">⇅</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Produtos -->
        <section id="produtos" class="products-section">
        <div class="container">
            <h2 class="section-title">Produtos em Destaque</h2>
            <div class="products-grid" id="gridProdutos">
                <?php 
                // 2. Query (Consulta) para buscar todos os produtos
                $sql = "SELECT id, nome, descricao, preco, categoria, imagem_url FROM produtos ORDER BY id DESC";
                $resultado = $conn->query($sql);

                // 3. Verifica se há produtos e inicia o loop
                if ($resultado->num_rows > 0) {
                    while($produto = $resultado->fetch_assoc()) {
                        // O HTML é gerado dentro do loop para cada produto
                        ?>
                        
                        <div class="product-card" data-categoria="<?php echo htmlspecialchars($produto['categoria']); ?>" data-preco="<?php echo htmlspecialchars($produto['preco']); ?>">
                            
                            <img src="imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" height=250 width=250" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                            <div class="product-footer">
                                <span class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                                <button class="btn-add-cart" data-id="<?php echo $produto['id']; ?>">Adicionar ao Carrinho</button>
                            </div>
                        </div>
                        
                        <?php
                    }
                } else {
                    echo "<p>Nenhum produto encontrado na loja.</p>";
                }

                // 4. Fecha a conexão
                $conn->close();
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🧱 BRICK-UP</h3>
                    <p>Sua loja especializada em LEGO com os melhores preços e maior variedade!</p>
                </div>
                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <a href="#produtos">Produtos</a>
                    <a href="#categorias">Categorias</a>
                    <a href="#sobre">Sobre Nós</a>
                    <a href="#contato">Contato</a>
                </div>
                <div class="footer-section">
                    <h4>Atendimento</h4>
                    <a href="#">FAQ</a>
                    <a href="#">Política de Troca</a>
                    <a href="#">Entrega</a>
                    <a href="#">Pagamento</a>
                </div>
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>contato@brickup.com.br</p>
                    <p>(11) 1234-5678</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Brick-Up. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
