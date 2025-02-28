<?php

include_once "config.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$tipo = $data["tipo"] ?? "";

if ($tipo === "sedes") {
    $datos = loadJSON("sedes.json");
    $nombreArchivo = "sedes.xlsx";
} elseif ($tipo === "canales") {
    $datos = loadJSON("canales.json");
    $nombreArchivo = "canales.xlsx";
} elseif ($tipo === "contratos") {
    $datos = loadJSON("historico.json");
    $nombreArchivo = "contratos.xlsx";
} else {
    echo json_encode(["status" => "error", "message" => "Tipo no válido"]);
    exit();
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$columnas = array_keys($datos[0]);
$sheet->fromArray([$columnas], NULL, 'A1');
$sheet->fromArray($datos, NULL, 'A2');

$writer = new Xlsx($spreadsheet);
$filepath = DATA_PATH . $nombreArchivo;
$writer->save($filepath);

echo json_encode(["status" => "success", "file" => $nombreArchivo]);
exit();

?>