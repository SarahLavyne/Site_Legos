<?php
session_start();
include '../conexao.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario_input = $conn->real_escape_string(trim($_POST['usuario']));
    $senha_input = $_POST['senha']; 
    
    $sql = "SELECT id, nome, senha, perfil FROM usuarios WHERE email = '$usuario_input' LIMIT 1";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        $hash_senha = $usuario['senha'];

        if (password_verify($senha_input, $hash_senha)) {
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['perfil'] = $usuario['perfil']; 
            
            $conn->close();
            
            if ($usuario['perfil'] === 'administrador') {
                header("Location: ../ADM/index.php");
                exit;
            } else {
                header("Location: ../INICIO/index.php");
                exit;
            }
            
        } else {
            $conn->close();
            header("Location: login.php?status=login_erro");
            exit;
        }
    } else {
        $conn->close();
        header("Location: login.php?status=login_erro");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>