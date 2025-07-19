<?php
require __DIR__ . '/../shared/conexao.php';

$sql = "SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        ORDER BY e.data_lancamento DESC
        LIMIT 20";
$stmt = $pdo->query($sql);
$episodios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Últimos Episódios Atualizados</title>
  <link rel="stylesheet" href="../../CSS/style1.css" />
  <link rel="icon" href="../../img/slogan1.png" type="image/png">
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Últimos Episódios Atualizados</h1>
    <nav>
      <a href="../../HTML/home.html">Home</a> |
      <a href="ultimo_episodios.php">Últimos Episódios</a> |
      <a href="estreias_temporada.php">Estreias da Temporada</a>
    </nav>
  </header>

  <section class="ultimas">
    <?php if ($episodios): ?>
      <ul class="episodios-lista">
        <?php foreach ($episodios as $ep): ?>
          <li>
            <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
            <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong> Temporada <?= $ep['temporada'] ?>, Episódio <?= $ep['numero'] ?>: 
            <?= htmlspecialchars($ep['titulo']) ?> 
            (Lançado em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)
            <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Nenhum episódio encontrado.</p>
    <?php endif; ?>
  </section>
</body>
</html>
