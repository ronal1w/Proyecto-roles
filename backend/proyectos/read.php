<?php
header('Content-Type: application/json; charset=utf-8');

// Crea una conexión a la base de datos
require_once '../../config/database.php';

// Obtiene todos los proyectos de la base de datos
try {
    $stmt = $pdo->query("SELECT 
    p.id, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, e.nombre AS id_responsable 
    FROM  proyectos AS p INNER JOIN empleados AS e 
     ON p.id_responsable = e.id
    ");
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devuelve los proyectos en formato JSON
    echo json_encode($proyectos);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los proyectos: ' . $e->getMessage()]);
}

?>
