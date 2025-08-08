<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Consulta SQL para buscar os primeiros episódios da temporada atual
$sql = "SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        WHERE e.temporada = (
            SELECT MAX(temporada) FROM episodios WHERE anime_id = e.anime_id
        )
        AND e.numero = 1
        AND YEAR(e.data_lancamento) = YEAR(CURDATE()) -- Apenas estreias do ano atual
        ORDER BY e.data_lancamento DESC"; // Ordena pelas mais recentes

$stmt = $pdo->query($sql); // Executa a consulta
$estreias = $stmt->fetchAll(); // Armazena os resultados em um array
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Estreias da Temporada</title> 
  <link rel="stylesheet" href="../../CSS/style.css" /> 
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Estreias da Temporada</h1>
    <nav> <!-- Menu de navegação -->
      <a href="../../HTML/home.html">Home</a> |
      <a href="ultimo_episodios.php">Lançamentos</a> |
      <a href="estreias_temporada.php">Estreias da Temporada</a>
    </nav>
  </header>

  <section class="temporada">
    <?php if ($estreias): ?> <!-- Verifica se há estreias -->
      <ul class="episodios-lista">
        <?php foreach ($estreias as $ep): ?> <!-- Itera sobre cada estreia -->
          <li>
            <!-- Exibe a imagem da capa do anime -->
            <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
            <!-- Exibe o nome do anime, temporada, número do episódio e título -->
            <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong> — Temporada <?= $ep['temporada'] ?>, Episódio <?= $ep['numero'] ?>: 
            <?= htmlspecialchars($ep['titulo']) ?> 
            <!-- Exibe a data formatada de estreia -->
            (Estreia em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)
            <!-- Link para a lista de episódios do anime -->
            <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?> <!-- Caso não haja estreias -->
      <p>Nenhuma estreia para esta temporada.</p>
    <?php endif; ?>
  </section>
</body>
</html>
