<section class="admin-acessos">
  <h3>Últimos Acessos</h3>

  <table class="admin-table-acesso" id="tabela-acessos">
    <thead>
      <tr>
        <th>ID</th>
        <th>Usuário</th>
        <th>Página</th>
        <th>Origem</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="5">Carregando...</td>
      </tr>
    </tbody>
  </table>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof inicializarDashboard === "function") inicializarDashboard();
  });
</script>