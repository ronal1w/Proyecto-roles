<?php
header('Content-Type: application/json; charset=utf-8');

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene los datos del array
$name = $data['name'];

// Crea un nuevo rol en la base de datos
try {
    require_once '../../config/database.php';

    $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (?)");
    $stmt->execute([$name]);

    // Devuelve una respuesta exitosa
    http_response_code(201);
    echo json_encode(['message' => 'Rol creado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el rol: ' . $e->getMessage()]);
}

?>
