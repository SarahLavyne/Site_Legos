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

    <main class="auth-main">
        <div class="auth-container">
            <div class="auth-card">
                
                <form id="loginForm" class="auth-form active-form" action="processa_login.php" method="POST">
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

                <form id="cadastroForm" class="auth-form" action="processa_cadastro.php" method="POST">
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
    <script src="script.js"></script>
</body>
</html>