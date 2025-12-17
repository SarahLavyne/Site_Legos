<?php
session_start();
include '../conexao.php'; 

if (!isset($_SESSION['usuario_id'])) {
    echo "Erro: Usuário não logado.";
    exit;
}

if (isset($_POST['produto_id'])) {
    $produto_id = $conn->real_escape_string($_POST['produto_id']);
    $usuario_id = $_SESSION['usuario_id'];
    $quantidade = 1;

    $check_sql = "SELECT id, quantidade FROM carrinho 
                  WHERE usuario_id = '$usuario_id' AND produto_id = '$produto_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        $nova_quantidade = $row['quantidade'] + 1;
        $update_sql = "UPDATE carrinho SET quantidade = '$nova_quantidade' 
                       WHERE id = '" . $row['id'] . "'";
        
        if ($conn->query($update_sql) === TRUE) {
            echo "Quantidade atualizada!";
        } else {
            echo "Erro ao atualizar: " . $conn->error;
        }
    } else {
        $insert_sql = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) 
                       VALUES ('$usuario_id', '$produto_id', '$quantidade')";
        
        if ($conn->query($insert_sql) === TRUE) {
            echo "Produto adicionado!";
        } else {
            echo "Erro ao inserir: " . $conn->error;
        }
    }
} else {
    echo "Erro: Produto não identificado.";
}

$conn->close();
?>