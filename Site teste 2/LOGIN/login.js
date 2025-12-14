document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos do formulário
    const loginForm = document.getElementById('loginForm');
    const cadastroForm = document.getElementById('cadastroForm');
    
    // Links internos para alternar
    const linkToCadastro = document.getElementById('linkToCadastro');
    const linkToLogin = document.getElementById('linkToLogin');
    
    // Botões do cabeçalho
    const btnShowLogin = document.getElementById('btnShowLogin');
    const btnShowCadastro = document.getElementById('btnShowCadastro');

    // Funções de alternância
    function showLoginForm() {
        loginForm.classList.add('active-form');
        cadastroForm.classList.remove('active-form');
    }

    function showCadastroForm() {
        cadastroForm.classList.add('active-form');
        loginForm.classList.remove('active-form');
    }

    // =========================================================
    // 1. LÓGICA DE INICIALIZAÇÃO (Leitura do Status do PHP)
    // =========================================================
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Lista de status que devem manter o formulário de Cadastro ativo
    if (status && (status.startsWith('cadastro_erro') && status !== 'cadastro_sucesso')) {
        // Se houve erro (CPF, Senha, E-mail, Genérico), mostra o Cadastro
        showCadastroForm();
        
    } else {
        // Padrão (Sucesso no cadastro, Erro no login, Logout ou Sem status): mostra o Login
        showLoginForm();
    }
    
    // =========================================================
    // 2. EVENT LISTENERS para alternância manual (clique)
    // =========================================================
    
    // Links no cartão (Login <-> Cadastro)
    if (linkToCadastro) {
        linkToCadastro.addEventListener('click', function(e) {
            e.preventDefault();
            showCadastroForm();
        });
    }

    if (linkToLogin) {
        linkToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
    }
    
    // Botões no header
    if (btnShowLogin) {
        btnShowLogin.addEventListener('click', showLoginForm);
    }
    
    if (btnShowCadastro) {
        btnShowCadastro.addEventListener('click', showCadastroForm);
    }
});

// PRECISA ARRUMAR A VALIDAÇÃO DE CPF QUE ESTÁ DANDO ERRADO
