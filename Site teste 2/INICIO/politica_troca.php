<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Troca e Devolução | Brick-Up</title>
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
                    <a href="../CARRINHO/carrinho.php" class="btn-secondary">Carrinho</a>
                    <a href="../PERFIL/perfil.php" class="btn-secondary">Perfil</a>
                    <a href="logout.php" class="btn-primary">Sair</a>
                <?php else: ?>
                    <a href="../LOGIN/login.php" class="btn-secondary">Entrar</a>
                    <a href="../INICIO/index.php" class="btn-secondary">Voltar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container politica-page">
        
        <section class="politica-header">
            <h1>Política de Troca e Devolução</h1>
            <p>Nossa política está baseada no Código de Defesa do Consumidor (Lei nº 8.078/90), garantindo seus direitos e a transparência em todas as transações.</p>
        </section>

        <section class="politica-corpo">

            <div class="politica-secao">
                <h2>1. Direito de Arrependimento (Compras Via PIX)</h2>
                
                <p>Mesmo que a retirada seja feita em nossa loja física, a compra realizada via site é considerada compra fora do estabelecimento comercial, garantindo o direito de arrependimento.</p>
                
                <h3>Detalhes:</h3>
                <ul>
                    <li><strong>Prazo:</strong> O cliente tem o prazo de <strong>7 (sete) dias corridos</strong>, contados a partir da data de retirada do produto na loja, para manifestar a desistência da compra.</li>
                    <li><strong>Condição do Produto:</strong> O produto deve ser devolvido em sua embalagem original e sem indícios de uso.</li>
                    <li><strong>Reembolso:</strong> O reembolso será integral, incluindo o valor total pago pelo produto.</li>
                </ul>
            </div>

            <div class="politica-secao">
                <h2>2. Troca por Defeito (Vício do Produto)</h2>
                
                <p>Em caso de produtos que apresentem defeito de fabricação ou vício, aplicam-se os prazos legais, independentemente de a compra ter sido online ou na loja física:</p>
                
                <ul>
                    <li><strong>Prazo Legal:</strong> Nossos produtos (blocos de montar) são considerados produtos duráveis. O prazo para reclamação por defeito é de <strong>90 (noventa) dias corridos</strong> a partir da data de retirada.</li>
                    <li><strong>Solução:</strong> A Brick-Up tem o prazo de até 30 (trinta) dias para solucionar o problema. Se o defeito não for resolvido nesse período, o cliente pode optar pela troca do produto, devolução do valor pago ou abatimento proporcional do preço.</li>
                </ul>
            </div>

            <div class="politica-secao">
                <h2>3. Troca por Outros Motivos (Não Obrigatória por Lei)</h2>
                
                <p>A troca por questões de gosto, cor, ou modelo (sem que o produto apresente defeito) é uma liberalidade da Brick-Up, aplicada exclusivamente em compras feitas em nosso site:</p>
                
                <ul>
                    <li><strong>Prazo:</strong> Aceitamos a troca por modelo ou cor em até <strong>7 (sete) dias corridos</strong> após a retirada.</li>
                    <li><strong>Condição:</strong> O produto deve estar em perfeitas condições, sem sinais de abertura da embalagem ou uso, e com a Nota Fiscal.</li>
                    <li><strong>Atenção:</strong> Neste caso, a troca é por outro produto de igual ou maior valor (com pagamento da diferença). Não realizamos devolução do dinheiro para esta modalidade.</li>
                </ul>
            </div>

            <div class="politica-secao">
                <h2>4. Compras na Loja Física</h2>
                
                <p>Para pagamentos realizadas diretamente em nosso estabelecimento físico, não há direito legal de arrependimento (desistência sem justificativa), conforme o CDC.</p>
                
                <ul>
                    <li>A troca só será obrigatória se o produto apresentar defeito.</li>
                    <li>Qualquer outra troca será analisada e decidida pela gerência, seguindo nossa política interna de cortesia.</li>
                </ul>
            </div>

        </section>

    </main>

 <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🧱 BRICK-UP</h3>
                    <p>Sua loja especializada em LEGO com os melhores preços e maior variedade!</p>
                </div>
                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <a href="index.php">Produtos</a>
                    <a href="sobre_nos.php">Sobre Nós</a>
                </div>
                <div class="footer-section">
                    <h4>Atendimento</h4>
                    <a href="faq.php">FAQ</a>
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

</body>
</html>

<style>
/* Estilos Específicos para a página Política */
.politica-page {
    padding: 40px 0;
}
.politica-header {
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 20px;
}
.politica-corpo {
    max-width: 900px;
    margin: 0 auto;
}
.politica-secao {
    margin-bottom: 40px;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fcfcfc;
}
.politica-secao h2 {
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-top: 0;
    font-size: 1.5rem;
}
.politica-secao h3 {
    color: #007bff;
    font-size: 1.2rem;
    margin-top: 20px;
}
.politica-secao ul {
    list-style-type: disc;
    margin-left: 20px;
    padding-left: 0;
}
.politica-secao li {
    margin-bottom: 10px;
    line-height: 1.5;
}
</style>