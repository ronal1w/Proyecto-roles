<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el id del rol
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($data['id']) || !isset($data['token'])) {
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

// Obtiene el role_id del usuario
$user_id = $decoded_token->user_id;
$stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$role_id = $user['role_id'];

// Verifica si el role_id es 1 y elimina el rol de la base de datos, o devuelve un mensaje de error
if ($role_id == 1) {
    // Obtiene el id del rol
    $id = $data['id'];

    // Verifica si el id del rol es 1 (rol que no se puede eliminar)
    if ($id == 1) {
        http_response_code(400);
        echo json_encode(['error' => 'No se puede eliminar el rol de admin']);
        exit();
    }

    // Verifica si el rol existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count === 0) {
        // El rol no existe, devuelve un mensaje de error
        http_response_code(404);
        echo json_encode(['error' => 'El rol con el ID especificado no existe']);
        exit();
    }

    // Elimina el rol de la base de datos
    try {
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->execute([$id]);

        // Devuelve una respuesta exitosa
        http_response_code(200);
        echo json_encode(['message' => 'Rol eliminado correctamente']);
    } catch (PDOException $e) {
        // En caso de error, devuelve un mensaje de error
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar el rol: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Usuario no tiene permisos para eliminar un rol']);
}
?>
