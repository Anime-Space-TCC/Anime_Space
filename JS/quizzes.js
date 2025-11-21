/******************************
 *         QUIZ LOGIC
 ******************************/

/** Carrega a pergunta atual */
function carregarPergunta() {
  const p = perguntas[indice];

  progressFill.style.width = (indice / perguntas.length) * 100 + "%";
  btnProximo.disabled = true;

  box.innerHTML = `
        <div class="pergunta">
            <h2>${p.pergunta}</h2>
            <br>
            <div class="alternativas">
                ${["a", "b", "c", "d"]
                  .map(
                    (letra) => `
                    <button type="button" class="opcao" data-resp="${letra}">
                        ${p["alternativa_" + letra]}
                    </button>
                `
                  )
                  .join("")}
            </div>
        </div>
    `;

  document.querySelectorAll(".opcao").forEach((btn) => {
    btn.addEventListener("click", (e) =>
      selecionarResposta(e, p.resposta_correta)
    );
  });
}

function selecionarResposta(e, correta) {
  const escolhido = e.target.dataset.resp;

  document.querySelectorAll(".opcao").forEach((btn) => (btn.disabled = true));

  if (escolhido === correta) {
    e.target.classList.add("correto");
    pontuacao++;
  } else {
    e.target.classList.add("errado");
    document.querySelectorAll(".opcao").forEach((btn) => {
      if (btn.dataset.resp === correta) btn.classList.add("correto");
    });
  }

  btnProximo.disabled = false;
}

/** Avança ou finaliza */
btnProximo.addEventListener("click", () => {
  indice++;
  indice < perguntas.length ? carregarPergunta() : mostrarResultado();
});

/** Exibe o resultado */
function mostrarResultado() {
  progressFill.style.width = "100%";

  const total = perguntas.length;
  const acertos = pontuacao;

  box.innerHTML = `
        <div class="resultado">
            <h2>Resultado Final</h2>
            <br>
            <p>Você acertou <strong>${acertos}</strong> de <strong>${total}</strong> perguntas!</p>
            <br>
            <button id="btn-finalizar" class="btn-final">Finalizar</button>
        </div>
    `;

  btnProximo.style.display = "none";

  document.getElementById("btn-finalizar").addEventListener("click", () => {
    const total = perguntas.length;
    const acertos = pontuacao;

    let xp = 50; // primeira conclusão
    if (acertos === total) xp += 50; // bônus perfeição

    fetch("../shared/quiz_resultado.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `quiz_id=${quizId}&xp=${xp}&pontuacao=${acertos}`,
    }).finally(() => {
      window.location.href = "quizzes.php";
    });
  });
}

// Inicializa o quiz
carregarPergunta();
