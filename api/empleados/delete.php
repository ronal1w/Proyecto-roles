<?php

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el ID del empleado
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incorrectos o incompletos']);
    exit();
}

// Obtiene el ID del empleado a eliminar
$id = $data['id'];

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Elimina el empleado de la base de datos
try {
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
    $stmt->execute([$id]);

    // Verifica si se eliminó algún registro
    if ($stmt->rowCount() > 0) {
        // Devuelve una respuesta exitosa
        http_response_code(200);
        echo json_encode(['message' => 'Empleado eliminado correctamente']);
    } else {
        // No se encontró el empleado con el ID especificado
        http_response_code(404);
        echo json_encode(['error' => 'Empleado no encontrado']);
    }
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el empleado: ' . $e->getMessage()]);
}

?>
