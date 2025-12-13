<?php
session_start();
// O include precisa subir dois níveis: CARRINHO/ -> SITE TESTE 2/
include '../conexao.php'; 

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    // Retorna um valor inválido ou redireciona
    echo "0.00";
    exit;
}

$carrinho_id = $_POST['carrinho_id'] ?? null;
$acao = $_POST['acao'] ?? null;
$quantidade = $_POST['quantidade'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

if ($carrinho_id && $acao) {
    
    // 1. --- Ação: REMOVER ITEM ---
    if ($acao === 'remover') {
        $carrinho_id_seguro = $conn->real_escape_string($carrinho_id);
        
        $sql = "DELETE FROM carrinho 
                WHERE id = '$carrinho_id_seguro' AND usuario_id = '$usuario_id'";
        $conn->query($sql);

        // Se a remoção for feita via POST normal (não AJAX), redirecione
        // Como o botão "X" no HTML usa POST sem JS, ele recarregará a página.
    }
    
    // 2. --- Ação: ATUALIZAR QUANTIDADE ---
    elseif ($acao === 'atualizar' && $quantidade > 0) {
        $quantidade_segura = $conn->real_escape_string($quantidade);
        $carrinho_id_seguro = $conn->real_escape_string($carrinho_id);
        
        $sql = "UPDATE carrinho SET quantidade = '$quantidade_segura' 
                WHERE id = '$carrinho_id_seguro' AND usuario_id = '$usuario_id'";
        $conn->query($sql);
    }
    
    // --- CALCULA NOVO TOTAL GERAL APÓS A OPERAÇÃO (PARA RETORNAR AO AJAX) ---
    // Esta consulta é executada tanto após ATUALIZAR quanto após REMOVER
    $sql_total = "SELECT SUM(c.quantidade * p.preco) AS total 
                  FROM carrinho c
                  JOIN produtos p ON c.produto_id = p.id
                  WHERE c.usuario_id = '$usuario_id'";
    
    $res_total = $conn->query($sql_total);
    $novo_total_carrinho = 0;
    
    if ($res_total && $res_total->num_rows > 0) {
        $novo_total_carrinho = $res_total->fetch_assoc()['total'];
    }

    $conn->close();
    
    // RETORNA O NOVO TOTAL GERAL (para o JavaScript ou POST normal)
    if ($acao === 'remover') {
        // Redireciona de volta após a remoção (POST normal)
        header("Location: carrinho.php"); 
        exit;
    } else {
        // Retorna o valor para o AJAX (atualizar quantidade)
        echo number_format($novo_total_carrinho, 2, '.', ''); 
        exit;
    }

} else {
    $conn->close();
    // Acesso direto ou dados incompletos
    header("Location: carrinho.php");
    exit;
}
?>