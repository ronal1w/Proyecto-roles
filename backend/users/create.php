<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($data['email'], $data['password'], $data['first_name'], $data['last_name'], $data['role_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene los datos del array
$email = $data['email'];
$password = $data['password'];
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$role_id = $data['role_id'];

// Verifica si el correo electrónico tiene un formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de correo electrónico inválido']);
    exit();
}

// Verifica si el rol existe en la base de datos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE id = ?");
$stmt->execute([$role_id]);
if ($stmt->fetchColumn() === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El rol especificado no existe']);
    exit();
}

// Crea un nuevo usuario en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$email, $password, $first_name, $last_name, $role_id]);

    // Devuelve una respuesta exitosa
    http_response_code(201);
    echo json_encode(['message' => 'Usuario creado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
}

?>
