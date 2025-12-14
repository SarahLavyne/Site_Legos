// =================================================================
// LÓGICA DA ÁREA PÚBLICA (index.php e detalhes.php)
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
// Verificação de segurança: só roda filtros se a grade existir (evita erro na página detalhes.php)
if (gridProdutos) {
    const botoesFiltro = document.querySelectorAll(".filtro-btn")
    const btnOrdenarPreco = document.getElementById("btnOrdenarPreco")
    let ordemPreco = "nenhuma" 
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
    if(btnOrdenarPreco) {
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
    }
}

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

// =================================================================
// LÓGICA DE CARRINHO (ATUALIZADA PARA INDEX E DETALHES)
// =================================================================

// --- 7. Botão "Adicionar ao Carrinho" (Genérico + Estilo Amazon) ---
document.querySelectorAll(".btn-add-cart").forEach(button => {
    button.addEventListener("click", function() {
        const produtoId = this.dataset.id;
        // Salva o texto original para restaurar depois (ex: "Adicionar" ou "Adicionar ao carrinho")
        const originalText = this.textContent;
        
        // Feedback Visual
        this.disabled = true;
        this.textContent = "Adicionando...";
        
        // Se for o botão amarelo da Amazon, mantemos a cor amarela durante o carregamento
        if(this.classList.contains('btn-amazon-add')) {
            this.style.backgroundColor = "#f0c14b"; 
        }

        // Caminho AJAX
        fetch('../CARRINHO/adicionar_carrinho.php', { 
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'produto_id=' + produtoId
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); 
            
            // Sucesso
            this.textContent = "✓ Adicionado!";
            this.style.backgroundColor = "#2ed573"; // Verde
            this.style.color = "#fff"; // Texto branco para garantir leitura no verde

            setTimeout(() => {
                this.textContent = originalText;
                this.style.backgroundColor = ""; // Remove estilo inline (volta ao CSS original)
                this.style.color = "";
                this.disabled = false;
            }, 1500);
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro ao adicionar. Verifique se está logado."); 
            this.disabled = false;
            this.textContent = originalText;
        });
    });
});

// --- 8. Botão "Comprar Agora" (Laranja - Redireciona) ---
document.querySelectorAll(".btn-buy-now").forEach(button => {
    button.addEventListener("click", function() {
        const produtoId = this.dataset.id;
        
        this.textContent = "Processando...";
        this.disabled = true;

        fetch('../CARRINHO/adicionar_carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'produto_id=' + produtoId
        })
        .then(response => response.text())
        .then(data => {
            // Redireciona imediatamente para o carrinho
            window.location.href = "../CARRINHO/carrinho.php";
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro ao processar compra.");
            this.disabled = false;
            this.textContent = "Comprar agora";
        });
    });
});

// --- 9. Animação nas categorias ---
const cartoesCategorias = document.querySelectorAll(".category-card")
cartoesCategorias.forEach((cartao) => {
    cartao.addEventListener("click", function () {
        const nomeCategoria = this.querySelector("h3").textContent
        console.log(`Categoria selecionada: ${nomeCategoria}`)
    })
})

// --- 10. Efeito parallax no hero ---
window.addEventListener("scroll", () => {
    const imagemHero = document.querySelector(".hero-image img")
    if (imagemHero) {
        const rolagem = window.pageYOffset
        imagemHero.style.transform = `translateY(${rolagem * 0.3}px)`
    }
})

