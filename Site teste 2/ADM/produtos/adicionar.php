<form action="processa_adm.php" method="POST" enctype="multipart/form-data" class="form-admin">
    <input type="hidden" name="acao" value="adicionar_produto">

    <div class="form-group">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    
    <div class="form-group">
        <label for="descricao">Descrição Completa:</label>
        <textarea id="descricao" name="descricao" required></textarea>
    </div>

    <div class="form-group">
        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option value="">Selecione a Categoria</option>
            <option value="Carros">Carros</option>
            <option value="Construcoes">Construções</option>
            <option value="Personagens">Personagens</option>
        </select>
    </div>

    <div class="form-row" style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0.01" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="estoque">Estoque Inicial:</label>
            <input type="number" id="estoque" name="estoque" min="0" required>
        </div>
    </div>

    <div class="form-group">
        <label for="imagem">Imagem do Produto:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>
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