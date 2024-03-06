<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Recibe el token del usuario
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$token = $data['token'] ?? '';

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

// Verifica si el role_id es 1 y muestra la lista de datos, o devuelve un mensaje de error
if ($role_id == 1) {
    try {
        $stmt = $pdo->query("SELECT * FROM roles");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devuelve los datos en formato JSON
        echo json_encode($datos);
    } catch (PDOException $e) {
        // En caso de error, devuelve un mensaje de error
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Usuario no tiene permisos para ver estos datos']);
}
?>
