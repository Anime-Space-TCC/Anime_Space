// Menu Lateral
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-toggle');
    const menu = document.getElementById('menuLateral');

    if (menuBtn && menu) {
        menuBtn.addEventListener('click', () => menu.classList.toggle('open'));
    }
});