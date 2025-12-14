<?php
session_start(); 
include '../conexao.php'; 

// 2. Verificar se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['nome'])) {
        
        // 3. Coletar e limpar os dados do formulário
        $nome = $conn->real_escape_string($_POST['nome']);
        $email = $conn->real_escape_string($_POST['email']);
        
        // 3.1 Limpeza dos campos que receberam máscaras (Remove tudo que não for número)
        $celular_limpo = isset($_POST['celular']) ? preg_replace('/[^0-9]/', '', $_POST['celular']) : '';
        $cpf_limpo = preg_replace('/[^0-9]/', '', $_POST['cpf']);
        $cep_limpo = isset($_POST['cep']) ? preg_replace('/[^0-9]/', '', $_POST['cep']) : '';
        
        $senha_pura = $_POST['senha'];
        
        // 4. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS
        if (empty($nome) || empty($email) || empty($senha_pura) || empty($cpf_limpo)) {
            $conn->close();
            // Redireciona com erro genérico de campo obrigatório
            header("Location: login.php?status=cadastro_erro"); 
            exit;
        }

        // 5. VALIDAÇÃO DE FORÇA DA SENHA
        // Regra: Mínimo 6 dígitos, uma minúscula, uma maiúscula, um caracter especial (non-alphanumeric)
        $senha_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{6,}$/';
        
        if (!preg_match($senha_regex, $senha_pura)) {
            $conn->close();
            // NOVO STATUS: Erro de Senha
            header("Location: login.php?status=cadastro_erro_senha");
            exit;
        }

        // 6. VALIDAÇÃO DE CPF (11 dígitos, após a limpeza)
        if (strlen($cpf_limpo) != 11) {
            $conn->close();
            // NOVO STATUS: Erro de CPF Inválido
            header("Location: login.php?status=cadastro_erro_cpf");
            exit;
        }

        // 7. VALIDAÇÃO DE CELULAR (Mínimo 10 dígitos, se preenchido)
        // Mínimo 10 dígitos para garantir DDD + Número completo
        if (!empty($celular_limpo) && strlen($celular_limpo) < 10) {
             $conn->close();
            // NOVO STATUS: Erro de Celular Inválido
            header("Location: login.php?status=cadastro_erro_celular");
            exit;
        }
        
        // 8. CRIPTOGRAFIA DE SENHA
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
        
        // 9. VERIFICAÇÃO DE DUPLICIDADE (Email e CPF)
        
        // Verifica Email
        $sql_email = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultado_email = $conn->query($sql_email);
        
        // Verifica CPF (usando o CPF limpo)
        $sql_cpf = "SELECT id FROM usuarios WHERE cpf = '$cpf_limpo'"; 
        $resultado_cpf = $conn->query($sql_cpf);

        if ($resultado_email->num_rows > 0 || $resultado_cpf->num_rows > 0) {
            $conn->close();
            // ERRO DE DUPLICIDADE: Redireciona com flag específica
            header("Location: login.php?status=cadastro_erro_email"); 
            exit;
        }
        
        // 10. INSERÇÃO DOS DADOS NA TABELA USUARIOS (usando as variáveis limpas)
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha, celular, cpf, cep, perfil) 
                        VALUES ('$nome', '$email', '$senha_hash', '$celular_limpo', '$cpf_limpo', '$cep_limpo', 'cliente')";

        if ($conn->query($sql_inserir) === TRUE) {
            // SUCESSO: Redireciona IMEDIATAMENTE
            $conn->close();
            header("Location: login.php?status=cadastro_sucesso"); 
            exit;
        } else {
            // Erro genérico no banco
            error_log("Erro ao cadastrar: " . $conn->error);
            $conn->close();
            header("Location: login.php?status=cadastro_erro"); 
            exit;
        }
    }
} else {
    // Se a página foi acessada diretamente sem o formulário POST
    header("Location: login.php");
    exit;
}
?>