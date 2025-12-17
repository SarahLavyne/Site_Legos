<?php
session_start(); 
include '../conexao.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['nome'])) {
        
        $nome = $conn->real_escape_string($_POST['nome']);
        $email = $conn->real_escape_string($_POST['email']);
        
        $celular_limpo = isset($_POST['celular']) ? preg_replace('/[^0-9]/', '', $_POST['celular']) : '';
        $cpf_limpo = preg_replace('/[^0-9]/', '', $_POST['cpf']);
        $cep_limpo = isset($_POST['cep']) ? preg_replace('/[^0-9]/', '', $_POST['cep']) : '';
        
        $senha_pura = $_POST['senha'];
        
        if (empty($nome) || empty($email) || empty($senha_pura) || empty($cpf_limpo)) {
            $conn->close();
            header("Location: login.php?status=cadastro_erro"); 
            exit;
        }

        $senha_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{6,}$/';
        
        if (!preg_match($senha_regex, $senha_pura)) {
            $conn->close();
            header("Location: login.php?status=cadastro_erro_senha");
            exit;
        }

        if (strlen($cpf_limpo) != 11) {
            $conn->close();
            header("Location: login.php?status=cadastro_erro_cpf");
            exit;
        }

        if (!empty($celular_limpo) && strlen($celular_limpo) < 10) {
             $conn->close();
            header("Location: login.php?status=cadastro_erro_celular");
            exit;
        }
        
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
        
        $sql_email = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultado_email = $conn->query($sql_email);
        
        $sql_cpf = "SELECT id FROM usuarios WHERE cpf = '$cpf_limpo'"; 
        $resultado_cpf = $conn->query($sql_cpf);

        if ($resultado_email->num_rows > 0 || $resultado_cpf->num_rows > 0) {
            $conn->close();
            header("Location: login.php?status=cadastro_erro_email"); 
            exit;
        }
        
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha, celular, cpf, cep, perfil) 
                        VALUES ('$nome', '$email', '$senha_hash', '$celular_limpo', '$cpf_limpo', '$cep_limpo', 'cliente')";

        if ($conn->query($sql_inserir) === TRUE) {
            $conn->close();
            header("Location: login.php?status=cadastro_sucesso"); 
            exit;
        } else {
            error_log("Erro ao cadastrar: " . $conn->error);
            $conn->close();
            header("Location: login.php?status=cadastro_erro"); 
            exit;
        }
    }
} else {
    header("Location: login.php");
    exit;
}
?>