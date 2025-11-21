// Favoritos
document.querySelectorAll(".btn-favorito").forEach((btnFav) => {
  btnFav.addEventListener("click", (e) => {
    e.preventDefault();
    const animeId = btnFav.dataset.animeId;

    fetch("../shared/favoritar.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `anime_id=${encodeURIComponent(animeId)}`,
      credentials: "same-origin",
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.sucesso) {
          btnFav.textContent = data.favoritado ? "❤️" : "🤍";
          btnFav.classList.toggle("ativo", data.favoritado);
        } else {
          alert(data.erro || "Erro desconhecido.");
        }
      })
      .catch(() => alert("Erro ao enviar favorito."));
  });
});
