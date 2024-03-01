<?php

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Determina la acción a realizar según el método y la URL
if ($method === 'GET' && isset($_GET['id'])) {
    // Consulta un empleado por su ID
    require __DIR__ . '/api/empleados/read_single.php';
} elseif ($method === 'GET') {
    // Consulta todos los empleados
    require __DIR__ . '/api/empleados/read.php';
} elseif ($method === 'POST') {
    // Crea un nuevo empleado
    require __DIR__ . '/api/empleados/create.php';
} elseif ($method === 'PUT') {
    // Actualiza un empleado
    require __DIR__ . '/api/empleados/update.php';
} elseif ($method === 'DELETE') {
    // Elimina un empleado
    require __DIR__ . '/api/empleados/delete.php';
} elseif ($method === 'GET' && isset($_GET['id'])) {
    // Consulta un proyecto por su ID
    require __DIR__ . '/api/proyectos/read_single.php';
} elseif ($method === 'GET') {
    // Consulta todos los proyectos
    require __DIR__ . '/api/proyectos/read.php';
} elseif ($method === 'POST') {
    // Crea un nuevo proyecto
    require __DIR__ . '/api/proyectos/create.php';
} elseif ($method === 'PUT') {
    // Actualiza un proyecto
    require __DIR__ . '/api/proyectos/update.php';
} elseif ($method === 'DELETE') {
    // Elimina un proyecto
    require __DIR__ . '/api/proyectos/delete.php';
} else {
    // Método no permitido
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

?>
