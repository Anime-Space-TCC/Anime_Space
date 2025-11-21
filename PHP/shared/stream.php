<?php
// Defina a chave secreta usada para hash_hmac
define('SECRET_KEY', 'sua_chave_secreta_aqui');

// Função: session_start
session_start();

// Função: require
require __DIR__ . '/conexao.php';
require __DIR__ . '/auth.php';

// Função: filter_input
$episodeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$expires = filter_input(INPUT_GET, 'expires', FILTER_VALIDATE_INT);

// Função: hash_hmac
$expected = hash_hmac('sha256', $episodeId . '|' . $userId . '|' . $expires, SECRET_KEY);

// Função: hash_equals
if (!hash_equals($expected, $token)) {
    http_response_code(403);
    echo "Token inválido.";
    exit;
}

// Função: $pdo->prepare
$stmt = $pdo->prepare("SELECT video_url, video_filename FROM episodios WHERE id = ?");

// Função: $stmt->execute
$stmt->execute([$episodeId]);

// Função: $stmt->fetch
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Função: basename
$filename = basename($filename);

// Função: pathinfo
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

// Função: in_array
if (!in_array($ext, $allowedExt, true)) {
    http_response_code(403);
    echo "Tipo de arquivo não permitido.";
    exit;
}

// Função: realpath
$videoPath = realpath(__DIR__ . '/../videos/' . $filename);
$videosDir = realpath(__DIR__ . '/../videos/');

// Função: strpos
if (!$videoPath || strpos($videoPath, $videosDir) !== 0 || !is_file($videoPath)) {
    http_response_code(404);
    echo "Arquivo de vídeo não encontrado.";
    exit;
}

// Função: filesize
$filesize = filesize($videoPath);

// Função: fopen
$fm = @fopen($videoPath, 'rb');

// Função: getallheaders
$headers = getallheaders();

// Função: preg_match
if (preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
    // ...
}

// Função: finfo_open
$finfo = finfo_open(FILEINFO_MIME_TYPE);

// Função: finfo_file
$mime = finfo_file($finfo, $videoPath) ?: 'application/octet-stream';

// Função: finfo_close
finfo_close($finfo);

// Função: fseek
fseek($fm, $begin);

// Função: feof
while (!feof($fm) && $bytesSent < $length) {
    // ...
}

// Função: fread
$data = fread($fm, $read);

// Função: flush
flush();

// Função: fclose
fclose($fm);
exit;
