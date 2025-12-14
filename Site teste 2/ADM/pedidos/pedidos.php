<?php
// Este arquivo é incluído por ADM/adm.php. A variável $conn está disponível.

// Define a ação padrão (listar, ver_detalhes, editar_status)
$acao_pedido = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

// Lógica de inclusão de sub-páginas
if ($acao_pedido === 'ver_detalhes') {
    // Iremos desenvolver os detalhes depois
    echo "<h2>Detalhes do Pedido</h2><hr><p>Página de detalhes em desenvolvimento.</p>"; 
} elseif ($acao_pedido === 'editar_status') {
    // Iremos desenvolver a edição de status a seguir
    include 'editar_status.php';
} else {
    // PADRÃO: Listar pedidos
    include 'listar_pedidos.php'; 
}
?>