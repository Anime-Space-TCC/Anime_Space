/**
 * Carrega a pergunta atual e renderiza as alternativas
 */
function carregarPergunta() {
    const p = perguntas[indice];
    
    // Atualiza barra de progresso
    progressFill.style.width = ((indice) / perguntas.length * 100) + "%";
    btnProximo.disabled = true;

    // Renderiza pergunta e alternativas
    box.innerHTML = `
        <div class="pergunta">
            <h2>${p.pergunta}</h2>
            <div class="alternativas">
                ${["a", "b", "c", "d"].map(letra => `
                    <button class="opcao" data-resp="${p["alternativa_" + letra]}">
                        ${p["alternativa_" + letra]}
                    </button>
                `).join("")}
            </div>
        </div>
    `;

    // Adiciona evento de clique para cada alternativa
    document.querySelectorAll(".opcao").forEach(btn => {
        btn.addEventListener("click", e => selecionarResposta(e, p.resposta_correta));
    });
}

/**
 * Trata a seleção de uma alternativa
 */
function selecionarResposta(e, correta) {
    const escolhido = e.target.dataset.resp;

    // Desabilita todas as opções
    document.querySelectorAll(".opcao").forEach(btn => btn.disabled = true);

    if (escolhido === correta) {
        e.target.classList.add("correto");
        pontuacao++;
    } else {
        e.target.classList.add("errado");
        // Marca a resposta correta
        document.querySelectorAll(".opcao").forEach(btn => {
            if (btn.dataset.resp === correta) btn.classList.add("correto");
        });
    }

    btnProximo.disabled = false;
}

/**
 * Passa para a próxima pergunta ou mostra o resultado final
 */
btnProximo.addEventListener("click", () => {
    indice++;
    if (indice < perguntas.length) {
        carregarPergunta();
    } else {
        mostrarResultado();
    }
});

/**
 * Mostra o resultado final e permite salvar progresso
 */
function mostrarResultado() {
    progressFill.style.width = "100%";

    const total = perguntas.length;
    const acertos = pontuacao;
    const xpGanho = Math.round((acertos / total) * 50);

    box.innerHTML = `
        <form action="../shared/quiz_resultado.php" method="post">
            <input type="hidden" name="quiz_id" value="${quizId}">
            <input type="hidden" name="xp" value="${xpGanho}">
            <div class="resultado">
                <h2>Resultado Final</h2>
                <p>Você acertou <strong>${acertos}</strong> de <strong>${total}</strong> perguntas!</p>
                <p>Ganhou <strong>${xpGanho} XP</strong> 🎖️</p>
                <button type="submit" class="btn-final">Salvar Progresso</button>
            </div>
        </form>
    `;

    btnProximo.style.display = "none";
}

// Inicializa o quiz
carregarPergunta();
