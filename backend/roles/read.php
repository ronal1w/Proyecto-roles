<?php
header('Content-Type: application/json; charset=utf-8');


require_once '../../config/database.php';

// Obtiene todos los roles de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM roles");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devuelve los roles en formato JSON
    echo json_encode($roles);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los roles: ' . $e->getMessage()]);
}

?>
