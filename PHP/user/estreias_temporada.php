<?php
require __DIR__ . '/../shared/conexao.php';

$sql = "SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        WHERE e.temporada = (
            SELECT MAX(temporada) FROM episodios WHERE anime_id = e.anime_id
        )
        AND e.numero = 1
        AND YEAR(e.data_lancamento) = YEAR(CURDATE())
        ORDER BY e.data_lancamento DESC";
$stmt = $pdo->query($sql);
$estreias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Estreias da Temporada</title>
  <link rel="stylesheet" href="../../CSS/style1.css" />
  <link rel="icon" href="../../img/slogan1.png" type="image/png">
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Estreias da Temporada</h1>
    <nav>
      <a href="../../HTML/home.html">Home</a> |
      <a href="ultimo_episodios.php">Últimos Episódios</a> |
      <a href="estreias_temporada.php">Estreias da Temporada</a>
    </nav>
  </header>

  <section class="temporada">
    <?php if ($estreias): ?>
      <ul class="episodios-lista">
        <?php foreach ($estreias as $ep): ?>
          <li>
            <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
            <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong> — Temporada <?= $ep['temporada'] ?>, Episódio <?= $ep['numero'] ?>: 
            <?= htmlspecialchars($ep['titulo']) ?> 
            (Estreia em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)
            <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Nenhuma estreia para esta temporada.</p>
    <?php endif; ?>
  </section>
</body>
</html>
