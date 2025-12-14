<?php
session_start();
include '../conexao.php'; // Inclui a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Captura e limpeza dos dados
    $usuario_input = $conn->real_escape_string(trim($_POST['usuario']));
    $senha_input = $_POST['senha']; 
    
    // 3. Busca o usuário no banco de dados, INCLUINDO O CAMPO 'perfil'
    $sql = "SELECT id, nome, senha, perfil FROM usuarios WHERE email = '$usuario_input' LIMIT 1";
    $resultado = $conn->query($sql);

    // 4. Verifica se o usuário foi encontrado
    if ($resultado && $resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        $hash_senha = $usuario['senha'];

        // 5. Verifica a senha usando password_verify
        if (password_verify($senha_input, $hash_senha)) {
            
            // Sucesso no Login!
            
            // 6. Inicia a sessão e armazena os dados importantes, INCLUINDO O PERFIL
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['perfil'] = $usuario['perfil']; // Salva 'administrador' ou 'cliente'
            
            $conn->close();
            
            // 7. Redireciona com base no perfil (USANDO 'administrador')
            if ($usuario['perfil'] === 'administrador') {
                // Redireciona o administrador para o Painel
                header("Location: ../ADM/index.php");
                exit;
            } else {
                // Redireciona o cliente para a Loja
                header("Location: ../INICIO/index.php");
                exit;
            }
            
        } else {
            // Falha na Senha
            $conn->close();
            header("Location: login.php?status=login_erro");
            exit;
        }
    } else {
        // Usuário não encontrado
        $conn->close();
        header("Location: login.php?status=login_erro");
        exit;
    }
} else {
    // Se a página foi acessada diretamente (GET) sem enviar o formulário
    header("Location: login.php");
    exit;
}
?>