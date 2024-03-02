<?php
header('Content-Type: application/json; charset=utf-8');

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se ha enviado el ID del usuario
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
    exit();
}

// Obtiene el ID del usuario
$id = $data['id'];

// Consulta el usuario por su ID
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
    } else {
        http_response_code(200);
        echo json_encode($user);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el usuario: ' . $e->getMessage()]);
}

?>
