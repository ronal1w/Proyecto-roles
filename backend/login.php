<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';
require_once '../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$sql = "SELECT * FROM users WHERE email = :email AND password = :password";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email, 'password' => $password]);
$user = $stmt->fetch();

if ($user) {
    $user_id = $user['id'];
    $secret_key = 'your_secret_key'; // La clave secreta que usas para firmar el token JWT

    // Crear el token JWT con el user_id
    $payload = array(
        'user_id' => $user_id
    );
    $token = JWT::encode($payload, $secret_key, 'HS256');

    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];

    // Determinar la jerarquía de los usuarios
    $role_id = $user['role_id'];
    if ($role_id == 1) {
        $_SESSION['role'] = 'Admin';
    } elseif ($role_id == 2) {
        $_SESSION['role'] = 'Moderator';
    } else {
        $_SESSION['role'] = 'User';
    }

    echo json_encode(['success' => true, 'role' => $_SESSION['role'], 'token' => $token]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos']);
}
