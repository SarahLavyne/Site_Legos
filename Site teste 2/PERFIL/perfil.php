<?php
session_start();
include '../conexao.php'; 

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 2. Busca os dados atuais do usuário (CORRIGIDO: usando 'celular' e SEM 'endereco')
$sql = "SELECT nome, email, cpf, celular, cep FROM usuarios WHERE id = '$usuario_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 0) {
    echo "Erro: Usuário não encontrado no banco de dados.";
    exit;
}

$usuario = $resultado->fetch_assoc();

// 3. Define a seção ativa: 'dados' por padrão ou outra seção
$secao_ativa = 'dados';
if (isset($_GET['secao'])) {
    if ($_GET['secao'] == 'pedidos') {
        $secao_ativa = 'pedidos';
    } elseif ($_GET['secao'] == 'seguranca') {
        $secao_ativa = 'seguranca';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Brick-Up</title>
    <link rel="stylesheet" href="../INICIO/styles.css"> 
    <link rel="stylesheet" href="perfil.css"> 
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <div class="header-actions">                
                <a href="../INICIO/index.php" class="btn-secondary">Inicio</a>
                <a href="../CARRINHO/carrinho.php" class="btn-primary">Carrinho</a>
            </div>
        </div>
    </header>

    <main class="perfil-layout">
        
        <?php
        // 4. BLOCO DE MENSAGENS DE STATUS (Sucesso/Erro na atualização de Dados Pessoais)
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $mensagem = '';
            $classe = '';

            if ($status === 'sucesso') {
                $mensagem = "Seus dados foram atualizados com sucesso!";
                $classe = 'alerta-sucesso';
            } elseif ($status === 'erro') {
                $mensagem = "Erro ao atualizar dados. Por favor, tente novamente.";
                $classe = 'alerta-erro';
            }
            
            if ($mensagem) {
                echo "<div class='alerta $classe'>$mensagem</div>";
            }
        }
        ?>

        <div class="perfil-menu">
            <h2>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?></h2>
            <nav class="menu-nav">
                <a href="perfil.php" class="menu-item <?php echo ($secao_ativa == 'dados' ? 'ativo' : ''); ?>">Dados Pessoais</a>
                <a href="perfil.php?secao=pedidos" class="menu-item <?php echo ($secao_ativa == 'pedidos' ? 'ativo' : ''); ?>">Meus Pedidos</a> 
                
                <a href="perfil.php?secao=seguranca" class="menu-item <?php echo ($secao_ativa == 'seguranca' ? 'ativo' : ''); ?>">Segurança (Senha)</a> 
                
                <a href="../INICIO/logout.php" class="menu-item logout-link">Deslogar conta</a>
            </nav>
        </div>
        
        <div class="perfil-conteudo">
            
            <?php if ($secao_ativa == 'dados'): ?>
                
                <h2>Dados Pessoais e CEP</h2>
                
                <form action="processa_perfil.php" method="POST" class="form-perfil">
                    
                    <div class="form-coluna">
                        <h3 class="form-titulo">1. Seus Dados</h3>
                        
                        <label for="nome">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        
                        <label for="celular">Celular:</label>
                        <input type="text" id="celular" name="celular" value="<?php echo htmlspecialchars($usuario['celular']); ?>">
                        
                        <label for="cpf">CPF (Não Editável):</label>
                        <input type="text" id="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" disabled> 
                    </div>

                    <div class="form-coluna">
                        <h3 class="form-titulo">2. CEP para Retirada</h3>

                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($usuario['cep']); ?>">
                        
                        <button type="submit" class="btn-primary btn-salvar" style="margin-top: 50px;">Salvar Alterações</button>
                    </div>
                </form>

            <?php elseif ($secao_ativa == 'pedidos'): ?>
                
                <h2>Histórico de Pedidos</h2>

                <?php 
                $sql_pedidos = "SELECT id, data_pedido, total, status FROM pedidos WHERE usuario_id = '$usuario_id' ORDER BY data_pedido DESC";
                $res_pedidos = $conn->query($sql_pedidos);
                
                if ($res_pedidos->num_rows > 0): 
                ?>
                    <div class="historico-pedidos">
                        <?php while ($pedido = $res_pedidos->fetch_assoc()): ?>
                            <div class="pedido-card">
                                
                                <div class="pedido-info">
                                    <p><strong>Pedido #<?php echo $pedido['id']; ?></strong></p>
                                    <p>Data: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                                    <p class="status-<?php echo strtolower($pedido['status']); ?>">Status: <?php echo htmlspecialchars($pedido['status']); ?></p>
                                    <p>Total: R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
                                </div>
                                
                                <div class="detalhes-itens">
                                    <?php
                                    $sql_itens = "SELECT ip.quantidade, ip.preco_unitario, p.nome, p.imagem_url 
                                                  FROM itens_pedido ip
                                                  JOIN produtos p ON ip.produto_id = p.id
                                                  WHERE ip.pedido_id = " . $pedido['id'];
                                    $res_itens = $conn->query($sql_itens);
                                    
                                    while ($item = $res_itens->fetch_assoc()):
                                    ?>
                                        <div class="item-linha">
                                            <img src="../INICIO/imagens/<?php echo htmlspecialchars($item['imagem_url']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" width="40">
                                            <span><?php echo $item['quantidade']; ?>x <?php echo htmlspecialchars($item['nome']); ?></span>
                                            <span class="preco-item">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></span>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="aviso-vazio">Você ainda não realizou nenhum pedido. Que tal conferir nossos <a href="../INICIO/index.php">produtos</a>?</p>
                <?php endif; ?>

            <?php elseif ($secao_ativa == 'seguranca'): ?>
                
                <h2>Atualizar Senha</h2>
                
                <?php
                // Exibição de mensagens de status (sucesso/erro da atualização de senha)
                if (isset($_GET['status_senha'])) {
                    $status_senha = $_GET['status_senha'];
                    if ($status_senha === 'sucesso') {
                        echo "<div class='alerta alerta-sucesso'>Senha atualizada com sucesso!</div>";
                    } elseif ($status_senha === 'erro_antiga') {
                        echo "<div class='alerta alerta-erro'>Erro: A senha antiga está incorreta.</div>";
                    } elseif ($status_senha === 'erro_diferente') {
                         echo "<div class='alerta alerta-erro'>Erro: A nova senha e a confirmação não coincidem.</div>";
                    } else {
                        echo "<div class='alerta alerta-erro'>Erro ao atualizar a senha. Tente novamente.</div>";
                    }
                }
                ?>

                <form action="processa_senha.php" method="POST" class="form-senha">
                    <div class="form-coluna">
                        
                        <label for="senha_antiga">Senha Antiga:</label>
                        <input type="password" id="senha_antiga" name="senha_antiga" required>
                        
                        <label for="senha_nova">Nova Senha:</label>
                        <input type="password" id="senha_nova" name="senha_nova" required>
                        
                        <label for="senha_confirmar">Confirmar Nova Senha:</label>
                        <input type="password" id="senha_confirmar" name="senha_confirmar" required>
                        
                        <button type="submit" class="btn-primary btn-salvar">Mudar Senha</button>
                    </div>
                </form>

            <?php endif; ?>

        </div> 
    </main>

    <footer class="footer">
        </footer>
</body>
</html>

<?php $conn->close(); ?>