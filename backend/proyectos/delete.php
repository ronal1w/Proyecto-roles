<?php
header('Content-Type: application/json; charset=utf-8');

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el ID del proyecto a eliminar
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de proyecto no válido']);
    exit();
}

// Obtiene el ID del proyecto
$id = $data['id'];

// Verifica si el proyecto existe
$stmt = $pdo->prepare("SELECT COUNT(*) FROM proyectos WHERE id = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count === 0) {
    // El proyecto no existe, devuelve un mensaje de error
    http_response_code(404);
    echo json_encode(['error' => 'El proyecto con el ID especificado no existe']);
    exit();
}

// Elimina el proyecto de la base de datos
try {
    $stmt = $pdo->prepare("DELETE FROM proyectos WHERE id = ?");
    $stmt->execute([$id]);

    // Devuelve una respuesta exitosa
    http_response_code(200);
    echo json_encode(['message' => 'Proyecto eliminado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el proyecto: ' . $e->getMessage()]);
}

?>

