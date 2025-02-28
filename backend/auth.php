<?php

session_start();
include_once "config.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$accion = $data["accion"] ?? "";

$usuarios = loadJSON("usuarios.json");

switch ($accion) {
    case "login":
        $email = $data["email"] ?? "";
        $password = $data["password"] ?? "";
        
        foreach ($usuarios as $usuario) {
            if ($usuario["db_EMAIL"] === $email && password_verify($password, $usuario["db_PASSWORD"])) {
                $_SESSION["usuario"] = [
                    "nombre" => $usuario["db_NOMBRE"],
                    "email" => $usuario["db_EMAIL"],
                    "rol" => $usuario["db_ROL"]
                ];
                echo json_encode(["status" => "success", "usuario" => $_SESSION["usuario"]]);
                exit();
            }
        }
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
        exit();
    
    case "logout":
        session_destroy();
        echo json_encode(["status" => "success", "message" => "Sesión cerrada"]);
        exit();
    
    case "verificar":
        if (isset($_SESSION["usuario"])) {
            echo json_encode(["status" => "success", "usuario" => $_SESSION["usuario"]]);
        } else {
            echo json_encode(["status" => "error", "message" => "No hay sesión activa"]);
        }
        exit();
    
    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        exit();
}

?>