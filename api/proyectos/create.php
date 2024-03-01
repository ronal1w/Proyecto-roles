<?php

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($data['nombre'], $data['descripcion'], $data['fecha_inicio'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene los datos del array
$nombre = $data['nombre'];
$descripcion = $data['descripcion'];
$fecha_inicio = $data['fecha_inicio'];
$fecha_fin = isset($data['fecha_fin']) ? $data['fecha_fin'] : null;
$id_responsable = isset($data['id_responsable']) ? $data['id_responsable'] : null;

// Crea un nuevo proyecto en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin, id_responsable) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $fecha_inicio, $fecha_fin, $id_responsable]);

    // Devuelve una respuesta exitosa
    http_response_code(201);
    echo json_encode(['message' => 'Proyecto creado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el proyecto: ' . $e->getMessage()]);
}

?>
