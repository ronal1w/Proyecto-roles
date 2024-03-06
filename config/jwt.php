<?php
// config/jwt.php
require_once __DIR__ . '../../vendor/autoload.php';


use \Firebase\JWT\JWT;

// Define la clave secreta para firmar el token JWT
$secret_key = 'tu_clave_secreta';

// Función para verificar el token JWT
function verifyToken() {
    // Obtiene el token JWT de los headers de la solicitud
    $headers = apache_request_headers();
    $token = $headers['Authorization'] ?? '';

    // Verifica si el token es válido
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Token no proporcionado']);
        exit();
    }

    global $secret_key; // La clave secreta que usaste para firmar el token JWT

    try {
        // Verifica y decodifica el token JWT
        $decoded = JWT::decode($token, $secret_key, ['HS256']);

        // Si el token es válido, devuelve los datos decodificados
        return $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token no válido: ' . $e->getMessage()]);
        exit();
    }
}
?>
