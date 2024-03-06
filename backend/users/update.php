<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

// Obtiene el contenido JSON enviado en la solicitud
$json = file_get_contents('php://input');

// Convierte el JSON en un array asociativo
$data = json_decode($json, true);

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'PUT' || !isset($data['id'], $data['email'], $data['password'], $data['first_name'], $data['last_name'], $data['role_id'], $data['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene el token del usuario del array
$token = $data['token'];

// Verifica si el token recibido es el mismo que el generado durante el login
$secret_key = 'ron_melgar'; // La misma clave secreta que usaste para firmar el token JWT
$decoded_token = null;
try {
    $decoded_token = JWT::decode($token, $secret_key, array('HS256'));
} catch (\Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

if (!$decoded_token || !isset($decoded_token->user_id)) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

// Obtiene el role_id del usuario
$user_id = $decoded_token->user_id;
$stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$role_id = $user['role_id'];

// Verifica si el role_id es 1 y actualiza el usuario en la base de datos, o devuelve un mensaje de error
if ($role_id == 1) {
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
} else {
    echo json_encode(['error' => 'Usuario no tiene permisos para actualizar un usuario']);
}
?>
