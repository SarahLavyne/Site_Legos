<?php 
    session_start();
    include '../conexao.php'; 
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
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <div class="header-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    
                    <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'administrador'): ?>
                        <a href="../ADM/adm.php" class="btn-primary">Painel ADM</a>
                    <?php endif; ?>
                    
                    <a href="../CARRINHO/carrinho.php" class="btn-secondary">Carrinho</a>
                    <a href="../PERFIL/perfil.php" class="btn-secondary">Perfil</a>
                    <a href="logout.php" class="btn-primary">Sair</a>
                <?php else: ?>
                    <a href="../LOGIN/login.php" class="btn-secondary">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h2>Construa Seus Sonhos com LEGO</h2>
                <p>Descubra milhares de sets e peças para dar vida à sua imaginação. Da criança ao adulto colecionador!</p>
                <div class="hero-buttons">
                    <a href="explorar.php" class="btn-primary btn-large">Explorar Produtos</a>
                    <a href="#produtos" class="btn-secondary btn-large">Ver Destaques</a>
                </div>
            </div>
        </div>
    </section>

    <section id="produtos" class="products-section">
        <div class="container">
            <h2 class="section-title">🔥 Produtos em Destaque</h2>
            <div class="products-grid">
                <?php 
                // Busca apenas produtos marcados como destaque no banco de dados
                $sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE destaque = 1 ORDER BY id DESC";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while($produto = $resultado->fetch_assoc()) {
                        ?>
                        <div class="product-card">
                            <a href="detalhes.php?id=<?php echo $produto['id']; ?>">
                                <img src="imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            </a>

                            <div class="product-info">
                                <h3>
                                    <a href="detalhes.php?id=<?php echo $produto['id']; ?>" style="text-decoration: none; color: inherit;">
                                        <?php echo htmlspecialchars($produto['nome']); ?>
                                    </a>
                                </h3>

                                <div class="product-footer">
                                    <span class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                                    <a href="detalhes.php?id=<?php echo $produto['id']; ?>" class="btn-primary">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='alerta-aviso'>Nenhum produto em destaque no momento. Confira nossa aba de explorar!</p>";
                }
                ?>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="explorar.php" class="btn-secondary btn-large">Ver Todos os Produtos →</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🧱 BRICK-UP</h3>
                    <p>Sua loja especializada em LEGO com os melhores preços e maior variedade!</p>
                </div>
                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <a href="explorar.php">Explorar Produtos</a>
                    <a href="sobre_nos.php">Sobre Nós</a>
                </div>
                <div class="footer-section">
                    <h4>Atendimento</h4>
                    <a href="faq.php">FAQ</a>
                    <a href="politica_troca.php">Política de Troca</a>
                    <a href="entrega_pagamento.php">Entrega/Pagamento</a>
                </div>
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>brickup@gmail.com</p>
                    <p>(68) 99923-7313</p>
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
<?php 
    $conn->close(); 
?>