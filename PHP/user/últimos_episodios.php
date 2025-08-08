<?php
require __DIR__ . '/../shared/conexao.php'; // Inclui a conexão com o banco de dados

// Consulta SQL para buscar os 20 episódios mais recentes com informações do anime
$sql = "SELECT e.*, a.nome AS anime_nome, a.capa
        FROM episodios e
        JOIN animes a ON e.anime_id = a.id
        ORDER BY e.data_lancamento DESC
        LIMIT 20";
$stmt = $pdo->query($sql); // Executa a consulta
$episodios = $stmt->fetchAll(); // Obtém todos os resultados
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" /> 
  <title>Últimos Episódios Atualizados</title> 
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png"> 
</head>
<body>
  <header>
    <h1 class="titulo-pagina">Últimos Episódios Atualizados</h1> <!-- Título principal da página -->
    <nav>
      <a href="../../HTML/home.html">Home</a> |
      <a href="ultimo_episodios.php">Lançamentos</a> | 
      <a href="estreias_temporada.php">Estreias da Temporada</a> 
    </nav>
  </header>

  <section class="ultimas">
    <?php if ($episodios): ?> <!-- Verifica se há episódios -->
      <ul class="episodios-lista">
        <?php foreach ($episodios as $ep): ?> <!-- Itera sobre os episódios -->
          <li>
            <!-- Imagem da capa do anime -->
            <img src="../../img/<?= htmlspecialchars($ep['capa']) ?>" alt="Capa <?= htmlspecialchars($ep['anime_nome']) ?>" width="100" />
            <!-- Nome do anime e informações do episódio -->
            <strong><?= htmlspecialchars($ep['anime_nome']) ?></strong> Temporada <?= $ep['temporada'] ?>, Episódio <?= $ep['numero'] ?>: 
            <?= htmlspecialchars($ep['titulo']) ?> 
            (Lançado em <?= date('d/m/Y', strtotime($ep['data_lancamento'])) ?>)
            <!-- Link para ver episódios do anime -->
            <a href="episodes.php?id=<?= $ep['anime_id'] ?>">Ver Episódios</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?> <!-- Caso não existam episódios -->
      <p>Nenhum episódio encontrado.</p>
    <?php endif; ?>
  </section>
</body>
</html>
