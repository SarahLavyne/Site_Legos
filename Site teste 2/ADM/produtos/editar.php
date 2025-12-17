<?php
// Este arquivo é incluído por ADMIN/produtos/produtos.php

$produto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($produto_id == 0) {
    echo '<div class="alerta-global alerta-erro">ID do produto não especificado para edição.</div>';
    echo '<a href="index.php?secao=produtos" class="btn-secondary">Voltar</a>';
    return; // Para o processamento
}

// 1. Busca os dados atuais do produto
$sql_produto = "SELECT * FROM produtos WHERE id = $produto_id";
$resultado = $conn->query($sql_produto);

if ($resultado->num_rows === 0) {
    echo '<div class="alerta-global alerta-erro">Produto não encontrado.</div>';
    echo '<a href="index.php?secao=produtos" class="btn-secondary">Voltar</a>';
    return;
}

$produto = $resultado->fetch_assoc();

// Categorias disponíveis (deve ser o mesmo array do adicionar.php)
$categorias = ["Carro", "Construção", "Personagens"];
?>

<form action="processa_adm.php" method="POST" enctype="multipart/form-data" class="form-admin">
    <input type="hidden" name="acao" value="editar_produto">
    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

    <div class="form-group">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="descricao">Descrição Completa:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option value="">Selecione a Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat; ?>" 
                        <?php echo ($produto['categoria'] === $cat ? 'selected' : ''); ?>>
                    <?php echo $cat; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-row" style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0.01" 
                   value="<?php echo $produto['preco']; ?>" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="estoque">Estoque:</label>
            <input type="number" id="estoque" name="estoque" min="0" 
                   value="<?php echo $produto['estoque']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Imagem Atual:</label>
        <img src="../INICIO/imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" 
             alt="Imagem do Produto" width="100" style="display: block; margin-bottom: 10px; border-radius: 4px;">
        <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($produto['imagem_url']); ?>">
    </div>
    
    <div class="form-group">
        <label for="nova_imagem">Nova Imagem (Opcional):</label>
        <input type="file" id="nova_imagem" name="nova_imagem" accept="image/*">
        <small style="color: #6c757d;">Deixe em branco para manter a imagem atual.</small>
    </div>

    <div class="form-group">
            <label for="destaque">Produto em Destaque:</label>
            <input type="checkbox" id="destaque" name="destaque" value="1" style="width: auto;">
            <small style="display: block; color: #6c757d;">Marque se este produto deve aparecer na página inicial da loja.</small>
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 20px;">
            Salvar Novo Produto
        </button>
    </form>
</form>