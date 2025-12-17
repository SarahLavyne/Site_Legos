<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Perguntas Frequentes | Brick-Up</title>
    <link rel="stylesheet" href="styles.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const header = item.querySelector('.faq-pergunta');
                header.addEventListener('click', () => {
                    faqItems.forEach(i => {
                        if (i !== item && i.classList.contains('ativo')) {
                            i.classList.remove('ativo');
                            i.querySelector('.faq-resposta').style.maxHeight = null;
                        }
                    });

                    item.classList.toggle('ativo');
                    const answer = item.querySelector('.faq-resposta');
                    
                    if (item.classList.contains('ativo')) {
                        answer.style.maxHeight = answer.scrollHeight + "px";
                    } else {
                        answer.style.maxHeight = null;
                    }
                });
            });
        });
    </script>

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

    <main class="container faq-page">
        
        <section class="faq-header">
            <h1>Perguntas Frequentes (FAQ)</h1>
            <p>Encontre respostas rápidas para as dúvidas mais comuns sobre pedidos, pagamentos e trocas na Brick-Up.</p>
        </section>

        <section class="faq-list">
            
            <div class="faq-item">
                <h3 class="faq-pergunta">💳 Quais são as formas de pagamento aceitas?</h3>
                <div class="faq-resposta">
                    <p>Aceitamos duas formas de pagamento para sua comodidade e segurança:</p>
                    <ul>
                        <li><strong>Cartão de Crédito:</strong> Visa, Mastercard, Elo e American Express. Pagamento nessa modalidade é realizado apenas na RETIRADA do produto.</li>
                        <li><strong>PIX:</strong> Pagamento instantâneo. O código QR e a chave PIX são gerados na finalização do pedido, garantindo a aprovação imediata. Pode ser feito no site ou no momento da retirada.</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-pergunta">🔄 Como funciona a Política de Troca e Devolução?</h3>
                <div class="faq-resposta">
                    <p>Nossa política respeita integralmente o Código de Defesa do Consumidor (CDC):</p>
                    <ul>
                        <li><strong>Arrependimento/Desistência:</strong> Você tem até 7 (sete) dias corridos, a contar do recebimento do produto, para solicitar a devolução ou troca, desde que o produto esteja sem uso e na embalagem original lacrada.</li>
                        <li><strong>Defeito de Fabricação:</strong> Caso o produto apresente defeito, você tem até 90 (noventa) dias corridos para solicitar a troca ou reparo.</li>
                    </ul>
                    <p>Para iniciar o processo, deve comparecer a loja para solicitar a devolução ou a troca.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-pergunta">📍 Posso retirar meu pedido diretamente na loja?</h3>
                <div class="faq-resposta">
                    <p>Sim, temos somente a retirada grátis em nosso ponto físico após a confirmação do pedido.</p>
                    <p><strong>Atenção ao Retirar:</strong></p>
                    <p>Ao solicitar um produto, será gerado um Código de Retirada, que também pode ser acessado na sua área de cliente. <strong>É obrigatório apresentar este código e um documento de identificação com foto</strong> no momento da retirada para garantir a segurança da transação.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-pergunta">📦 Como posso rastrear meu pedido?</h3>
                <div class="faq-resposta">
                    <p>Você pode acompanhar o status do seu pedido diretamente na seção "Meus Pedidos" da sua conta aqui no nosso site.</p>
                </div>
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
</body>
</html>

<style>
.faq-page {
    padding-top: 40px;
}
.faq-header {
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 1px solid #eee;
    padding-bottom: 20px;
}

.faq-list {
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 15px;
    overflow: hidden;
    background-color: #fff;
    transition: all 0.3s ease;
}

.faq-pergunta {
    padding: 18px 25px;
    cursor: pointer;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f7f7f7;
    transition: background-color 0.3s;
}

.faq-pergunta::after {
    content: '+';
    font-size: 1.5rem;
    color: #007bff;
    transition: transform 0.3s;
}

.faq-item.ativo .faq-pergunta {
    background-color: #e9f5ff; /* Fundo levemente azul ao abrir */
    color: #007bff;
}

.faq-item.ativo .faq-pergunta::after {
    content: '-';
    transform: rotate(180deg);
}

.faq-resposta {
    padding: 0 25px;
    background-color: #ffffff;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.faq-resposta p, .faq-resposta ul {
    padding-bottom: 15px;
}

.faq-resposta ul {
    margin-top: 0;
}

.faq-resposta li {
    margin-bottom: 5px;
}
</style>