<?php 
session_start(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Brick-Up</title>
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
    

    <main class="container">
        
        <section class="sobre-nos-header">
            <h1>Nossa História</h1>
            <p class="introducao">
                A Brick-Up nasceu da paixão por blocos de montar e da crença no poder da criatividade. Para nós, não é apenas um brinquedo, mas uma ferramenta de desenvolvimento, aprendizado e imaginação sem limites.
            </p>
        </section>

        <section class="missao-visao-valores">
            <h2>Nosso Propósito</h2>
            
            <div class="proposito-grid">
                
                <div class="proposito-card">
                    <h3>🎯 Missão</h3>
                    <p>Inspirar a criatividade através de experiências de construção divertidas e de alta qualidade</p>
                </div>
                
                <div class="proposito-card">
                    <h3>✨ Visão</h3>
                    <p>Ser a referência principal no mercado de blocos de montar, reconhecida por fomentar a inovação e o desenvolvimento de habilidades em todas as idades.</p>
                </div>
                
                <div class="proposito-card">
                    <h3>⭐ Valores</h3>
                    <ul>
                        <li><strog>Qualidade: Garantir que cada produto oferecido promova durabilidade e segurança.</li>
                        <li>Criatividade: Estimular a imaginação e a liberdade de construir.</li>
                        <li>Comunidade: Valorizar e conectar construtores de todas as gerações.</li>
                        <li>Integridade: Conduzir os negócios com ética, transparência e respeito mútuo.</li>
                    </ul>
                </div>

            </div>
        </section>

        <section class="foco-qualidade">
            <h2>Qualidade e Compromisso</h2>
            <p>
                Trabalhamos apenas com fornecedores que compartilham nosso compromisso com a excelência. Cada peça vendida na Brick-Up passa por rigorosos padrões de qualidade para garantir que sua experiência de construção seja perfeita, desde o primeiro bloco até o último detalhe da sua obra-prima.
            </p>
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

</body>
</html>

<style>
/* Estilos Específicos para a página Sobre Nós */
.sobre-nos-header {
    text-align: center;
    padding: 40px 0;
    border-bottom: 1px solid #eee;
}
.introducao {
    font-size: 1.25rem;
    color: #555;
    max-width: 800px;
    margin: 20px auto;
}
.missao-visao-valores {
    padding: 40px 0;
    text-align: center;
}
.proposito-grid {
    display: flex;
    gap: 30px;
    margin-top: 30px;
    justify-content: center;
}
.proposito-card {
    flex: 1;
    max-width: 300px;
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #ebebebff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    text-align: left;
}
.proposito-card h3 {
    color: #007bff; /* Cor principal da marca */
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    margin-top: 0;
}
.proposito-card ul {
    list-style: none;
    padding-left: 0;
}
.proposito-card li {
    margin-bottom: 8px;
    line-height: 1.4;
}

.foco-qualidade {
    padding: 40px 0;
    text-align: center;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}
</style>