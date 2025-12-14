<?php   
// Define qual ação (listar, adicionar, editar) será executada, o padrão é 'listar'
$acao_produto = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
?>

<div class="conteudo-header">
    <?php 
    // Títulos dinâmicos com base na ação
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
// 1. Lógica para incluir o arquivo correto
// ATENÇÃO: As inclusões não usam 'produtos/' no caminho, pois este arquivo
// já está dentro da pasta ADM/produtos/
if ($acao_produto == 'adicionar') {
    // Carrega o formulário para adicionar (ADM/produtos/adicionar.php)
    include 'adicionar.php';
    
} elseif ($acao_produto == 'editar') {
    // Carrega o formulário para edição (ADM/produtos/editar.php)
    include 'editar.php'; 
    
} else {
    // Carrega a listagem padrão (ADM/produtos/listar.php)
    include 'listar.php';
}
?>