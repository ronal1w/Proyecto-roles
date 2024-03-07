<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($data['token'], $data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de proyecto no válido']);
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

// Verifica si el role_id es 1 o 2 y elimina el proyecto, o devuelve un mensaje de error
if ($role_id == 1 || $role_id == 2) {
    $id = $data['id'];
    // Elimina el proyecto de la base de datos
    try {
        $stmt = $pdo->prepare("DELETE FROM proyectos WHERE id = ?");
        $stmt->execute([$id]);

        // Devuelve una respuesta exitosa
        http_response_code(200);
        echo json_encode(['message' => 'Proyecto eliminado correctamente']);
    } catch (PDOException $e) {
        // En caso de error, devuelve un mensaje de error
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar el proyecto: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Usuario no tiene permisos para eliminar un proyecto']);
}
?>
