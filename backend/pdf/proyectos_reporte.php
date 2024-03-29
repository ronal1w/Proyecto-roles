<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';
require_once '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de composer

use Dompdf\Dompdf;
use Dompdf\Options;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(400);
    echo json_encode(['error' => 'Solicitud incorrecta']);
    exit();
}

$stmt = $pdo->prepare("SELECT 
p.id, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, e.nombre AS id_responsable 
FROM  proyectos AS p INNER JOIN empleados AS e 
ON p.id_responsable = e.id");
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Proyectos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
       <center> <h4>Reporte de Proyectos</h4> </center>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>';

foreach ($proyectos as $proyecto) {
    $html .= '
                <tr>
                    <td>' . $proyecto['id'] . '</td>
                    <td>' . $proyecto['nombre'] . '</td>
                    <td>' . $proyecto['descripcion'] . '</td>
                    <td>' . $proyecto['fecha_inicio'] . '</td>
                    <td>' . $proyecto['fecha_fin'] . '</td>
                    <td>' . $proyecto['id_responsable'] . '</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream('reporte_proyectos.pdf', array('Attachment' => 0));
?>
