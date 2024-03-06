<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';
require_once '../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Firebase\JWT\JWT;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$token = $data['token'] ?? '';

$secret_key = 'ron_melgar'; // La misma clave secreta que usaste para firmar el token JWT

try {
    $decoded = JWT::decode($token, $secret_key, array('HS256'));

    // Los datos decodificados estarán disponibles en forma de objeto
    $user_id = $decoded->user_id;

    // Puedes imprimir los datos decodificados
    echo json_encode(['success' => true, 'user_id' => $user_id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
