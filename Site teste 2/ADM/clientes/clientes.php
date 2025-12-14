<?php
// Este arquivo é incluído por ADM/adm.php. A variável $conn está disponível.

// 1. Busca todos os usuários com perfil 'cliente'
$sql_clientes = "SELECT id, nome, email, celular, cpf, data_cadastro FROM usuarios WHERE perfil = 'cliente' ORDER BY data_cadastro DESC";
$resultado_clientes = $conn->query($sql_clientes);
?>

<div class="conteudo-header">
    <h2>👥 Listagem de Clientes Cadastrados</h2>
    </div>

<hr>

<?php if ($resultado_clientes->num_rows > 0): ?>

    <table class="tabela-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Celular</th>
                <th>CPF</th>
                <th>Desde</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($cliente = $resultado_clientes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $cliente['id']; ?></td>
                <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                <td><?php echo htmlspecialchars($cliente['celular']); ?></td>
                <td><?php echo htmlspecialchars($cliente['cpf']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></td>
                <td class="coluna-acoes">
                    <a href="../processa_adm.php?acao=apagar_cliente&id=<?php echo $cliente['id']; ?>" 
                       class="btn-apagar" 
                       onclick="return confirm('ATENÇÃO: Tem certeza que deseja APAGAR o cliente <?php echo htmlspecialchars($cliente['nome']); ?>? Esta ação é irreversível.');">
                        Apagar
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="alerta-global alerta-aviso">
        Nenhum cliente cadastrado ainda.
    </div>
<?php endif; ?>