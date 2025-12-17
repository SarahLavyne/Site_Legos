<?php
session_start();
include '../conexao.php'; 

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    echo "0.00";
    exit;
}

$carrinho_id = $_POST['carrinho_id'] ?? null;
$acao = $_POST['acao'] ?? null;
$quantidade = $_POST['quantidade'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

if ($carrinho_id && $acao) {
    
    if ($acao === 'remover') {
        $carrinho_id_seguro = $conn->real_escape_string($carrinho_id);
        
        $sql = "DELETE FROM carrinho 
                WHERE id = '$carrinho_id_seguro' AND usuario_id = '$usuario_id'";
        $conn->query($sql);

    }
    
    elseif ($acao === 'atualizar' && $quantidade > 0) {
        $quantidade_segura = $conn->real_escape_string($quantidade);
        $carrinho_id_seguro = $conn->real_escape_string($carrinho_id);
        
        $sql = "UPDATE carrinho SET quantidade = '$quantidade_segura' 
                WHERE id = '$carrinho_id_seguro' AND usuario_id = '$usuario_id'";
        $conn->query($sql);
    }
    
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
        if ($acao === 'remover') {
        header("Location: carrinho.php"); 
        exit;
    } else {
        echo number_format($novo_total_carrinho, 2, '.', ''); 
        exit;
    }

} else {
    $conn->close();
    header("Location: carrinho.php");
    exit;
}
?>