<?php
header('Content-Type: application/json; charset=utf-8');

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el ID del proyecto
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de proyecto no proporcionado']);
    exit();
}

// Obtiene el ID del proyecto
$id = $data['id'];

// Consulta el proyecto por su ID
try {
    $stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id = ?");
    $stmt->execute([$id]);
    $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        http_response_code(404);
        echo json_encode(['error' => 'Proyecto no encontrado']);
    } else {
        http_response_code(200);
        echo json_encode($proyecto);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el proyecto: ' . $e->getMessage()]);
}

?>
