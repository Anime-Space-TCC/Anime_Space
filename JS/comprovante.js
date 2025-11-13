document.addEventListener("DOMContentLoaded", () => {
    const btnImprimir = document.getElementById("btnImprimir");
    if (btnImprimir) {
        btnImprimir.addEventListener("click", imprimirComprovantePersonalizado);
    }
});

function imprimirComprovantePersonalizado() {
    // Clona a área do comprovante para poder manipular sem alterar o original
    const comprovante = document.querySelector(".comprovante").cloneNode(true);

    // Remove o bloco dos botões (para não aparecer os links no comprovante)
    const botoes = comprovante.querySelector(".botoes-comprovante");
    if (botoes) botoes.remove();

    // Pega apenas o conteúdo limpo 
    const conteudo = comprovante.innerHTML;

    const data = new Date().toLocaleDateString("pt-BR", { timeZone: "America/Sao_Paulo" });
    const nome = typeof usuarioNome !== "undefined" ? usuarioNome : "Cliente";

    const janela = window.open("", "_blank");
    janela.document.write(`
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
                h1, h2, p { text-align:center; }
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
                img {
                    display:block;
                    margin:0 auto 10px auto;
                    width:120px;
                }
                @media print {
                    body { box-shadow:none; margin:0; }
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
                    <p>Vai Pela Sombra © ${new Date().getFullYear()}</p>
                </div>
            </div>
        </body>
        </html>
    `);
    janela.document.close();
    janela.print();
}
