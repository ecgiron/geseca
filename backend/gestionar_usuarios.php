<?php

include_once "config.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$accion = $data["accion"] ?? "";

$contratos = loadJSON("historico.json");

switch ($accion) {
    case "listar":
        echo json_encode(["status" => "success", "contratos" => $contratos]);
        break;

    case "crear":
        $nuevo_contrato = [
            "db_COD_CONTRATO" => uniqid("contrato_"),
            "db_PROVEEDOR" => $data["proveedor"],
            "db_FECHA_INICIO" => $data["fecha_inicio"],
            "db_FECHA_FIN" => $data["fecha_fin"],
            "db_DETALLES" => $data["detalles"]
        ];
        $contratos[] = $nuevo_contrato;
        saveJSON("historico.json", $contratos);
        echo json_encode(["status" => "success", "contrato" => $nuevo_contrato]);
        break;

    case "editar":
        foreach ($contratos as &$contrato) {
            if ($contrato["db_COD_CONTRATO"] === $data["codigo"]) {
                $contrato["db_PROVEEDOR"] = $data["proveedor"];
                $contrato["db_FECHA_INICIO"] = $data["fecha_inicio"];
                $contrato["db_FECHA_FIN"] = $data["fecha_fin"];
                $contrato["db_DETALLES"] = $data["detalles"];
                saveJSON("historico.json", $contratos);
                echo json_encode(["status" => "success", "contrato" => $contrato]);
                break;
            }
        }
        break;

    case "eliminar":
        $contratos = array_filter($contratos, fn($contrato) => $contrato["db_COD_CONTRATO"] !== $data["codigo"]);
        saveJSON("historico.json", array_values($contratos));
        echo json_encode(["status" => "success", "message" => "Contrato eliminado"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        break;
}

exit();

?>
