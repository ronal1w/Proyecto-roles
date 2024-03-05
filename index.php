<?php

require_once __DIR__ . '/vendor/autoload.php'; // Carga el paquete firebase/php-jwt

use \Firebase\JWT\JWT;

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Determina la acción a realizar según el método y la URL
if ($method === 'GET' && $path === '/backend/empleados') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta todos los empleados
    require __DIR__ . '/backend/empleados/read.php';
} elseif ($method === 'POST' && $path === '/backend/empleados') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Crea un nuevo empleado
    require __DIR__ . '/backend/empleados/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/empleados\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Actualiza un empleado
    require __DIR__ . '/backend/empleados/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/empleados\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Elimina un empleado
    require __DIR__ . '/backend/empleados/delete.php';
} elseif ($method === 'POST' && $path === '/backend/empleados/read_single') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta un empleado por su ID
    require __DIR__ . '/backend/empleados/read_single.php';
} elseif ($method === 'GET' && $path === '/backend/proyectos') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta todos los proyectos
    require __DIR__ . '/backend/proyectos/read.php';
} elseif ($method === 'POST' && $path === '/backend/proyectos') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Crea un nuevo proyecto
    require __DIR__ . '/backend/proyectos/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/proyectos\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Actualiza un proyecto
    require __DIR__ . '/backend/proyectos/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/proyectos\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Elimina un proyecto
    require __DIR__ . '/backend/proyectos/delete.php';
} elseif ($method === 'POST' && $path === '/backend/proyectos/read_single') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta un proyecto por su ID
    require __DIR__ . '/backend/proyectos/read_single.php';
} elseif ($method === 'GET' && $path === '/backend/roles') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta todos los roles
    require __DIR__ . '/backend/roles/read.php';
} elseif ($method === 'POST' && $path === '/backend/roles') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Crea un nuevo rol
    require __DIR__ . '/backend/roles/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/roles\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Actualiza un rol
    require __DIR__ . '/backend/roles/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/roles\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Elimina un rol
    require __DIR__ . '/backend/roles/delete.php';
} elseif ($method === 'GET' && $path === '/backend/users') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Consulta todos los usuarios
    require __DIR__ . '/backend/users/read.php';
} elseif ($method === 'POST' && $path === '/backend/users') {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Crea un nuevo usuario
    require __DIR__ . '/backend/users/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/users\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Actualiza un usuario
    require __DIR__ . '/backend/users/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/users\/\d+/', $path)) {
    // Verifica el token JWT antes de procesar la solicitud
    verifyToken();

    // Elimina un usuario
    require __DIR__ . '/backend/users/delete.php';
} else {
    // Ruta no encontrada
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}

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

    try {
        // Verifica y decodifica el token JWT
        $secret_key = 'your_secret_key'; // La clave secreta que usaste para firmar el token JWT
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
