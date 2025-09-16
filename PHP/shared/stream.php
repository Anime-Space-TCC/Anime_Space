<?php
// shared/stream.php
// Entrega segura de vídeos por id com token assinado e validação.
// Requisitos: sessão ativa, SECRET_KEY definido (em shared/config.php por exemplo).

declare(strict_types=1);

session_start();

require __DIR__ . '/conexao.php';
require __DIR__ . '/auth.php';       // verificarLogin() etc
// (opcional) require __DIR__ . '/utils.php';

// CONFIG: definir SECRET_KEY em arquivo seguro (ex: shared/config.php)
// Se não existir, forçar erro para evitar uso inseguro.
if (!defined('SECRET_KEY') || empty(SECRET_KEY)) {
    http_response_code(500);
    echo "SECRET_KEY não definido. Configure shared/config.php com SECRET_KEY forte.";
    exit;
}

// Parâmetros esperados: id (int), expires (timestamp), token (HMAC)
$episodeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$expires   = filter_input(INPUT_GET, 'expires', FILTER_VALIDATE_INT);
$token     = $_GET['token'] ?? '';

if (!$episodeId || !$expires || !$token) {
    http_response_code(400);
    echo "Parâmetros inválidos.";
    exit;
}

// Verifica expiry
if (time() > $expires) {
    http_response_code(403);
    echo "Link expirado.";
    exit;
}

// Usuário deve estar logado (opcionalmente permitir vídeos públicos)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Login requerido.";
    exit;
}
$userId = (int) $_SESSION['user_id'];

// Recria o HMAC esperado: usar id|userId|expires (mesma lógica que a geração)
$expected = hash_hmac('sha256', $episodeId . '|' . $userId . '|' . $expires, SECRET_KEY);

// Timing-safe comparison
if (!hash_equals($expected, $token)) {
    http_response_code(403);
    echo "Token inválido.";
    exit;
}

// --- Buscar filename no DB de forma segura ---
$stmt = $pdo->prepare("SELECT video_url, video_filename FROM episodios WHERE id = ?");
$stmt->execute([$episodeId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    http_response_code(404);
    echo "Episódio não encontrado.";
    exit;
}

// Preferir um campo que contenha apenas o nome do arquivo (video_filename).
// Se você só tem video_url no DB, faça o mapping para filename controlado.
// Aqui assumimos que video_filename existe ou mapeamos de video_url de forma segura.
$filename = $row['video_filename'] ?? null;
if (!$filename) {
    // Exemplo simples: se video_url for "videos/arquivo.mp4" ou apenas "arquivo.mp4"
    // você deve mapear BLANCO ou usar um identificador. Evite confiar no valor bruto.
    // Para segurança, abortamos e pedimos mapeamento.
    http_response_code(500);
    echo "Arquivo não mapeado corretamente. Configure video_filename para o episódio.";
    exit;
}

// Sanitize: remove caminhos, mantém somente o basename
$filename = basename($filename);

// Extensões permitidas
$allowedExt = ['mp4','webm','m4v','ogg'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt, true)) {
    http_response_code(403);
    echo "Tipo de arquivo não permitido.";
    exit;
}

// Monta caminho completo (ajuste conforme seu projeto)
$videoPath = realpath(__DIR__ . '/../videos/' . $filename);
$videosDir = realpath(__DIR__ . '/../videos/');

if (!$videoPath || strpos($videoPath, $videosDir) !== 0 || !is_file($videoPath)) {
    http_response_code(404);
    echo "Arquivo de vídeo não encontrado.";
    exit;
}

// --- Serve o arquivo com suporte a Range (streaming) ---
$filesize = filesize($videoPath);
$fm = @fopen($videoPath, 'rb');
if (!$fm) {
    http_response_code(500);
    echo "Erro ao abrir arquivo.";
    exit;
}

$begin = 0;
$end = $filesize - 1;
$httpStatus = 200;
$headers = getallheaders();

// Suporta Range: "Range: bytes=START-END"
if (isset($_SERVER['HTTP_RANGE'])) {
    $range = $_SERVER['HTTP_RANGE'];
    if (preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
        if ($matches[1] !== '') $begin = intval($matches[1]);
        if ($matches[2] !== '') $end = intval($matches[2]);
        if ($begin > $end || $begin < 0 || $end >= $filesize) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header("Content-Range: bytes */$filesize");
            exit;
        }
        $httpStatus = 206;
    }
}

$length = $end - $begin + 1;
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $videoPath) ?: 'application/octet-stream';
finfo_close($finfo);

// Cabeçalhos
http_response_code($httpStatus);
header("Content-Type: {$mime}");
header('Accept-Ranges: bytes');
header("Content-Length: {$length}");
if ($httpStatus === 206) {
    header("Content-Range: bytes {$begin}-{$end}/{$filesize}");
}
// Cache curto (opcional)
header('Cache-Control: private, max-age=300');

// Se servidor suporta X-Sendfile (recomendado para produção), usar:
// header('X-Sendfile: ' . $videoPath);
// exit;

// Leitura parcial
$bufferSize = 8192;
fseek($fm, $begin);
$bytesSent = 0;
while (!feof($fm) && $bytesSent < $length) {
    $read = min($bufferSize, $length - $bytesSent);
    $data = fread($fm, $read);
    echo $data;
    flush();
    $bytesSent += strlen($data);
}
fclose($fm);
exit;
