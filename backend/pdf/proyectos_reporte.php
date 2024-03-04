<?php
// Realiza la consulta a la API para obtener los datos de los proyectos
$api_url = 'http://localhost/proyecto-roles/backend/proyectos/read.php';
$proyectos = file_get_contents($api_url);
$proyectos = json_decode($proyectos, true);
?>


<?php
require_once '../../vendor/autoload.php'; // Reemplaza con la ruta correcta a tu archivo autoload.php de Composer

use Dompdf\Dompdf;
use Dompdf\Options;

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
        <h1>Reporte de Proyectos</h1>
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
