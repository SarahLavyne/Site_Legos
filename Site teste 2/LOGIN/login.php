<?php
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../INICIO/index.php");
    exit;
}

// 1. LÓGICA PARA CAPTURAR E PREPARAR AS MENSAGENS DE STATUS
$mensagem = '';
$classe = '';
$form_ativo = 'loginForm'; // Padrão: mostra o Login

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    
    // Feedback de LOGIN
    if ($status === 'login_erro') {
        $mensagem = "Credenciais inválidas. Verifique seu e-mail/usuário e senha.";
        $classe = 'alerta-erro';
    } 
    // Feedback de CADASTRO - SUCESSO
    elseif ($status === 'cadastro_sucesso') {
        $mensagem = "🎉 Cadastro realizado com sucesso! Você já pode acessar sua conta.";
        $classe = 'alerta-sucesso';
        $form_ativo = 'loginForm'; // Sucesso no cadastro leva ao login
    } 
    // Feedback de CADASTRO - ERROS ESPECÍFICOS (Mantém o formulário de cadastro ativo)
    elseif ($status === 'cadastro_erro_email') {
        $mensagem = "O e-mail ou CPF informado já está cadastrado.";
        $classe = 'alerta-erro';
        $form_ativo = 'cadastroForm';
    } 
    elseif ($status === 'cadastro_erro_senha') {
        $mensagem = "A senha é fraca. Ela deve ter no mínimo 6 dígitos, uma letra minúscula, uma maiúscula e um caractere especial.";
        $classe = 'alerta-erro';
        $form_ativo = 'cadastroForm';
    }
    elseif ($status === 'cadastro_erro_cpf') {
        $mensagem = "O CPF informado é inválido. Ele deve conter 11 dígitos.";
        $classe = 'alerta-erro';
        $form_ativo = 'cadastroForm';
    }
    elseif ($status === 'cadastro_erro_celular') {
        $mensagem = "O número de Celular é inválido. Por favor, inclua o DDD e o número (mínimo de 10 dígitos).";
        $classe = 'alerta-erro';
        $form_ativo = 'cadastroForm';
    }
    // Feedback de CADASTRO - GENÉRICO
    elseif ($status === 'cadastro_erro') {
        $mensagem = "Erro ao criar a conta. Por favor, tente novamente mais tarde.";
        $classe = 'alerta-erro';
        $form_ativo = 'cadastroForm';
    }
    
    // Outros feedbacks
    elseif ($status === 'logout') {
        $mensagem = "Sessão encerrada com sucesso.";
        $classe = 'alerta-aviso';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brick-Up - Login e Cadastro</title>
    <link rel="stylesheet" href="login.css"> 
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🧱 BRICK-UP</h1>
            </div>
            <div class="header-actions">
                <button class="btn-secondary" id="btnShowLogin">Entrar</button>
                <button class="btn-primary" id="btnShowCadastro">Cadastrar</button>
            </div>
        </div>
    </header>

    <?php if ($mensagem): ?>
    <div class="alerta-global <?php echo $classe; ?>">
        <div class="container">
            <?php echo $mensagem; ?>
        </div>
    </div>
    <?php endif; ?>

    <main class="auth-main">
        <div class="auth-container">
            <div class="auth-card">
                
                <form id="loginForm" class="auth-form <?php echo ($form_ativo == 'loginForm' ? 'active-form' : ''); ?>" action="processa_login.php" method="POST">
                    <h2>Entrar na Brick-Up</h2>
                    <p>Acesse o universo LEGO da sua conta.</p>

                    <div class="form-group">
                        <label for="login_usuario">E-mail ou Usuário</label>
                        <input type="text" id="login_usuario" name="usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="login_senha">Senha</label>
                        <input type="password" id="login_senha" name="senha" required>
                    </div>

                    <button type="submit" class="btn-primary btn-full-width">Acessar Conta</button>
                    <p class="switch-link">
                        Não tem conta? <a href="#" id="linkToCadastro">Cadastre-se aqui.</a>
                    </p>
                </form>

                <form id="cadastroForm" class="auth-form <?php echo ($form_ativo == 'cadastroForm' ? 'active-form' : ''); ?>" action="processa_cadastro.php" method="POST">
                    <h2>Crie sua Conta Grátis</h2>
                    <p>Preencha os dados abaixo para começar a construir!</p>
                    
                    <div class="form-group">
                        <label for="cadastro_nome">Nome Completo</label>
                        <input type="text" id="cadastro_nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="cadastro_email">E-mail (Será seu Usuário)</label>
                        <input type="email" id="cadastro_email" name="email" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cadastro_senha">Senha</label>
                            <input type="password" id="cadastro_senha" name="senha" required>
                            <small class="hint">Mín. 6 dígitos, com maiúscula, minúscula e especial.</small>
                        </div>
                        <div class="form-group">
                            <label for="cadastro_celular">Celular (DDD + Número)</label>
                            <input type="text" id="cadastro_celular" name="celular" required maxlength="15"> 
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cadastro_cpf">CPF</label>
                            <input type="text" id="cadastro_cpf" name="cpf" required maxlength="14">
                        </div>
                        <div class="form-group">
                            <label for="cadastro_cep">CEP</label>
                            <input type="text" id="cadastro_cep" name="cep" required maxlength="9">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary btn-full-width">Criar Conta</button>
                    <p class="switch-link">
                        Já tem conta? <a href="#" id="linkToLogin">Fazer Login.</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <script src="login.js"></script>
    <script src="../INICIO/script.js"></script> 
</body>
</html>