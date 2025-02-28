<!-- config.php -->
<?php

// Configuración global del sistema
header("Content-Type: application/json");

define("DATA_PATH", __DIR__ . "/../data/");

function loadJSON($filename) {
    $filePath = DATA_PATH . $filename;
    if (!file_exists($filePath)) {
        return [];
    }
    return json_decode(file_get_contents($filePath), true);
}

function saveJSON($filename, $data) {
    $filePath = DATA_PATH . $filename;
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

?>
