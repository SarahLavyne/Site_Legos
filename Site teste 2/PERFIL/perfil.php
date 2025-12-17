<?php
session_start();
include '../conexao.php'; 

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 2. Busca os dados atuais do usuário
$sql = "SELECT nome, email, cpf, celular, cep FROM usuarios WHERE id = '$usuario_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 0) {
    echo "Erro: Usuário não encontrado no banco de dados.";
    exit;
}

$usuario = $resultado->fetch_assoc();

// 3. Define a seção ativa
$secao_ativa = 'dados';
if (isset($_GET['secao'])) {
    $secoes_permitidas = ['pedidos', 'seguranca'];
    if (in_array($_GET['secao'], $secoes_permitidas)) {
        $secao_ativa = $_GET['secao'];
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
    <style>
        /* Estilos específicos para o Código de Retirada e Pedidos */
        .vouche-retirada {
            margin-top: 15px;
            background: #f0f7ff;
            border: 2px dashed #007bff;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            max-width: 220px;
        }
        .vouche-retirada small {
            display: block;
            font-size: 0.7rem;
            color: #007bff;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .codigo-texto {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.4rem;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 2px;
        }
        .pedido-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            border: 1px solid #edf2f7;
        }
        .pedido-info { flex: 1; min-width: 250px; }
        .detalhes-itens { flex: 1.5; min-width: 300px; border-left: 1px solid #eee; padding-left: 20px; }
        .item-linha { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #f8f9fa; }
        .status-pendente { color: #d97706; font-weight: bold; }
        .status-pago { color: #059669; font-weight: bold; }
        .status-entregue { color: #2563eb; font-weight: bold; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo"><h1>🧱 BRICK-UP</h1></div>
            <div class="header-actions">                
                <a href="../INICIO/index.php" class="btn-secondary">Início</a>
                <a href="../CARRINHO/carrinho.php" class="btn-primary">Carrinho</a>
            </div>
        </div>
    </header>

    <main class="perfil-layout">
        <div class="perfil-menu">
            <h2>Olá, <?php echo explode(' ', htmlspecialchars($usuario['nome']))[0]; ?>!</h2>
            <nav class="menu-nav">
                <a href="perfil.php" class="menu-item <?php echo ($secao_ativa == 'dados' ? 'ativo' : ''); ?>">Dados Pessoais</a>
                <a href="perfil.php?secao=pedidos" class="menu-item <?php echo ($secao_ativa == 'pedidos' ? 'ativo' : ''); ?>">Meus Pedidos</a> 
                <a href="perfil.php?secao=seguranca" class="menu-item <?php echo ($secao_ativa == 'seguranca' ? 'ativo' : ''); ?>">Segurança</a> 
                <a href="../INICIO/logout.php" class="menu-item logout-link">Sair da Conta</a>
            </nav>
        </div>
        
        <div class="perfil-conteudo">
            
            <?php if ($secao_ativa == 'dados'): ?>
                <h2>Seus Dados e Localização</h2>
                <form action="processa_perfil.php" method="POST" class="form-perfil">
                    <div class="form-coluna">
                        <label>Nome Completo:</label>
                        <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        <label>E-mail:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        <label>Celular:</label>
                        <input type="text" name="celular" value="<?php echo htmlspecialchars($usuario['celular']); ?>">
                    </div>
                    <div class="form-coluna">
                        <label>CEP:</label>
                        <input type="text" name="cep" value="<?php echo htmlspecialchars($usuario['cep']); ?>">
                        <button type="submit" class="btn-primary" style="margin-top: 20px;">Atualizar Dados</button>
                    </div>
                </form>

            <?php elseif ($secao_ativa == 'pedidos'): ?>
                <h2>Meu Histórico de Pedidos</h2>
                <?php 
                // Busca pedidos incluindo o código de retirada
                $sql_pedidos = "SELECT id, data_pedido, total, status, codigo_retirada FROM pedidos WHERE usuario_id = '$usuario_id' ORDER BY data_pedido DESC";
                $res_pedidos = $conn->query($sql_pedidos);
                
                if ($res_pedidos->num_rows > 0): 
                ?>
                    <div class="historico-pedidos">
                        <?php while ($pedido = $res_pedidos->fetch_assoc()): ?>
                            <div class="pedido-card">
                                <div class="pedido-info">
                                    <p><strong>Pedido #<?php echo $pedido['id']; ?></strong></p>
                                    <p>Data: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                                    <p>Status: <span class="status-<?php echo strtolower(explode(' ', $pedido['status'])[0]); ?>"><?php echo htmlspecialchars($pedido['status']); ?></span></p>
                                    <p>Total: <strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong></p>
                                    
                                    <div class="vouche-retirada">
                                        <small>Apresente na retirada:</small>
                                        <span class="codigo-texto"><?php echo htmlspecialchars($pedido['codigo_retirada']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="detalhes-itens">
                                    <h4 style="margin-bottom: 10px; font-size: 0.9rem; color: #64748b;">Itens do Pedido:</h4>
                                    <?php
                                    $sql_itens = "SELECT ip.quantidade, p.nome, p.imagem_url 
                                                  FROM itens_pedido ip
                                                  JOIN produtos p ON ip.produto_id = p.id
                                                  WHERE ip.pedido_id = " . $pedido['id'];
                                    $res_itens = $conn->query($sql_itens);
                                    while ($item = $res_itens->fetch_assoc()):
                                    ?>
                                        <div class="item-linha">
                                            <img src="../INICIO/imagens/<?php echo htmlspecialchars($item['imagem_url']); ?>" width="35" style="border-radius: 4px;">
                                            <span><?php echo $item['quantidade']; ?>x <?php echo htmlspecialchars($item['nome']); ?></span>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="aviso-vazio">Você ainda não fez pedidos. <a href="../INICIO/index.php">Clique aqui</a> para ver os Legos!</div>
                <?php endif; ?>

            <?php elseif ($secao_ativa == 'seguranca'): ?>
                <h2>Alterar Senha de Acesso</h2>
                <form action="processa_senha.php" method="POST" class="form-perfil">
                    <div class="form-coluna">
                        <label>Senha Antiga:</label>
                        <input type="password" name="senha_antiga" required>
                        <label>Nova Senha:</label>
                        <input type="password" name="senha_nova" required>
                        <label>Confirmar Nova Senha:</label>
                        <input type="password" name="senha_confirmar" required>
                        <button type="submit" class="btn-primary">Atualizar Senha</button>
                    </div>
                </form>
            <?php endif; ?>

        </div> 
    </main>
</body>
</html>
<?php $conn->close(); ?>