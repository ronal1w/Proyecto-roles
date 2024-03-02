<?php

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'PUT' || !isset($data['id'], $data['email'], $data['password'], $data['first_name'], $data['last_name'], $data['role_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene los datos del array
$id = $data['id'];
$email = $data['email'];
$password = $data['password'];
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$role_id = $data['role_id'];

// Verifica el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de correo electrónico incorrecto']);
    exit();
}

// Verifica la longitud y formato de la contraseña
if (strlen($password) < 8 || strlen($password) > 20 || !preg_match('/^[a-zA-Z0-9@#$%^&*()_-]+$/', $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'La contraseña debe tener entre 8 y 20 caracteres y contener solo letras, números y algunos caracteres especiales como @, #, $, %, ^, &, *, (, ), _, -']);
    exit();
}

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Actualiza un usuario en la base de datos
try {
    $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ?, first_name = ?, last_name = ?, role_id = ? WHERE id = ?");
    $stmt->execute([$email, $password, $first_name, $last_name, $role_id, $id]);

    // Devuelve una respuesta exitosa
    http_response_code(200);
    echo json_encode(['message' => 'Usuario actualizado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
}

?>
