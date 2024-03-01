<?php

require_once '../../config/database.php';

// Verifica si se han enviado los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit();
}

// Obtiene los datos enviados por el cliente
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];

// Actualiza el empleado en la base de datos
try {
    $stmt = $pdo->prepare("UPDATE empleados SET nombre=?, apellido=?, email=?, telefono=? WHERE id=?");
    $stmt->execute([$nombre, $apellido, $email, $telefono, $id]);
    
    // Devuelve una respuesta exitosa
    http_response_code(200);
    echo json_encode(['message' => 'Empleado actualizado correctamente']);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar el empleado: ' . $e->getMessage()]);
}
