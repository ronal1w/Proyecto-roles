<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$sql = "SELECT * FROM users WHERE email = :email AND password = :password";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email, 'password' => $password]);
$user = $stmt->fetch();

if ($user) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
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

    echo json_encode(['success' => true, 'role' => $_SESSION['role']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos']);
}
?>
