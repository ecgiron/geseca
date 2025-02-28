<?php

include_once "config.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$accion = $data["accion"] ?? "";

$canales = loadJSON("canales.json");

switch ($accion) {
    case "listar":
        echo json_encode(["status" => "success", "canales" => $canales]);
        break;

    case "crear":
        $nuevo_canal = [
            "db_COD_CANAL" => uniqid("canal_"),
            "db_COD_SEDE" => $data["codigo_sede"],
            "db_NOMBRE_CANAL" => $data["nombre"],
            "db_ANCHO_BANDA" => $data["ancho_banda"],
            "db_ESTADO" => $data["estado"]
        ];
        $canales[] = $nuevo_canal;
        saveJSON("canales.json", $canales);
        echo json_encode(["status" => "success", "canal" => $nuevo_canal]);
        break;

    case "editar":
        foreach ($canales as &$canal) {
            if ($canal["db_COD_CANAL"] === $data["codigo"]) {
                $canal["db_NOMBRE_CANAL"] = $data["nombre"];
                $canal["db_ANCHO_BANDA"] = $data["ancho_banda"];
                $canal["db_ESTADO"] = $data["estado"];
                saveJSON("canales.json", $canales);
                echo json_encode(["status" => "success", "canal" => $canal]);
                break;
            }
        }
        break;

    case "eliminar":
        $canales = array_filter($canales, fn($canal) => $canal["db_COD_CANAL"] !== $data["codigo"]);
        saveJSON("canales.json", array_values($canales));
        echo json_encode(["status" => "success", "message" => "Canal eliminado"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        break;
}

exit();

?>