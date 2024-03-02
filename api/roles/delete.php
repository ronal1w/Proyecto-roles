<?php

require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el id del rol
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incorrectos o incompletos']);
    exit();
}

// Obtiene el id del rol
$id = $data['id'];

// Verifica si el id del rol es 1 (rol que no se puede eliminar)
if ($id == 1) {
    http_response_code(400);
    echo json_encode(['error' => 'No se puede eliminar el rol de admin']);
    exit();
}

// Elimina el rol de la base de datos
try {
    $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
    $stmt->execute([$id]);

    // Devuelve una respuesta exitosa
    http_response_code(200);
    echo json_encode(['message' => 'Rol eliminado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el rol: ' . $e->getMessage()]);
}

?>
