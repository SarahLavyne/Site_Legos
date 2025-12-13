// Variáveis para alternar formulários
const loginForm = document.getElementById('loginForm');
const cadastroForm = document.getElementById('cadastroForm');
const btnShowLogin = document.getElementById('btnShowLogin');
const btnShowCadastro = document.getElementById('btnShowCadastro');
const linkToLogin = document.getElementById('linkToLogin');
const linkToCadastro = document.getElementById('linkToCadastro');

// Função para alternar entre as abas
function switchForm(formToShow) {
    if (formToShow === 'login') {
        loginForm.classList.add('active-form');
        cadastroForm.classList.remove('active-form');
    } else {
        cadastroForm.classList.add('active-form');
        loginForm.classList.remove('active-form');
    }
}

// Event Listeners para alternância
btnShowLogin.addEventListener('click', () => switchForm('login'));
btnShowCadastro.addEventListener('click', () => switchForm('cadastro'));
linkToLogin.addEventListener('click', (e) => {
    e.preventDefault();
    switchForm('login');
});
linkToCadastro.addEventListener('click', (e) => {
    e.preventDefault();
    switchForm('cadastro');
});


// FUNÇÕES DE VALIDAÇÃO E MÁSCARA (Frontend)
// As máscaras ajudam a garantir que o formato de entrada está correto.

// 1. Máscara Genérica para campos
function mascara(o, f) {
    v_obj = o
    v_fun = f
    setTimeout("execmascara()", 1)
}
function execmascara() {
    v_obj.value = v_fun(v_obj.value)
}

// 2. Funções de Formatação (Máscaras)
function mCEP(v) {
    v = v.replace(/\D/g, "") // Remove tudo o que não é dígito
    v = v.replace(/^(\d{5})(\d)/, "$1-$2") // Coloca hífen entre o 5º e o 6º dígito
    return v
}

function mCPF(v) {
    v = v.replace(/\D/g, "") // Remove tudo o que não é dígito
    v = v.replace(/(\d{3})(\d)/, "$1.$2") // Coloca um ponto entre o 3º e o 4º dígitos
    v = v.replace(/(\d{3})(\d)/, "$1.$2") // Coloca um ponto entre o 6º e o 7º dígitos
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2") // Coloca um hífen entre o 9º e o 10º dígitos
    return v
}

function mCelular(v) {
    v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); // Coloca parênteses em volta dos dois primeiros dígitos
    v = v.replace(/(\d)(\d{4})$/, "$1-$2"); // Coloca hífen entre o 4º e o 5º dígitos
    return v;
}

// 3. Aplica as máscaras aos campos (Event Listeners)
document.getElementById('cadastro_cep').addEventListener('input', function() {
    mascara(this, mCEP);
});

document.getElementById('cadastro_cpf').addEventListener('input', function() {
    mascara(this, mCPF);
});

document.getElementById('cadastro_celular').addEventListener('input', function() {
    mascara(this, mCelular);
});

// Nota: A validação final se o CPF/CEP/Email é VÁLIDO (não apenas bem formatado) deve ser feita no PHP (Backend)