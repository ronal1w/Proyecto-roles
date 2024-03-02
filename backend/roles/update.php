<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'PUT' || !isset($data['id'], $data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incorrectos o incompletos']);
    exit();
}

// Obtiene los datos del array
$id = $data['id'];
$name = $data['name'];

// Actualiza el rol en la base de datos
try {
    $stmt = $pdo->prepare("UPDATE roles SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);

    // Devuelve una respuesta exitosa
    http_response_code(200);
    echo json_encode(['message' => 'Rol actualizado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar el rol: ' . $e->getMessage()]);
}

?>

