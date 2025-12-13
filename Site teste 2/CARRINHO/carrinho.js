// ==========================================================
// LÓGICA ESPECÍFICA DA PÁGINA DO CARRINHO (carrinho.php)
// ==========================================================

// --- FUNÇÃO DE UTILIDADE ---
function formatCurrencyBRL(value) {
    return 'R$ ' + parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// --- LÓGICA DE ATUALIZAÇÃO DE QUANTIDADE AJAX (+ / -) ---
document.querySelectorAll(".btn-qty-plus, .btn-qty-minus").forEach(button => {
    button.addEventListener("click", function() {
        
        const td = this.closest('td'); 
        
        // --- CORREÇÃO AQUI: Use carrinhoId (I maiúsculo) ---
        const carrinhoId = td.dataset.carrinhoId; 
        
        const precoUnitario = parseFloat(td.dataset.preco);
        const qtyInput = td.querySelector('.qty-input'); 
        const subtotalTd = td.nextElementSibling; 
        const resumoTotal = document.querySelector('.carrinho-resumo h3'); 
        
        let currentQty = parseInt(qtyInput.value); 
        let newQty = currentQty;

        if (this.classList.contains('btn-qty-plus')) {
            newQty += 1;
        } else if (this.classList.contains('btn-qty-minus') && currentQty > 1) {
            newQty -= 1;
        } else {
            return; 
        }
        
        td.querySelectorAll('button, .qty-input').forEach(el => el.disabled = true);

        qtyInput.value = newQty;

        // AJAX
        fetch('processa_carrinho.php', { 
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `carrinho_id=${carrinhoId}&quantidade=${newQty}&acao=atualizar` 
        })
        .then(response => response.text())
        .then(novoTotalGeral => {
            const totalFloat = parseFloat(novoTotalGeral);

            if (isNaN(totalFloat)) {
                console.error("Resposta inválida do PHP:", novoTotalGeral);
                throw new Error("O servidor não retornou um preço válido.");
            }

            const newSubtotal = newQty * precoUnitario;
            subtotalTd.textContent = formatCurrencyBRL(newSubtotal);

            // Atualiza Total Geral
            if (resumoTotal) {
                 resumoTotal.innerHTML = `Total da Compra: <strong>${formatCurrencyBRL(totalFloat)}</strong>`;
            }

            td.querySelectorAll('button, .qty-input').forEach(el => el.disabled = false);
        })
        .catch(error => {
            console.error('Erro na atualização:', error);
            qtyInput.value = currentQty;
            td.querySelectorAll('button, .qty-input').forEach(el => el.disabled = false);
            alert("Não foi possível atualizar o carrinho.");
        });
    });
});

// --- LÓGICA PARA DIGITAÇÃO DIRETA NO INPUT (Evento Change) ---
document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("change", function() {
        const td = this.closest('td');
        
        // --- CORREÇÃO AQUI TAMBÉM: Use carrinhoId (I maiúsculo) ---
        const carrinhoId = td.dataset.carrinhoId;
        
        const precoUnitario = parseFloat(td.dataset.preco);
        const subtotalTd = td.nextElementSibling;
        const resumoTotal = document.querySelector('.carrinho-resumo h3');
        
        let newQty = parseInt(this.value);

        if (newQty < 1 || isNaN(newQty)) {
            newQty = 1;
            this.value = 1;
        }

        td.querySelectorAll('button, .qty-input').forEach(el => el.disabled = true);

        fetch('processa_carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `carrinho_id=${carrinhoId}&quantidade=${newQty}&acao=atualizar` 
        })
        .then(response => response.text())
        .then(novoTotalGeral => {
            const totalFloat = parseFloat(novoTotalGeral);

            if (isNaN(totalFloat)) {
                throw new Error("Resposta inválida do PHP");
            }

            const newSubtotal = newQty * precoUnitario;
            subtotalTd.textContent = formatCurrencyBRL(newSubtotal);

            if (resumoTotal) {
                 resumoTotal.innerHTML = `Total da Compra: <strong>${formatCurrencyBRL(totalFloat)}</strong>`;
            }
            td.querySelectorAll('button, .qty-input').forEach(el => el.disabled = false);
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro ao atualizar.");
            window.location.reload(); 
        });
    });
});
// ... (Mantenha o código anterior de atualização de quantidade aqui) ...

// ==========================================================
// LÓGICA PARA ADICIONAR ITEM DAS SUGESTÕES (Upsell)
// ==========================================================

document.querySelectorAll(".btn-add-cart-sugestao").forEach(button => {
    button.addEventListener("click", function() {
        const produtoId = this.dataset.id;
        const originalText = this.textContent;
        
        // Feedback visual imediato
        this.disabled = true;
        this.textContent = "Adicionando...";

        // AJAX para adicionar ao carrinho
        // O arquivo adicionar_carrinho.php está na mesma pasta (CARRINHO/), então o caminho é direto
        fetch('adicionar_carrinho.php', { 
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'produto_id=' + produtoId
        })
        .then(response => response.text())
        .then(data => {
            console.log("Resposta do servidor:", data);
            
            // Feedback de Sucesso
            this.textContent = "✓ Adicionado!";
            this.style.backgroundColor = "#2ed573"; // Verde
            this.style.color = "white";
            
            // RECARREGA A PÁGINA para o item aparecer na tabela e atualizar o total
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Erro ao adicionar sugestão:', error);
            this.textContent = "Erro!";
            this.style.backgroundColor = "#ff4d4d"; // Vermelho
            
            setTimeout(() => {
                this.textContent = originalText;
                this.style.backgroundColor = "";
                this.disabled = false;
            }, 2000);
        });
    });
});