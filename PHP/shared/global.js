// Função global para mostrar notificações de XP, conquistas, etc
function showNotification(message, type = "xp") {
    const notification = document.createElement("div");
    notification.classList.add("xp-notification");
    notification.textContent = message;

    if (type === "error") notification.style.background = "#e74c3c";
    else if (type === "success") notification.style.background = "#27ae60";
    else notification.style.background = "#2980b9";

    document.body.appendChild(notification);

    // Animação de entrada
    setTimeout(() => {
        notification.classList.add("show");
    }, 10);

    // Remove após 3 segundos
    setTimeout(() => {
        notification.classList.remove("show");
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Função auxiliar global para adicionar XP via AJAX
function adicionarXP(quantidade, descricao) {
    fetch("/../PHP/shared/gamificacao.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `acao=ganharXP&quantidade=${quantidade}&descricao=${encodeURIComponent(descricao)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) {
            showNotification(`🏆 ${descricao} +${quantidade} XP!`);
        } else {
            console.error("Erro ao adicionar XP:", data.mensagem);
        }
    })
    .catch(err => console.error("Erro na requisição:", err));
}
