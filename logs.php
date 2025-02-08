<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");

function registrar_log($tabla, $accion, $registro_id, $descripcion) {
    global $connection;

    $sql = "INSERT INTO logs (tabla, accion, registro_id, descripcion, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssis", $tabla, $accion, $registro_id, $descripcion);
    $stmt->execute();
}
?>
