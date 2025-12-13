<?php
// Inicia a sessão para armazenar o status do usuário (necessário para logar)
session_start();

// Inclui a conexão (Ajuste o caminho se necessário, ex: '../../conexao.php')
include '../conexao.php'; 

// 1. Verifica se o formulário de login foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Coletar e limpar os dados
    $email = $conn->real_escape_string($_POST['usuario']); // Campo "E-mail ou Usuário" do formulário
    $senha_digitada = $_POST['senha'];

    // 3. Consulta ao Banco de Dados para buscar o usuário pelo e-mail
    $sql = "SELECT id, nome, senha, perfil FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);

    // 4. Verifica se o usuário foi encontrado
    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        
        // 5. Verifica a senha criptografada
        if (password_verify($senha_digitada, $usuario['senha'])) {
            
            // SENHA CORRETA: Cria a sessão de login
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['perfil'] = $usuario['perfil']; // 'cliente' ou 'administrador'
            
            // 6. Redirecionamento baseado no perfil
            if ($usuario['perfil'] === 'administrador') {
                // Redireciona para o painel do ADM (próximo passo)
                header("Location: ../painel_adm/dashboard.php"); 
            } else {
                // Redireciona para a página principal ou área do cliente
                header("Location: ../INICIO/index.php"); 
            }
            exit;

        } else {
            // Senha Incorreta
            echo "Erro: Senha incorreta. <a href='login.php'>Tentar novamente</a>.";
        }
        
    } else {
        // Usuário não encontrado
        echo "Erro: Usuário não encontrado. <a href='login.php'>Tentar novamente</a>.";
    }

    $conn->close();

} else {
    // Acesso direto à página de processamento
    header("Location: login.php");
    exit;
}
?>