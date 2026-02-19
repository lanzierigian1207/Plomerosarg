<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'Método no permitido. Usá POST.'
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

function normalize_profesion(mixed $value): array
{
    $items = [];

    if (is_array($value)) {
        $items = $value;
    } else {
        $raw = clean_text($value, 300);
        if ($raw !== '') {
            $items = array_map('trim', explode(',', $raw));
        }
    }

    $normalized = [];
    foreach ($items as $item) {
        $clean = clean_text($item, 40);
        if ($clean !== '') {
            $normalized[$clean] = true;
        }
    }

    return array_keys($normalized);
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

$dni = clean_text($payload['dni'] ?? '', 20);
$encuentro = clean_text($payload['encuentro'] ?? '', 80);
$nombreApellido = clean_text($payload['nombre_apellido'] ?? '', 120);
$mail = clean_text($payload['mail'] ?? '', 120);
$provincia = clean_text($payload['provincia'] ?? '', 40);
$localidad = clean_text($payload['localidad'] ?? '', 120);
$asociado = clean_text($payload['asociado'] ?? '', 5);
$profesiones = normalize_profesion($payload['profesion'] ?? '');
$profesion = implode(',', $profesiones);
$origen = clean_text($payload['origen'] ?? '', 40);
$aceptoTerminos = clean_text($payload['acepto_terminos'] ?? '', 5);

if (
    $dni === '' ||
    $nombreApellido === '' ||
    $mail === '' ||
    $provincia === '' ||
    $asociado === '' ||
    $profesion === '' ||
    $origen === '' ||
    $aceptoTerminos !== 'si'
) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Completá todos los campos obligatorios y aceptá los términos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Mail inválido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$allowedProvincias = [
    'buenos_aires', 'caba', 'catamarca', 'chaco', 'chubut', 'cordoba', 'corrientes',
    'entre_rios', 'formosa', 'jujuy', 'la_pampa', 'la_rioja', 'mendoza', 'misiones',
    'neuquen', 'rio_negro', 'salta', 'san_juan', 'san_luis', 'santa_cruz', 'santa_fe',
    'santiago_del_estero', 'tierra_del_fuego', 'tucuman'
];

$allowedAsociado = ['si', 'no'];
$allowedProfesion = [
    'plomero', 'gasista',
    'oficial_plomero', 'ayudante_plomero', 'medio_oficial_plomero', 'maestro_mayor_obra',
    'arquitecto_ingeniero', 'estudiante_centro_formacion', 'expositor', 'otros'
];
$allowedOrigen = ['facebook', 'grupo_whatsapp', 'instagram', 'casa_sanitarios', 'centro_formacion'];

if ($encuentro === '') {
    $encuentro = 'general';
}

if (!in_array($provincia, $allowedProvincias, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Provincia inválida.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!in_array($asociado, $allowedAsociado, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Valor de asociado inválido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

foreach ($profesiones as $profesionItem) {
    if (!in_array($profesionItem, $allowedProfesion, true)) {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'error' => 'Profesión inválida.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!in_array($origen, $allowedOrigen, true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Origen inválido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$record = [
    'created_at' => date('c'),
    'encuentro' => $encuentro,
    'dni' => $dni,
    'nombre_apellido' => $nombreApellido,
    'mail' => $mail,
    'provincia' => $provincia,
    'localidad' => $localidad,
    'asociado' => $asociado,
    'profesion' => $profesion,
    'origen' => $origen,
    'acepto_terminos' => true,
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

$filePath = $dataDir . DIRECTORY_SEPARATOR . 'inscripciones.jsonl';
$line = json_encode($record, JSON_UNESCAPED_UNICODE);

if ($line === false || file_put_contents($filePath, $line . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'No se pudo guardar la inscripción.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'ok' => true,
    'message' => 'Inscripción guardada correctamente.'
], JSON_UNESCAPED_UNICODE);
