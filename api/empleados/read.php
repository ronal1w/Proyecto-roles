<?php

require_once __DIR__ . '/../../config/database.php';

// Obtiene todos los empleados de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM empleados");
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devuelve los empleados en formato JSON
    echo json_encode($empleados);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los empleados: ' . $e->getMessage()]);
}
