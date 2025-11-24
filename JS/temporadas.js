const btnTemp = document.getElementById("btnTemp");
const dropdownList = document.getElementById("dropdownList");

// Só ativa se existir dropdown (ou seja, +1 temporada)
if (btnTemp && dropdownList) {

    const dropdownItems = dropdownList.querySelectorAll("li");

    // Abrir/fechar dropdown
    btnTemp.addEventListener("click", (event) => {
        event.stopPropagation();
        dropdownList.classList.toggle("show");
    });

    // Selecionar temporada
    dropdownItems.forEach((item) => {
        item.addEventListener("click", (event) => {
            event.stopPropagation();

            const temporada = item.dataset.temporada;

            btnTemp.textContent = `Temporada ${temporada}`;

            // Mostrar apenas essa temporada
            document.querySelectorAll(".temporada-bloco").forEach((bloco) => {
                bloco.style.display =
                    bloco.dataset.temporada === temporada ? "" : "none";
            });

            dropdownList.classList.remove("show");
        });
    });

    // Fechar ao clicar fora
    document.addEventListener("click", () => {
        dropdownList.classList.remove("show");
    });
}
