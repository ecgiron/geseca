<?php

include_once "config.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$accion = $data["accion"] ?? "";

$sedes = loadJSON("sedes.json");

switch ($accion) {
    case "listar":
        echo json_encode(["status" => "success", "sedes" => $sedes]);
        break;

    case "crear":
        $nueva_sede = [
            "db_COD_SEDE" => uniqid("sede_"),
            "db_NOMBRE_SEDE" => $data["nombre"],
            "db_TIPO_SEDE" => $data["tipo"],
            "db_DEPARTAMENTO" => $data["departamento"],
            "db_MUNICIPIO" => $data["municipio"],
            "db_SD_WAN" => $data["sdwan"]
        ];
        $sedes[] = $nueva_sede;
        saveJSON("sedes.json", $sedes);
        echo json_encode(["status" => "success", "sede" => $nueva_sede]);
        break;

    case "editar":
        foreach ($sedes as &$sede) {
            if ($sede["db_COD_SEDE"] === $data["codigo"]) {
                $sede["db_NOMBRE_SEDE"] = $data["nombre"];
                $sede["db_TIPO_SEDE"] = $data["tipo"];
                $sede["db_DEPARTAMENTO"] = $data["departamento"];
                $sede["db_MUNICIPIO"] = $data["municipio"];
                $sede["db_SD_WAN"] = $data["sdwan"];
                saveJSON("sedes.json", $sedes);
                echo json_encode(["status" => "success", "sede" => $sede]);
                break;
            }
        }
        break;

    case "eliminar":
        $sedes = array_filter($sedes, fn($sede) => $sede["db_COD_SEDE"] !== $data["codigo"]);
        saveJSON("sedes.json", array_values($sedes));
        echo json_encode(["status" => "success", "message" => "Sede eliminada"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        break;
}

exit();

?>
