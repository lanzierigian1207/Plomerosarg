<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'Metodo no permitido. Usa POST.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function clean_text(mixed $value, int $maxLength): string
{
    $text = trim((string) $value);
    $text = strip_tags($text);
    if (mb_strlen($text) > $maxLength) {
        $text = mb_substr($text, 0, $maxLength);
    }
    return $text;
}

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);
    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

if (!$payload) {
    $payload = $_POST;
}

$nombre = clean_text($payload['nombre'] ?? '', 80);
$telefono = clean_text($payload['telefono'] ?? '', 30);
$zona = clean_text($payload['zona'] ?? '', 80);
$servicio = clean_text($payload['servicio'] ?? '', 20);
$puntuacion = clean_text($payload['puntuacion'] ?? '', 2);
$recomienda = clean_text($payload['recomienda'] ?? '', 5);
$comentario = clean_text($payload['comentario'] ?? '', 500);

if ($nombre === '' || $zona === '' || $servicio === '' || $puntuacion === '' || $recomienda === '') {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Completa todos los campos obligatorios.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$allowedServices = ['destapacion', 'reparacion', 'instalacion', 'emergencia'];
$allowedScores = ['1', '2', '3', '4', '5'];
$allowedRecommend = ['si', 'no'];

if (!in_array($servicio, $allowedServices, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Servicio invalido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!in_array($puntuacion, $allowedScores, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Puntuacion invalida.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!in_array($recomienda, $allowedRecommend, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Valor de recomendacion invalido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$record = [
    'created_at' => date('c'),
    'nombre' => $nombre,
    'telefono' => $telefono,
    'zona' => $zona,
    'servicio' => $servicio,
    'puntuacion' => (int) $puntuacion,
    'recomienda' => $recomienda,
    'comentario' => $comentario,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => clean_text($_SERVER['HTTP_USER_AGENT'] ?? '', 255),
];

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'datos';
if (!is_dir($dataDir) && !mkdir($dataDir, 0775, true) && !is_dir($dataDir)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'No se pudo crear la carpeta de datos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$filePath = $dataDir . DIRECTORY_SEPARATOR . 'encuestas.jsonl';
$line = json_encode($record, JSON_UNESCAPED_UNICODE);

if ($line === false || file_put_contents($filePath, $line . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'No se pudo guardar la encuesta.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'ok' => true,
    'message' => 'Encuesta guardada correctamente.'
], JSON_UNESCAPED_UNICODE);
