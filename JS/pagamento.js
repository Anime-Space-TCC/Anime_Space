// Manipulação de seleção de métodos de pagamento e finalizar compras
document.addEventListener("DOMContentLoaded", () => {
  const opcoes = document.querySelectorAll(".pagamento-opcao");
  const formsPagamento = document.querySelectorAll(".form-pagamento");

  let metodoSelecionado = null;

  // Seleciona método de pagamento
  opcoes.forEach((opcao) => {
    opcao.addEventListener("click", () => {
      // Remove selecionado de todas as opções
      opcoes.forEach((o) => o.classList.remove("selecionado"));
      // Marca a opção clicada
      opcao.classList.add("selecionado");
      metodoSelecionado = opcao.dataset.metodo;

      // Atualiza todos os inputs hidden dos formulários
      formsPagamento.forEach((form) => {
        const inputMetodo = form.querySelector(".input-metodo");
        if (inputMetodo) inputMetodo.value = metodoSelecionado;
      });
    });
  });

  // Valida formulário ao enviar
  formsPagamento.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const metodo = form.querySelector(".input-metodo").value;
      if (!metodo) {
        e.preventDefault();
        alert("Selecione um método de pagamento antes de finalizar.");
      }
    });
  });
});
