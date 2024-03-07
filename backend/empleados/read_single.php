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

// Verifica si se ha enviado el ID del empleado
if (!isset($data['id'], $data['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de empleado no proporcionado']);
    exit();
}

// Obtiene el ID del empleado
$id = $data['id'];

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

// Consulta el empleado por su ID
try {
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
    $stmt->execute([$id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empleado) {
        http_response_code(404);
        echo json_encode(['error' => 'Empleado no encontrado']);
    } else {
        http_response_code(200);
        echo json_encode($empleado);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el empleado: ' . $e->getMessage()]);
}

?>
