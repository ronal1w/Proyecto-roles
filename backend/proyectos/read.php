<?php
header('Content-Type: application/json; charset=utf-8');

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene todos los proyectos de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM proyectos");
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devuelve los proyectos en formato JSON
    echo json_encode($proyectos);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los proyectos: ' . $e->getMessage()]);
}

?>
