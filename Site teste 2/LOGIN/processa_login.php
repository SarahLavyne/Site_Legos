<?php
session_start();
include '../conexao.php'; // Inclui a conexão com o banco de dados

// 1. Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Captura e limpeza dos dados
    // O usuário pode inserir E-MAIL ou NOME DE USUÁRIO (se você usasse um)
    $usuario_input = $conn->real_escape_string(trim($_POST['usuario']));
    $senha_input = $_POST['senha']; // Senha pura, será verificada com hash
    
    // 3. Busca o usuário no banco de dados usando o e-mail (ou o nome de usuário)
    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = '$usuario_input'";
    $resultado = $conn->query($sql);

    // 4. Verifica se o usuário foi encontrado
    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        $hash_senha = $usuario['senha'];

        // 5. Verifica a senha usando password_verify
        if (password_verify($senha_input, $hash_senha)) {
            
            // Sucesso no Login!
            
            // Inicia a sessão e armazena os dados importantes
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            // Fecha a conexão antes de redirecionar
            $conn->close();
            
            // Redireciona para a página principal (INICIO)
            header("Location: ../INICIO/index.php");
            exit;
            
        } else {
            // Falha na Senha
            
            $conn->close();
            // Redireciona de volta com status de erro
            header("Location: login.php?status=login_erro");
            exit;
        }
    } else {
        // Usuário não encontrado
        
        $conn->close();
        // Redireciona de volta com status de erro
        header("Location: login.php?status=login_erro");
        exit;
    }
} else {
    // Se a página foi acessada diretamente (GET) sem enviar o formulário
    header("Location: login.php");
    exit;
}

// O $conn->close() aqui fora é inalcançável e desnecessário, 
// pois ele está fechado em todos os caminhos de sucesso/erro.
?>