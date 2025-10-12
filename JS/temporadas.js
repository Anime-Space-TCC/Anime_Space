    // Dropdown de temporadas
    const btnDropdown = document.getElementById('btnDropdown');
    const dropdownList = document.getElementById('dropdownList');

    if (btnDropdown && dropdownList) {
      const dropdownItems = dropdownList.querySelectorAll('li');

      btnDropdown.addEventListener('click', () => {
        dropdownList.classList.toggle('show');
      });

      dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
          const temporada = item.dataset.temporada;
          btnDropdown.textContent = `Temporada ${temporada}`;

          document.querySelectorAll('.temporada-bloco').forEach(bloco => {
            bloco.style.display = (bloco.dataset.temporada === temporada) ? "" : "none";
          });

          dropdownList.classList.remove('show');
        });
      });

      document.addEventListener('click', e => {
        if (!btnDropdown.contains(e.target) && !dropdownList.contains(e.target)) {
          dropdownList.classList.remove('show');
        }
      });
    }