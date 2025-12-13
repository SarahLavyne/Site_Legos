<?php
session_start();
include '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Erro: Você precisa estar logado para adicionar produtos ao carrinho.");
}

// Verifica se o ID do produto foi enviado (usando método POST ou GET)
if (isset($_POST['produto_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $produto_id = $conn->real_escape_string($_POST['produto_id']);
    $quantidade = 1; // Por padrão, adiciona 1 unidade

    // 1. Verifica se o produto já está no carrinho
    $sql_check = "SELECT id, quantidade FROM carrinho WHERE usuario_id = '$usuario_id' AND produto_id = '$produto_id'";
    $resultado_check = $conn->query($sql_check);

    if ($resultado_check->num_rows > 0) {
        // Se já existe, apenas aumenta a quantidade
        $item = $resultado_check->fetch_assoc();
        $nova_quantidade = $item['quantidade'] + 1;
        $sql_update = "UPDATE carrinho SET quantidade = '$nova_quantidade' WHERE id = " . $item['id'];
        $conn->query($sql_update);
        echo "Quantidade atualizada no carrinho!";
    } else {
        // Se não existe, insere um novo item
        $sql_insert = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ('$usuario_id', '$produto_id', '$quantidade')";
        $conn->query($sql_insert);
        echo "Produto adicionado ao carrinho!";
    }

    $conn->close();
} else {
    echo "Erro: ID do produto não fornecido.";
}
?>