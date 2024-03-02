<?php

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Determina la acción a realizar según el método y la URL
if ($method === 'GET' && isset($_GET['id'])) {
    // Consulta un empleado por su ID
    require __DIR__ . '/api/empleados/read_single.php';
} elseif ($method === 'GET' && isset($_GET['empleados'])) {
    // Consulta todos los empleados
    require __DIR__ . '/api/empleados/read.php';
} elseif ($method === 'POST' && isset($_POST['empleados'])) {
    // Crea un nuevo empleado
    require __DIR__ . '/api/empleados/create.php';
} elseif ($method === 'PUT' && isset($_POST['empleados'])) {
    // Actualiza un empleado
    require __DIR__ . '/api/empleados/update.php';
} elseif ($method === 'DELETE' && isset($_GET['id']) && isset($_GET['empleados'])) {
    // Elimina un empleado
    require __DIR__ . '/api/empleados/delete.php';
} elseif ($method === 'GET' && isset($_GET['proyectos'])) {
    // Consulta todos los proyectos
    require __DIR__ . '/api/proyectos/read.php';
} elseif ($method === 'POST' && isset($_POST['proyectos'])) {
    // Crea un nuevo proyecto
    require __DIR__ . '/api/proyectos/create.php';
} elseif ($method === 'PUT' && isset($_POST['proyectos'])) {
    // Actualiza un proyecto
    require __DIR__ . '/api/proyectos/update.php';
} elseif ($method === 'DELETE' && isset($_GET['id']) && isset($_GET['proyectos'])) {
    // Elimina un proyecto
    require __DIR__ . '/api/proyectos/delete.php';
} elseif ($method === 'GET' && isset($_GET['roles'])) {
    // Consulta todos los roles
    require __DIR__ . '/api/roles/read.php';
} elseif ($method === 'POST' && isset($_POST['roles'])) {
    // Crea un nuevo rol
    require __DIR__ . '/api/roles/create.php';
} elseif ($method === 'PUT' && isset($_POST['roles'])) {
    // Actualiza un rol
    require __DIR__ . '/api/roles/update.php';
} elseif ($method === 'DELETE' && isset($_GET['id']) && isset($_GET['roles'])) {
    // Elimina un rol
    require __DIR__ . '/api/roles/delete.php';
} else {
    // Método no permitido o solicitud no encontrada
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

?>

