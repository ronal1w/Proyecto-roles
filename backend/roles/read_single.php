<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el ID del rol y el token
if (!isset($data['id']) || !isset($data['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de rol o token no proporcionado']);
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

// Obtiene el role_id del usuario
$user_id = $decoded_token->user_id;
$stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$role_id = $user['role_id'];

// Consulta el rol por su ID
try {
    $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$data['id']]);
    $rol = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rol) {
        http_response_code(404);
        echo json_encode(['error' => 'Rol no encontrado']);
    } else {
        if ($role_id == 1 || $rol['id'] == $role_id) {
            http_response_code(200);
            echo json_encode($rol);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Usuario no tiene permisos para acceder a este rol']);
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el rol: ' . $e->getMessage()]);
}

?>
