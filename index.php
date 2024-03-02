<?php

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Determina la acción a realizar según el método y la URL
if ($method === 'GET' && $path === '/api/empleados') {
    // Consulta todos los empleados
    require __DIR__ . '/api/empleados/read.php';
} elseif ($method === 'POST' && $path === '/api/empleados') {
    // Crea un nuevo empleado
    require __DIR__ . '/api/empleados/create.php';
} elseif ($method === 'PUT' && preg_match('/\/api\/empleados\/\d+/', $path)) {
    // Actualiza un empleado
    require __DIR__ . '/api/empleados/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/api\/empleados\/\d+/', $path)) {
    // Elimina un empleado
    require __DIR__ . '/api/empleados/delete.php';
} elseif ($method === 'GET' && $path === '/api/proyectos') {
    // Consulta todos los proyectos
    require __DIR__ . '/api/proyectos/read.php';
} elseif ($method === 'POST' && $path === '/api/proyectos') {
    // Crea un nuevo proyecto
    require __DIR__ . '/api/proyectos/create.php';
} elseif ($method === 'PUT' && preg_match('/\/api\/proyectos\/\d+/', $path)) {
    // Actualiza un proyecto
    require __DIR__ . '/api/proyectos/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/api\/proyectos\/\d+/', $path)) {
    // Elimina un proyecto
    require __DIR__ . '/api/proyectos/delete.php';
} elseif ($method === 'GET' && $path === '/api/roles') {
    // Consulta todos los roles
    require __DIR__ . '/api/roles/read.php';
} elseif ($method === 'POST' && $path === '/api/roles') {
    // Crea un nuevo rol
    require __DIR__ . '/api/roles/create.php';
} elseif ($method === 'PUT' && preg_match('/\/api\/roles\/\d+/', $path)) {
    // Actualiza un rol
    require __DIR__ . '/api/roles/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/api\/roles\/\d+/', $path)) {
    // Elimina un rol
    require __DIR__ . '/api/roles/delete.php';
} elseif ($method === 'GET' && $path === '/api/users') {
    // Consulta todos los usuarios
    require __DIR__ . '/api/users/read.php';
} elseif ($method === 'POST' && $path === '/api/users') {
    // Crea un nuevo usuario
    require __DIR__ . '/api/users/create.php';
} elseif ($method === 'PUT' && preg_match('/\/api\/users\/\d+/', $path)) {
    // Actualiza un usuario
    require __DIR__ . '/api/users/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/api\/users\/\d+/', $path)) {
    // Elimina un usuario
    require __DIR__ . '/api/users/delete.php';
} else {
    // Ruta no encontrada
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}

?>


