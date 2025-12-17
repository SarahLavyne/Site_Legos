<?php 
session_start();
// O conteúdo desta página é informativo e não requer conexão com o banco de dados.
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento e Retirada na Loja | Brick-Up</title>
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
    <main class="container entrega-pagamento-page">
        
        <section class="page-header">
            <h1>Formas de Pagamento e Retirada</h1>
            <p>Conheça as opções disponíveis para finalizar sua compra e as regras para retirada imediata na loja.</p>
        </section>

        <section class="secao-pagamento">
            <h2>💳 Formas de Pagamento</h2>
            
            <div class="modalidade-pagamento">
                <h3>Compre no Site (Online)</h3>
                <p>Priorizando a agilidade e o processamento imediato para a retirada no mesmo dia, aceitamos exclusivamente:</p>
                <div class="destaque-pagamento">
                    <h4>PIX - Pagamento Imediato</h4>
                    <p>O código QR e a chave PIX são gerados na finalização do pedido. A confirmação é instantânea, liberando a retirada do produto em poucos minutos.</p>
                </div>
            </div>

            <div class="modalidade-pagamento">
                <h3>Na Loja Física</h3>
                <p>Para compras ou pagamentos realizados diretamente em nosso balcão, aceitamos:</p>
                <ul>
                    <li><strong>PIX</strong></li>
                    <li><strong>Cartão de Débito</strong></li>
                    <li><strong>Cartão de Crédito</strong></li>
                </ul>
            </div>
        </section>

        <section class="secao-retirada">
            <h2>📍 Retirada Rápida na Loja</h2>
            
            <div class="regra-retirada">
                <h3>Regra de Ouro: Retirada no Mesmo Dia!</h3>
                <p>Todos os pedidos feitos devem ser retirados no <strong>mesmo dia da solicitação</strong>.</p>
                <p class="alerta-prazo">⚠️ <strong>Prazo Máximo de Retirada:</strong> O limite para retirada é até as <strong>16:00 (4 da tarde)</strong>, do dia da compra. Pedidos não retirados até este horário serão cancelados automaticamente e o reembolso via PIX será processado no próximo dia útil.</p>
            </div>
            
            <div class="passos-retirada">
                <h3>Passos para Retirada Segura</h3>
                <ol>
                    <li><strong>Pagamento via PIX:</strong> Finalize sua compra no site pagando via PIX. A aprovação é imediata.</li>
                    <li><strong>Recebimento do Código:</strong> Após a confirmação, você receberá um <strong>Código de Retirada</strong> na sua Área do Cliente.</li>
                    <li><strong>Dirija-se à Loja:</strong> Somente venha à loja após verificar que seu pedido está separado.</li>
                    <li><strong>Apresente o Código:</strong> É obrigatório informar o Código de Retirada e um documento de identificação com foto no balcão para liberação do produto.</li>
                </ol>
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
                    <a href="politica_troca.php">Política de Troca</a>
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
/* Estilos Específicos para a página Entrega e Pagamento */
.entrega-pagamento-page {
    padding: 40px 0;
}
.page-header {
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid #eee;
    padding-bottom: 20px;
}
h2 {
    color: #007bff;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 1.8rem;
}
h3 {
    font-size: 1.4rem;
    color: #333;
    margin-top: 25px;
}
.secao-pagamento, .secao-retirada {
    max-width: 800px;
    margin: 0 auto 50px auto;
    padding: 20px;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
.destaque-pagamento {
    background-color: #e9f5ff;
    border: 1px solid #007bff;
    padding: 15px;
    border-radius: 5px;
    margin-top: 15px;
}
.destaque-pagamento h4 {
    color: #007bff;
    margin-top: 0;
}
.alerta-prazo {
    background-color: #fff3cd; /* Amarelo claro */
    color: #856404; /* Texto escuro */
    border: 1px solid #ffeeba;
    padding: 15px;
    border-radius: 5px;
    font-weight: bold;
    margin: 15px 0;
}
.passos-retirada ol {
    list-style-type: decimal;
    margin-left: 20px;
    padding-left: 0;
}
.passos-retirada li {
    margin-bottom: 10px;
    line-height: 1.6;
}
</style>