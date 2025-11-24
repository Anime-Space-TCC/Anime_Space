// Manipulação de seleção de métodos de pagamento e validação
document.addEventListener("DOMContentLoaded", () => {
  const opcoes = document.querySelectorAll(".pagamento-opcao");
  const formPagamento = document.querySelector(".pagamento-container");
  const inputMetodo = formPagamento.querySelector(".input-metodo");

  // Seleciona método de pagamento
  opcoes.forEach(opcao => {
    opcao.addEventListener("click", () => {
      // Remove seleção de todas as opções
      opcoes.forEach(o => o.classList.remove("selecionado"));
      // Marca a opção clicada
      opcao.classList.add("selecionado");
      // Atualiza input hidden
      inputMetodo.value = opcao.dataset.metodo;
    });
  });

  // Valida envio do formulário
  formPagamento.addEventListener("submit", e => {
    if (!inputMetodo.value) {
      e.preventDefault();
      alert("Selecione um método de pagamento antes de finalizar.");
    }
  });
});
