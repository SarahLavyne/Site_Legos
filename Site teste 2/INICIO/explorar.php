<?php 
    session_start();
    include '../conexao.php'; 

    $categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    $ordem_filtro = isset($_GET['ordem']) ? $_GET['ordem'] : 'recente';

    $sql = "SELECT id, nome, preco, categoria, imagem_url FROM produtos WHERE 1=1";

    if ($categoria_filtro != '') {
        $cat_safe = $conn->real_escape_string($categoria_filtro);
        $sql .= " AND categoria = '$cat_safe'";
    }
    if ($ordem_filtro == 'caro') {
        $sql .= " ORDER BY preco DESC";
    } elseif ($ordem_filtro == 'barato') {
        $sql .= " ORDER BY preco ASC";
    } else {
        $sql .= " ORDER BY id DESC"; 
    }

    $resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Produtos - Brick-Up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .filtros-container {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filtro-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filtro-group select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo"><h1>🧱 BRICK-UP</h1></div>
            <div class="header-actions">
                <a href="index.php" class="btn-secondary">Início</a>
                <a href="../CARRINHO/carrinho.php" class="btn-secondary">Carrinho</a>
            </div>
        </div>
    </header>

    <main class="container" style="padding: 40px 0;">
        <h2 class="section-title">Todos os Nossos Produtos</h2>

        <form method="GET" action="explorar.php" class="filtros-container">
            <div class="filtro-group">
                <label for="categoria">Categoria:</label>
                <select name="categoria" id="categoria">
                    <option value="">Todas as Categorias</option>
                    <option value="Carros" <?php echo ($categoria_filtro == 'Carros' ? 'selected' : ''); ?>>Carros</option>
                    <option value="Construcoes" <?php echo ($categoria_filtro == 'Construcoes' ? 'selected' : ''); ?>>Construções</option>
                    <option value="Personagens" <?php echo ($categoria_filtro == 'Personagens' ? 'selected' : ''); ?>>Personagens</option>
                </select>
            </div>

            <div class="filtro-group">
                <label for="ordem">Ordenar por:</label>
                <select name="ordem" id="ordem">
                    <option value="recente" <?php echo ($ordem_filtro == 'recente' ? 'selected' : ''); ?>>Mais recentes</option>
                    <option value="barato" <?php echo ($ordem_filtro == 'barato' ? 'selected' : ''); ?>>Menor Preço</option>
                    <option value="caro" <?php echo ($ordem_filtro == 'caro' ? 'selected' : ''); ?>>Maior Preço</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">Filtrar</button>
            <a href="explorar.php" class="btn-secondary" style="text-decoration: none; font-size: 0.9rem;">Limpar</a>
        </form>

        <div class="products-grid">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($produto = $resultado->fetch_assoc()): ?>
                    <div class="product-card">
                        <a href="detalhes.php?id=<?php echo $produto['id']; ?>">
                            <img src="imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        </a>
                        <div class="product-info">
                            <small style="color: #007bff;"><?php echo $produto['categoria']; ?></small>
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <div class="product-footer">
                                <span class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                                <a href="detalhes.php?id=<?php echo $produto['id']; ?>" class="btn-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum produto encontrado para os filtros selecionados.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        </footer>
</body>
</html>