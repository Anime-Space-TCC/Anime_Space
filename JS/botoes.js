// Descricao sinopse
function toggleSinopse() {
  const sinopseContainer = document.getElementById("sinopse-container");
  const btn = document.querySelector("header .btn-info");
  if (sinopseContainer && btn) {
    sinopseContainer.classList.toggle("active");
    btn.textContent = sinopseContainer.classList.contains("active") ? "▲" : "▼";
  }
}

// Descricao descrição
function toggleDescricao(btn) {
  const card = btn.closest(".card");
  if (!card) return;

  const descricao = card.querySelector(".episodio-descricao");
  if (!descricao) return;

  descricao.classList.toggle("hidden");
  btn.textContent = descricao.classList.contains("hidden") ? "►" : "◄";

  const esconder = !descricao.classList.contains("hidden"); // true se a descrição estiver visível

  // 1️⃣ Esconder/Reaparecer os botões de reação
  const reacaoBtns = card.querySelectorAll(".reacao-btn");
  reacaoBtns.forEach((rb) => {
    rb.style.display = esconder ? "none" : "inline-flex";
  });

  // 2️⃣ Esconder/Reaparecer contadores de likes/dislikes
  const contadores = card.querySelectorAll(".contador-like, .contador-dislike");
  contadores.forEach((c) => {
    c.style.display = esconder ? "none" : "inline";
  });

  // 3️⃣ Esconder/Reaparecer botão "Assistir"
  const btnAssistir = card.querySelector(".btn-assistir");
  if (btnAssistir) {
    btnAssistir.style.display = esconder ? "none" : "inline-block";
  }

  // 4️⃣ Esconder/Reaparecer duração e lançamento
  const infoAdicional = card.querySelectorAll(".info-adicional span");
  infoAdicional.forEach((span) => {
    span.style.display = esconder ? "none" : "inline";
  });
}
