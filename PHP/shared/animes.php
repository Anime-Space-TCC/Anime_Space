<?php
require_once __DIR__ . '/auth.php';

// ==============================
// Inicialização de sessão
// ==============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================
// Funções relacionadas a Animes
// ==============================

// Busca todos os generos disponiveis
function buscarAnimePorId(PDO $pdo, int $id): ?array {
    // Busca os dados básicos do anime
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nome,
            capa,
            sinopse,
            ano,
            nota
        FROM animes
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $anime = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anime) return null;

    // Busca os gêneros como array
    $stmt2 = $pdo->prepare("
        SELECT g.id, g.nome 
        FROM generos g
        JOIN anime_generos ag ON g.id = ag.genero_id
        WHERE ag.anime_id = ?
    ");
    $stmt2->execute([$id]);
    $anime['generos'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    return $anime;
}

// Buscar a grade semanal de animes
function buscarGradeSemanal($pdo) {
  $sql = "SELECT * FROM animes WHERE dia_exibicao IS NOT NULL ORDER BY FIELD(dia_exibicao, 'segunda','terça','quarta','quinta','sexta','sábado','domingo'), hora_exibicao";
  $stmt = $pdo->query($sql);
  $animes = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Agrupar por dia
  $grade = [];
  foreach ($animes as $anime) {
    $dia = ucfirst($anime['dia_exibicao']);
    $grade[$dia][] = $anime;
  }
  return $grade;
}

// Busca os animes com maior nota
function buscarTopAnimes(PDO $pdo, int $limite = 5): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa, nota, sinopse FROM animes ORDER BY nota DESC LIMIT :limite");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarLancamentos(PDO $pdo, int $limite = 9): array {
    $stmt = $pdo->prepare("
        SELECT id, nome, capa, nota 
        FROM animes 
        ORDER BY id DESC 
        LIMIT :limite
    ");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Busca animes pelo nome (termo de pesquisa)
function buscarAnimePorNome(PDO $pdo, string $nome): array {
    $stmt = $pdo->prepare("SELECT id, nome, capa, sinopse FROM animes WHERE nome LIKE :nome");
    $stmt->execute(['nome' => "%$nome%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

