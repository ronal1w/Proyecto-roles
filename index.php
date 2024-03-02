<?php

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Determina la acción a realizar según el método y la URL
if ($method === 'GET' && $path === '/backend/empleados') {
    // Consulta todos los empleados
    require __DIR__ . '/backend/empleados/read.php';
} elseif ($method === 'POST' && $path === '/backend/empleados') {
    // Crea un nuevo empleado
    require __DIR__ . '/backend/empleados/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/empleados\/\d+/', $path)) {
    // Actualiza un empleado
    require __DIR__ . '/backend/empleados/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/empleados\/\d+/', $path)) {
    // Elimina un empleado
    require __DIR__ . '/backend/empleados/delete.php';
} elseif ($method === 'POST' && $path === '/backend/empleados/read_single') {
    // Consulta un empleado por su ID
    require __DIR__ . '/backend/empleados/read_single.php';
} elseif ($method === 'GET' && $path === '/backend/proyectos') {
    // Consulta todos los proyectos
    require __DIR__ . '/backend/proyectos/read.php';
} elseif ($method === 'POST' && $path === '/backend/proyectos') {
    // Crea un nuevo proyecto
    require __DIR__ . '/backend/proyectos/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/proyectos\/\d+/', $path)) {
    // Actualiza un proyecto
    require __DIR__ . '/backend/proyectos/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/proyectos\/\d+/', $path)) {
    // Elimina un proyecto
    require __DIR__ . '/backend/proyectos/delete.php';
} elseif ($method === 'POST' && $path === '/backend/proyectos/read_single.php') {
    // Consulta un proyecto por su ID
    require __DIR__ . '/backend/proyectos/read_single.php';
} elseif ($method === 'GET' && $path === '/backend/roles') {
    // Consulta todos los roles
    require __DIR__ . '/backend/roles/read.php';
} elseif ($method === 'POST' && $path === '/backend/roles') {
    // Crea un nuevo rol
    require __DIR__ . '/backend/roles/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/roles\/\d+/', $path)) {
    // Actualiza un rol
    require __DIR__ . '/backend/roles/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/roles\/\d+/', $path)) {
    // Elimina un rol
    require __DIR__ . '/backend/roles/delete.php';
} elseif ($method === 'GET' && $path === '/backend/users') {
        // Consulta un users por su ID
        require __DIR__ . '/backend/users/read_single.php';
    } elseif ($method === 'GET' && $path === '/backend/roles') {
    // Consulta todos los usuarios
    require __DIR__ . '/backend/users/read.php';
} elseif ($method === 'POST' && $path === '/backend/users') {
    // Crea un nuevo usuario
    require __DIR__ . '/backend/users/create.php';
} elseif ($method === 'PUT' && preg_match('/\/backend\/users\/\d+/', $path)) {
    // Actualiza un usuario
    require __DIR__ . '/backend/users/update.php';
} elseif ($method === 'DELETE' && preg_match('/\/backend\/users\/\d+/', $path)) {
    // Elimina un usuario
    require __DIR__ . '/backend/users/delete.php';
} else {
    // Ruta no encontrada
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}

?>
