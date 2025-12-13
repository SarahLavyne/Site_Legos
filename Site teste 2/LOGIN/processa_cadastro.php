<?php
// 1. Incluir a conexão com o banco de dados
include '../conexao.php'; // Usa '../' porque o conexao.php está na pasta acima

// 2. Verificar se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Adiciona uma verificação para garantir que pelo menos um campo principal (nome) foi preenchido.
    // Isso é redundante com a validação do passo 4, mas elimina warnings se o POST estiver vazio.
    if (isset($_POST['nome'])) {
        
        // 3. Coletar e limpar os dados do formulário
        $nome = $conn->real_escape_string($_POST['nome']);
        $email = $conn->real_escape_string($_POST['email']);
        
        // Remover caracteres de máscara para armazenar limpo
        // É crucial usar o operador ternário para evitar warnings se o campo vier totalmente vazio
        $celular = isset($_POST['celular']) ? preg_replace('/[^0-9]/', '', $_POST['celular']) : '';
        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']); // O CPF é obrigatório, o empty() abaixo checa se está vazio
        $cep = isset($_POST['cep']) ? preg_replace('/[^0-9]/', '', $_POST['cep']) : '';
        
        $senha_pura = $_POST['senha'];
        
        // 4. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS (PHP lado do servidor)
        if (empty($nome) || empty($email) || empty($senha_pura) || empty($cpf)) {
            die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
        }

        // 5. CRIPTOGRAFIA DE SENHA (Obrigatório por segurança)
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
        
        // 6. VERIFICAÇÃO DE DUPLICIDADE (Email e CPF)
        
        // A. Verifica Email
        $sql_email = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultado_email = $conn->query($sql_email);
        
        if ($resultado_email->num_rows > 0) {
            die("Erro: Este e-mail já está cadastrado. Tente fazer login.");
        }
        
        // B. Verifica CPF
        $sql_cpf = "SELECT id FROM usuarios WHERE cpf = '$cpf'";
        $resultado_cpf = $conn->query($sql_cpf);
        
        if ($resultado_cpf->num_rows > 0) {
            die("Erro: Este CPF já está cadastrado.");
        }
        
        // 7. INSERÇÃO DOS DADOS NA TABELA USUARIOS
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha, celular, cpf, cep, perfil) 
                        VALUES ('$nome', '$email', '$senha_hash', '$celular', '$cpf', '$cep', 'cliente')";

        if ($conn->query($sql_inserir) === TRUE) {
            // Sucesso: Redireciona para a página de login ou página inicial
            echo "Cadastro realizado com sucesso! Você será redirecionado em 3 segundos.";
            header("Refresh:3; url=login.php"); 
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }

        // 8. Fecha a conexão
        $conn->close();
    }
} else {
    // Se a página foi acessada diretamente sem o formulário POST
    header("Location: login.php");
    exit;
}
?>