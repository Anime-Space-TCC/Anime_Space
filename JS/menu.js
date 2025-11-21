document.addEventListener("DOMContentLoaded", () => {
  /* ===========================
     1. MENU LATERAL
  ============================ */
  function inicializarMenuLateral() {
    const menuBtn = document.querySelector(".menu-toggle");
    const menu = document.getElementById("menuLateral");

    if (menuBtn && menu) {
      menuBtn.addEventListener("click", () => {
        menu.classList.toggle("open");
      });
    }
  }

  /* ===========================
     2. BUSCA EXPANDIDA
  ============================ */
  function inicializarBuscaExpandida() {
    const buscaBtn = document.getElementById("buscaBtn");
    const buscaContainer = document.querySelector(".busca-container");
    const inputBusca = buscaContainer?.querySelector('input[name="busca"]');

    if (buscaBtn && buscaContainer && inputBusca) {
      buscaBtn.addEventListener("click", () => {
        buscaContainer.classList.toggle("activo");

        if (buscaContainer.classList.contains("active")) {
          inputBusca.focus();
        } else {
          inputBusca.value = "";
        }
      });
    }
  }

  /* ===========================
     3. CAIXA DE NOTIFICAÇÕES
  ============================ */
  function inicializarNotificacoes() {
    const btnToggle = document.getElementById("btnToggle");
    const caixa = document.getElementById("caixaNotificacoes");
    const badge = document.getElementById("notifBadge");

    if (!btnToggle || !caixa) return;

    btnToggle.addEventListener("click", () => {
      caixa.classList.toggle("ativo"); // <- aqui
      btnToggle.classList.toggle("ativo");

      // Se abriu, marca como lidas
      if (caixa.classList.contains("ativo")) {
        // <- aqui
        fetch("../../PHP/shared/marcar_notificacoes_lidas.php", {
          method: "POST",
        }).then(() => {
          if (badge) badge.style.display = "none";
        });
      }
    });

    // Fechar ao clicar fora
    document.addEventListener("click", (e) => {
      if (!btnToggle.contains(e.target) && !caixa.contains(e.target)) {
        btnToggle.classList.remove("ativo");
        caixa.classList.remove("ativo");
      }
    });
  }

  /* ===========================
     4. TABS NA CAIXA DE NOTIFICAÇÕES
  ============================ */
  function inicializarTabsCaixa() {
    const tabs = document.querySelectorAll(".tab-btn");
    const conteudos = document.querySelectorAll(".tab-conteudo");

    if (tabs.length === 0 || conteudos.length === 0) return;

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        conteudos.forEach((c) => c.classList.remove("active"));

        tab.classList.add("active");
        document.getElementById(tab.dataset.tab).classList.add("active");
      });
    });
  }

  /* ===========================
     INICIALIZA TODAS AS FUNÇÕES
  ============================ */
  inicializarMenuLateral();
  inicializarBuscaExpandida();
  inicializarNotificacoes();
  inicializarTabsCaixa();
});
