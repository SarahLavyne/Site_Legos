<?php
session_start();
include '../conexao.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$conn->begin_transaction(); // Inicia uma transação segura

try {
    // 1. CALCULAR O TOTAL DO CARRINHO E COLETAR ITENS
    $sql_carrinho = "SELECT c.quantidade, p.id AS produto_id, p.preco 
                     FROM carrinho c
                     JOIN produtos p ON c.produto_id = p.id
                     WHERE c.usuario_id = '$usuario_id'";

    $result_carrinho = $conn->query($sql_carrinho);
    $itens_carrinho = [];
    $total_final = 0;
    
    if ($result_carrinho->num_rows === 0) {
        throw new Exception("O carrinho está vazio.");
    }

    while ($item = $result_carrinho->fetch_assoc()) {
        $subtotal = $item['quantidade'] * $item['preco'];
        $total_final += $subtotal;
        $itens_carrinho[] = $item;

        // 1.1. VERIFICAÇÃO BÁSICA DE ESTOQUE (Evita que o usuário compre mais do que há no estoque)
        // Isso seria mais robusto no checkout.php, mas fazemos aqui para evitar erro fatal
        $sql_estoque = "SELECT estoque FROM produtos WHERE id = " . $item['produto_id'];
        $res_estoque = $conn->query($sql_estoque)->fetch_assoc();
        if ($item['quantidade'] > $res_estoque['estoque']) {
             throw new Exception("Estoque insuficiente para o produto ID: " . $item['produto_id']);
        }
    }

    // 2. RECUPERAÇÃO DO ENDEREÇO DO USUÁRIO (Usando o que está no perfil)
    $sql_endereco = "SELECT cep, endereco FROM usuarios WHERE id = '$usuario_id'";
    $res_endereco = $conn->query($sql_endereco)->fetch_assoc();
    $endereco_completo = "CEP: " . $res_endereco['cep'] . " | Endereço: " . $res_endereco['endereco'];

    // 3. INSERIR NA TABELA 'pedidos'
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total, endereco_entrega, status) VALUES (?, ?, ?, 'Concluído')");
    $stmt->bind_param("ids", $usuario_id, $total_final, $endereco_completo);
    $stmt->execute();
    $pedido_id = $conn->insert_id; // Pega o ID do pedido que acabou de ser criado
    $stmt->close();
    
    // 4. INSERIR NA TABELA 'itens_pedido' E BAIXAR ESTOQUE
    foreach ($itens_carrinho as $item) {
        // Insere o item do pedido
        $stmt_item = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt_item->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
        $stmt_item->execute();
        $stmt_item->close();

        // Baixa o estoque do produto
        $stmt_estoque = $conn->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
        $stmt_estoque->bind_param("ii", $item['quantidade'], $item['produto_id']);
        $stmt_estoque->execute();
        $stmt_estoque->close();
    }

    // 5. LIMPAR O CARRINHO
    $sql_limpar = "DELETE FROM carrinho WHERE usuario_id = '$usuario_id'";
    $conn->query($sql_limpar);
    
    // 6. FINALIZAR TRANSAÇÃO
    $conn->commit();
    $conn->close();

    // Redireciona para a página de sucesso
    header("Location: ../INICIO/index.php?status=pedido_sucesso");
    exit;

} catch (Exception $e) {
    // Se algo deu errado, desfaz tudo (rollback)
    $conn->rollback();
    $conn->close();
    
    error_log("Erro no checkout: " . $e->getMessage());
    // Redireciona com mensagem de erro
    header("Location: carrinho.php?status=erro_checkout&msg=" . urlencode($e->getMessage()));
    exit;
}
?>