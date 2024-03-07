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

$stmt = $pdo->prepare("SELECT * FROM empleados");
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados</title>
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
        <h5>Reporte de Empleados</h5>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>';

foreach ($empleados as $empleado) {
    $html .= '
                <tr>
                    <td>' . $empleado['id'] . '</td>
                    <td>' . $empleado['nombre'] . '</td>
                    <td>' . $empleado['apellido'] . '</td>
                    <td>' . $empleado['email'] . '</td>
                    <td>' . $empleado['telefono'] . '</td>
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

$dompdf->stream('reporte_empleados.pdf', array('Attachment' => 0));
?>
