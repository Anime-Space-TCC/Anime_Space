<?php
require __DIR__ . '/../shared/conexao.php';
require __DIR__ . '/../shared/animes.php';
require_once __DIR__ . '/../shared/auth.php';

// ====================
// Verificação de login
// ====================
verificarLogin();

$grade = buscarGradeSemanal($pdo);

//Função para limitar o número de animes exibidos por dia
function limitarAnimesPorDia(array $lista, int $limite = 3): array
{
  return array_slice($lista, 0, $limite);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Grade Semanal de Lançamentos</title>
  <link rel="stylesheet" href="../../CSS/style.css" />
  <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>
<body>
  <?php
  $current_page = 'semanal';
  include __DIR__ . '/navbar.php';
  ?>
  <main class="page-content">
    <h1 class="titulo-pagina">Grade Semanal de Lançamentos</h1>

    <div class="grade-semanal">
      <?php
      $diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
      foreach ($diasSemana as $dia):
        ?>
          <div class="dia">
            <h2><?= $dia ?></h2>
            <?php if (!empty($grade[$dia])): ?>
                <?php foreach (limitarAnimesPorDia($grade[$dia]) as $anime): ?>
                    <div class="anime-item">
                      <img src="../../img/<?= htmlspecialchars($anime['capa']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>">
                      <div class="anime-info">
                        <strong><?= htmlspecialchars($anime['nome']) ?></strong>
                        <span>🕒 <?= date('H:i', strtotime($anime['hora_exibicao'])) ?></span>
                        <a href="../../PHP/user/episodes.php?id=<?= $anime['id'] ?>">Ver episódios</a>
                      </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum anime neste dia.</p>
            <?php endif; ?>
          </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php include __DIR__ . '/rodape.php'; ?>
</body>
</html>
