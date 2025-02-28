<?php

include_once "config.php";
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$tipo = $data["tipo"] ?? "";

if ($tipo === "sedes") {
    $datos = loadJSON("sedes.json");
    $titulo = "Reporte de Sedes";
    $nombreArchivo = "sedes.pdf";
} elseif ($tipo === "canales") {
    $datos = loadJSON("canales.json");
    $titulo = "Reporte de Canales";
    $nombreArchivo = "canales.pdf";
} elseif ($tipo === "contratos") {
    $datos = loadJSON("historico.json");
    $titulo = "Reporte de Contratos";
    $nombreArchivo = "contratos.pdf";
} else {
    echo json_encode(["status" => "error", "message" => "Tipo no válido"]);
    exit();
}

$html = "<h1>$titulo</h1><table border='1' cellpadding='5' cellspacing='0'><tr>";
$columnas = array_keys($datos[0]);
foreach ($columnas as $col) {
    $html .= "<th>$col</th>";
}
$html .= "</tr>";

foreach ($datos as $fila) {
    $html .= "<tr>";
    foreach ($fila as $valor) {
        $html .= "<td>$valor</td>";
    }
    $html .= "</tr>";
}
$html .= "</table>";

$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filePath = DATA_PATH . $nombreArchivo;
file_put_contents($filePath, $dompdf->output());

echo json_encode(["status" => "success", "file" => $nombreArchivo]);
exit();

?>
