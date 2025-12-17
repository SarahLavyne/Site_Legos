<?php   
$acao_produto = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
?>

<div class="conteudo-header">
    <?php 
    if ($acao_produto === 'listar'): 
    ?>
        <h2>Listagem de Produtos</h2>
        <a href="adm.php?secao=produtos&acao=adicionar" class="btn-primary">
            + Adicionar Novo Produto
        </a>
    <?php elseif ($acao_produto === 'adicionar'): ?>
        <h2>➕ Adicionar Novo Produto</h2>
        <a href="adm.php?secao=produtos" class="btn-secondary">
            ← Voltar para a Lista
        </a>
    <?php elseif ($acao_produto === 'editar'): ?>
        <h2>✏️ Editar Produto</h2>
        <a href="adm.php?secao=produtos" class="btn-secondary">
            ← Voltar para a Lista
        </a>
    <?php endif; ?>
</div>

<hr>

<?php 
if ($acao_produto == 'adicionar') {
    include 'adicionar.php';
    
} elseif ($acao_produto == 'editar') {
    include 'editar.php'; 
    
} else {
    include 'listar.php';
}
?>