<?php
session_start();
// Caminho de conexão corrigido: sobe um nível (..) para encontrar o arquivo na raiz
include '../conexao.php'; 

// 1. VERIFICAÇÃO DE SEGURANÇA CRUCIAL: Acesso apenas para Administradores
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'administrador') {
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_nome = $_SESSION['usuario_nome'];

// 2. Lógica para determinar a seção ativa (Produtos é o padrão)
$secao_ativa = isset($_GET['secao']) ? $_GET['secao'] : 'produtos';

// Lógica para exibir mensagens de status
$mensagem = '';
$classe = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sucesso') {
        $mensagem = "Operação realizada com sucesso!";
        $classe = 'alerta-sucesso';
    } elseif ($_GET['status'] == 'erro') {
        $mensagem = "Ocorreu um erro durante a operação.";
        $classe = 'alerta-erro';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Brick-Up</title>
    
    <link rel="stylesheet" href="../INICIO/styles.css"> 
    <link rel="stylesheet" href="adm.css"> </head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <div class="header-actions">
                <a href="../INICIO/index.php" class="btn-secondary">Loja</a>
                <a href="../INICIO/logout.php" class="btn-primary">Sair</a>
            </div>
        </div>
    </header>

    <main class="perfil-layout container"> 
        
        <aside class="perfil-menu">
            <div class="menu-header">
                <h3>Bem-vindo, Administrador</h3>
                <p><?php echo htmlspecialchars($usuario_nome); ?></p>
            </div>
            
            <nav class="menu-nav">
                <a href="adm.php?secao=produtos" class="menu-item <?php echo ($secao_ativa == 'produtos' ? 'ativo' : ''); ?>">📦 Produtos</a>
                <a href="adm.php?secao=clientes" class="menu-item <?php echo ($secao_ativa == 'clientes' ? 'ativo' : ''); ?>">👥 Clientes</a>
                <a href="adm.php?secao=pedidos" class="menu-item <?php echo ($secao_ativa == 'pedidos' ? 'ativo' : ''); ?>">📝 Pedidos</a>
                <a href="adm.php?secao=vendas" class="menu-item <?php echo ($secao_ativa == 'vendas' ? 'ativo' : ''); ?>">📈 Vendas & Relatórios</a>
                
                <a href="../INICIO/logout.php" class="menu-item deslogar">Deslogar conta</a>
            </nav>
        </aside>

        <section class="perfil-conteudo">
            
            <?php if ($mensagem): ?>
                <div class="alerta-global <?php echo $classe; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <div class="conteudo-card">
                
                <?php 
                // 3. SWITCH CASE PARA CARREGAR O CONTEÚDO CORRETO
                switch ($secao_ativa):
                    
                    case 'produtos':
                        // O arquivo está em ADM/produtos/produtos.php
                        if (file_exists('produtos/produtos.php')) {
                            include 'produtos/produtos.php'; 
                        } else {
                            echo "<h2>Gerenciamento de Produtos</h2><hr><p>Crie o arquivo: ADM/produtos/produtos.php</p>";
                        }
                        break;
                    
                    case 'clientes':
                        // O arquivo está em ADM/clientes/clientes.php
                        if (file_exists('clientes/clientes.php')) {
                            include 'clientes/clientes.php';
                        } else {
                            echo "<h2>Visualizar e Apagar Clientes</h2><hr><p>Crie o arquivo: ADM/clientes/clientes.php</p>";
                        }
                        break;
                    
                    case 'pedidos':
                        // O arquivo está em ADM/pedidos/pedidos.php
                        if (file_exists('pedidos/pedidos.php')) {
                            echo '<h2>Gerenciamento de Pedidos e Status</h2><hr>';
                            include 'pedidos/pedidos.php'; 
                        } else {
                            echo "<h2>Gerenciamento de Pedidos e Status</h2><hr><p>Crie o arquivo: ADM/pedidos/pedidos.php</p>";
                        }
                        break;

                    case 'vendas':
                        
                        if (file_exists('relatorios/vendas.php')) {
                            include 'relatorios/vendas.php'; 
                        }
                        break;
                        
                    default:
                        echo "<h2>Seção não encontrada.</h2>";
                        break;
                endswitch;
                ?>

            </div>
        </section>
        
    </main>
</body>
</html>
<?php 
// Fecha a conexão com o banco de dados
$conn->close(); 
?>