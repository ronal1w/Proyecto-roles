<?php
// Permitir solicitudes CORS desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir los métodos GET, POST, PUT, DELETE
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// Permitir los encabezados Content-Type y Authorization
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios y si son válidos
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    !isset($data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['token']) ||
    empty(trim($data['nombre'])) || 
    empty(trim($data['apellido'])) || 
    empty(trim($data['email'])) || 
    empty(trim($data['telefono'])) ||
    !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^\d{8,15}$/', $data['telefono'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incorrectos o incompletos']);
    exit();
}

// Obtiene el token del usuario del array
$token = $data['token'];

// Verifica si el token recibido es el mismo que el generado durante el login
$secret_key = 'ron_melgar'; // La misma clave secreta que usaste para firmar el token JWT
$decoded_token = null;
try {
    $decoded_token = JWT::decode($token, $secret_key, array('HS256'));
} catch (\Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

if (!$decoded_token || !isset($decoded_token->user_id)) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

// Obtiene los datos del array
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$email = $data['email'];
$telefono = $data['telefono'];

// Verifica si el correo electrónico ya existe en la base de datos
$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM empleados WHERE email = ?");
$stmt->execute([$email]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result['count'] > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El correo electrónico ya está registrado']);
    exit();
}

// Verifica si el número de teléfono ya existe en la base de datos
$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM empleados WHERE telefono = ?");
$stmt->execute([$telefono]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result['count'] > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El número de teléfono ya está registrado']);
    exit();
}

// Crea un nuevo empleado en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO empleados (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellido, $email, $telefono]);

    // Devuelve una respuesta exitosa
    http_response_code(201);
    echo json_encode(['message' => 'Empleado creado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el empleado: ' . $e->getMessage()]);
}

?>
