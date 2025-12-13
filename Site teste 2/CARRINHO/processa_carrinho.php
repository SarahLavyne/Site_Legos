<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    // Acesso negado ou método incorreto
    header("Location: carrinho.php");
    exit;
}

$carrinho_id = $_POST['carrinho_id'] ?? null;
$acao = $_POST['acao'] ?? null;
$quantidade = $_POST['quantidade'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

if ($carrinho_id && $acao) {
    
    // --- Ação 1: REMOVER ITEM ---
    if ($acao === 'remover') {
        $sql = "DELETE FROM carrinho WHERE id = '$carrinho_id' AND usuario_id = '$usuario_id'";
        if ($conn->query($sql) === TRUE) {
            // Sucesso
        } else {
            // Erro ao remover
        }
    }
    
    // --- Ação 2: ATUALIZAR QUANTIDADE ---
    elseif ($acao === 'atualizar' && $quantidade > 0) {
        $sql = "UPDATE carrinho SET quantidade = '$quantidade' 
                WHERE id = '$carrinho_id' AND usuario_id = '$usuario_id'";
        if ($conn->query($sql) === TRUE) {
            // Sucesso
        } else {
            // Erro ao atualizar
        }
    }
}

$conn->close();

// Redireciona sempre de volta para a página do carrinho após a ação
header("Location: carrinho.php");
exit;
?>