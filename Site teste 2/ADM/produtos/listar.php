<?php
// Este arquivo é incluído por ADM/produtos/produtos.php, que por sua vez
// é incluído por ADM/adm.php. A variável $conn está disponível aqui.

// 1. Lógica para buscar os produtos no banco de dados
// Busca todos os campos necessários, incluindo 'categoria'
$sql_produtos = "SELECT id, nome, preco, categoria, estoque, imagem_url FROM produtos ORDER BY id DESC";
$resultado_produtos = $conn->query($sql_produtos);
?>

<?php if ($resultado_produtos->num_rows > 0): ?>

    <table class="tabela-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome do Produto</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($produto = $resultado_produtos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $produto['id']; ?></td>
                <td>
                    <img src="../INICIO/imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" 
                         alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                         width="50" style="border-radius: 4px;">
                </td>
                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                <td>
                    <?php 
                        // Lógica para destacar estoque baixo
                        $estoque_classe = '';
                        if ($produto['estoque'] == 0) {
                            $estoque_classe = 'text-erro';
                        } elseif ($produto['estoque'] < 10) {
                            $estoque_classe = 'text-aviso';
                        }
                        
                        echo "<span class='{$estoque_classe}'>" . $produto['estoque'] . "</span>";
                    ?>
                </td>
                <td class="coluna-acoes">
                    <a href="adm.php?secao=produtos&acao=editar&id=<?php echo $produto['id']; ?>" class="btn-editar">
                        Editar
                    </a>
                    
                    <a href="processa_adm.php?acao=apagar_produto&id=<?php echo $produto['id']; ?>" 
                       class="btn-apagar" 
                       onclick="return confirm('Tem certeza que deseja apagar o produto <?php echo htmlspecialchars($produto['nome']); ?>?');">
                        Apagar
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="alerta-global alerta-aviso">
        Não há produtos cadastrados no momento. 
        <a href="adm.php?secao=produtos&acao=adicionar">Adicione um novo!</a>
    </div>
<?php endif; ?>