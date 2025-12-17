<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Erro: Você precisa estar logado para adicionar produtos ao carrinho.");
}

if (isset($_POST['produto_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $produto_id = $conn->real_escape_string($_POST['produto_id']);
    $quantidade = 1; // Por padrão, adiciona 1 unidade

    $sql_check = "SELECT id, quantidade FROM carrinho WHERE usuario_id = '$usuario_id' AND produto_id = '$produto_id'";
    $resultado_check = $conn->query($sql_check);

    if ($resultado_check->num_rows > 0) {
        $item = $resultado_check->fetch_assoc();
        $nova_quantidade = $item['quantidade'] + 1;
        $sql_update = "UPDATE carrinho SET quantidade = '$nova_quantidade' WHERE id = " . $item['id'];
        $conn->query($sql_update);
        echo "Quantidade atualizada no carrinho!";
    } else {
        $sql_insert = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ('$usuario_id', '$produto_id', '$quantidade')";
        $conn->query($sql_insert);
        echo "Produto adicionado ao carrinho!";
    }

    $conn->close();
} else {
    echo "Erro: ID do produto não fornecido.";
}
?>