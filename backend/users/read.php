<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

// Obtiene todos los usuarios de la base de datos
try {
    $stmt = $pdo->query("SELECT u.id, u.email, u.password, u.first_name, u.last_name, r.name
    FROM users AS u
    INNER JOIN roles AS r ON u.role_id = r.id;
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devuelve los usuarios en formato JSON
    echo json_encode($users);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los usuarios: ' . $e->getMessage()]);
}

?>
