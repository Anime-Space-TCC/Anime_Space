document.addEventListener("DOMContentLoaded", () => {
  const btnImprimir = document.getElementById("btnImprimir");
  if (btnImprimir) {
    btnImprimir.addEventListener("click", imprimirComprovantePersonalizado);
  }
});

function imprimirComprovantePersonalizado() {
  const comprovante = document.querySelector(".comprovante").cloneNode(true);

  const botoes = comprovante.querySelector(".botoes-comprovante");
  if (botoes) botoes.remove();

  const conteudo = comprovante.innerHTML;

  const data = new Date().toLocaleDateString("pt-BR", {
    timeZone: "America/Sao_Paulo",
  });

  const nome = typeof usuarioNome !== "undefined" ? usuarioNome : "Cliente";

  const janela = window.open("", "_blank");

  if (!janela) {
    alert("O bloqueador de pop-ups impediu abrir o comprovante!");
    return;
  }

  const html = `
  <!DOCTYPE html>
  <html>
  <head>
      <title>Comprovante de Pagamento</title>
      <link rel="icon" href="../../img/slogan3.png" type="image/png">
      <style>
          body {
              background:#000;
              color:#ff9f00;
              font-family: Arial, sans-serif;
              margin:40px;
          }
          .comprovante-print {
              max-width:800px;
              margin:auto;
              padding:30px;
              border:2px solid #ff9f00;
              border-radius:15px;
              box-shadow:0 0 15px #ff9f00;
          }
          h1, p { text-align:center; }
          img {
              display:block;
              margin:0 auto 10px auto;
              width:120px;
          }
          table {
              width:100%;
              border-collapse:collapse;
              margin-top:20px;
          }
          th, td {
              border:1px solid #ff9f00;
              padding:10px;
              text-align:center;
          }
          th { background:#111; }
          .rodape-print {
              text-align:center;
              margin-top:30px;
              font-size:14px;
              color:#ccc;
          }
      </style>
  </head>
  <body>
      <div class="comprovante-print">
          <img src="../../img/slogan3.png" alt="Logo">
          <h1>Comprovante de Pagamento</h1>
          <p><strong>Cliente:</strong> ${nome}</p>
          <p><strong>Data:</strong> ${data}</p>
          ${conteudo}
          <div class="rodape-print">
              <p>Obrigado por comprar conosco!</p>
              <p>Animes Space © ${new Date().getFullYear()}</p>
          </div>
      </div>
  </body>
  </html>
  `;

  // 👉 O método mais compatível:
  janela.document.open();
  janela.document.write(html);
  janela.document.close();

  // Impressão
  janela.print();
}
