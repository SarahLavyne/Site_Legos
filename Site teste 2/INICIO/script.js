// =================================================================
// LÓGICA DA PÁGINA INICIAL (index.php)
// =================================================================

// --- 1. Scroll suave ---
document.querySelectorAll('a[href^="#"]').forEach((ancora) => {
    ancora.addEventListener("click", function (e) {
        e.preventDefault()
        const alvo = document.querySelector(this.getAttribute("href"))
        if (alvo) {
            alvo.scrollIntoView({ behavior: "smooth", block: "start" })
        }
    })
})

// --- 2. Variáveis globais (Filtro e Ordenação) ---
const gridProdutos = document.getElementById("gridProdutos")
const botoesFiltro = document.querySelectorAll(".filtro-btn")
const btnOrdenarPreco = document.getElementById("btnOrdenarPreco")
let ordemPreco = "nenhuma" // 'crescente', 'decrescente', 'nenhuma'
let categoriaAtiva = "todos"

// --- 3. Filtrar por categoria ---
botoesFiltro.forEach((botao) => {
    botao.addEventListener("click", function () {
        botoesFiltro.forEach((b) => b.classList.remove("ativo"))
        this.classList.add("ativo")

        categoriaAtiva = this.dataset.categoria
        filtrarProdutos()
    })
})

// --- 4. Ordenar por preço ---
btnOrdenarPreco.addEventListener("click", function () {
    if (ordemPreco === "nenhuma" || ordemPreco === "decrescente") {
        ordemPreco = "crescente"
        this.classList.remove("decrescente")
        this.classList.add("crescente")
    } else {
        ordemPreco = "decrescente"
        this.classList.remove("crescente")
        this.classList.add("decrescente")
    }

    ordenarProdutos()
})

// --- 5. Função para filtrar produtos ---
function filtrarProdutos() {
    const cartoesProdutos = Array.from(document.querySelectorAll(".product-card"))

    cartoesProdutos.forEach((cartao) => {
        const categoria = cartao.dataset.categoria

        if (categoriaAtiva === "todos" || categoria === categoriaAtiva) {
            cartao.classList.remove("oculto")
        } else {
            cartao.classList.add("oculto")
        }
    })
}

// --- 6. Função para ordenar produtos ---
function ordenarProdutos() {
    const cartoesProdutos = Array.from(document.querySelectorAll(".product-card"))

    cartoesProdutos.sort((a, b) => {
        const precoA = Number.parseFloat(a.dataset.preco)
        const precoB = Number.parseFloat(b.dataset.preco)

        if (ordemPreco === "crescente") {
            return precoA - precoB
        } else {
            return precoB - precoA
        }
    })

    cartoesProdutos.forEach((cartao) => {
        gridProdutos.appendChild(cartao)
    })
}

// --- 7. Adicionar ao carrinho (AJAX) ---
document.querySelectorAll(".btn-add-cart, .btn-add-cart-sugestao").forEach(button => {
    button.addEventListener("click", function() {
        const produtoId = this.dataset.id;
        
        // Caminho CORRIGIDO: Sobe para SITE TESTE 2/ e desce para CARRINHO/
        fetch('../CARRINHO/adicionar_carrinho.php', { 
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'produto_id=' + produtoId
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); 
            
            // Feedback visual
            this.textContent = "✓ Adicionado!";
            this.style.background = "#2ed573";
            setTimeout(() => {
                this.textContent = "Adicionar ao Carrinho";
                this.style.background = "";
            }, 1500);
        })
        .catch(error => {
            console.error('Erro:', error);
            // Em caso de erro, pode ser útil avisar o usuário
            alert("Erro ao adicionar o produto. Verifique se está logado."); 
        });
    });
});

// --- 8. Animação nas categorias ---
const cartoesCategorias = document.querySelectorAll(".category-card")
cartoesCategorias.forEach((cartao) => {
    cartao.addEventListener("click", function () {
        const nomeCategoria = this.querySelector("h3").textContent
        console.log(`Categoria selecionada: ${nomeCategoria}`)
    })
})

// --- 9. Efeito parallax no hero ---
window.addEventListener("scroll", () => {
    const imagemHero = document.querySelector(".hero-image img")
    if (imagemHero) {
        const rolagem = window.pageYOffset
        imagemHero.style.transform = `translateY(${rolagem * 0.3}px)`
    }
})

// NOTA: A lógica de atualização de quantidade (funções formatCurrencyBRL e LÓGICA DE ATUALIZAÇÃO DE QUANTIDADE AJAX)
// deve ser movida para o CARRINHO/carrinho.js