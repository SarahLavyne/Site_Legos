<?php
session_start();
include '../conexao.php'; 

// 1. VERIFICAÇÃO DE SEGURANÇA BÁSICA
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}

// Garante que só processamos via método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: perfil.php?secao=seguranca");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$status_redirect = "Location: perfil.php?secao=seguranca&status_senha="; // Base para redirecionamento

// 2. CAPTURA E LIMPEZA DOS DADOS RECEBIDOS
// Atenção: Não use real_escape_string em dados que serão verificados ou hasheados (como senhas), 
// mas é bom garantir que não estejam vazios.
$senha_antiga = trim($_POST['senha_antiga']);
$senha_nova = trim($_POST['senha_nova']);
$senha_confirmar = trim($_POST['senha_confirmar']);

// 3. VALIDAÇÃO: Senhas Novas Coincidem
if ($senha_nova !== $senha_confirmar) {
    $conn->close();
    header($status_redirect . "erro_diferente");
    exit;
}

// 4. RECUPERAÇÃO DO HASH DA SENHA ANTIGA NO BANCO
$sql_hash = "SELECT senha FROM usuarios WHERE id = '$usuario_id'";
$resultado = $conn->query($sql_hash);

if ($resultado && $resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $hash_antigo = $usuario['senha'];

    // 5. VERIFICAÇÃO DA SENHA ANTIGA (O PASSO MAIS IMPORTANTE!)
    if (password_verify($senha_antiga, $hash_antigo)) {
        
        // 6. CRIAÇÃO DO NOVO HASH PARA A NOVA SENHA
        $novo_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        
        // 7. ATUALIZAÇÃO DA SENHA NO BANCO
        $sql_update = "UPDATE usuarios SET senha = '$novo_hash' WHERE id = '$usuario_id'";
        
        if ($conn->query($sql_update) === TRUE) {
            
            $conn->close();
            // Sucesso! Redireciona com flag de sucesso
            header($status_redirect . "sucesso");
            exit;
        } else {
            // Erro na atualização do banco
            error_log("Erro de UPDATE de senha: " . $conn->error);
            $conn->close();
            header($status_redirect . "erro_update");
            exit;
        }
    } else {
        // Senha antiga incorreta
        $conn->close();
        header($status_redirect . "erro_antiga");
        exit;
    }
}

// Erro genérico (ex: usuário não encontrado)
$conn->close();
header($status_redirect . "erro");
exit;
?>