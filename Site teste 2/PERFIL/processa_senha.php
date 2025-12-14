<?php
session_start();
include '../conexao.php'; 

// 1. VERIFICAÇÃO DE SEGURANÇA BÁSICA
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: perfil.php?secao=seguranca");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 2. CAPTURA DOS DADOS E VALIDAÇÃO BÁSICA
$senha_antiga = $_POST['senha_antiga'];
$senha_nova = $_POST['senha_nova'];
$senha_confirmar = $_POST['senha_confirmar'];

// Verifica se a nova senha e a confirmação são iguais
if ($senha_nova !== $senha_confirmar) {
    header("Location: perfil.php?secao=seguranca&status_senha=erro_diferente");
    exit;
}

// 3. RECUPERAÇÃO DO HASH DA SENHA ANTIGA NO BANCO
$sql_hash = "SELECT senha FROM usuarios WHERE id = '$usuario_id'";
$resultado = $conn->query($sql_hash);

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $hash_antigo = $usuario['senha'];

    // 4. VERIFICAÇÃO DA SENHA ANTIGA (O PASSO MAIS IMPORTANTE!)
    if (password_verify($senha_antiga, $hash_antigo)) {
        
        // 5. CRIAÇÃO DO NOVO HASH PARA A NOVA SENHA
        $novo_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        
        // 6. ATUALIZAÇÃO DA SENHA NO BANCO
        $sql_update = "UPDATE usuarios SET senha = '$novo_hash' WHERE id = '$usuario_id'";
        
        if ($conn->query($sql_update) === TRUE) {
            
            $conn->close();
            // Sucesso! Redireciona com flag de sucesso
            header("Location: perfil.php?secao=seguranca&status_senha=sucesso");
            exit;
        } else {
            // Erro na atualização do banco
            error_log("Erro de UPDATE de senha: " . $conn->error);
            $conn->close();
            header("Location: perfil.php?secao=seguranca&status_senha=erro_update");
            exit;
        }
    } else {
        // Senha antiga incorreta
        $conn->close();
        header("Location: perfil.php?secao=seguranca&status_senha=erro_antiga");
        exit;
    }
}

// Caso a query falhe
$conn->close();
header("Location: perfil.php?secao=seguranca&status_senha=erro");
exit;
?>