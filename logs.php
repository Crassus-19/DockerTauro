<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");

function registrar_log($tabla, $operacion, $registro_id, $detalles) {
    global $connection;

    $sql = "INSERT INTO logs (Tabla, Operacion, Registro_ID, Detalles) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("❌ Error en la preparación de la consulta: " . $connection->error);
    }

    $stmt->bind_param("ssis", $tabla, $operacion, $registro_id, $detalles);

    if (!$stmt->execute()) {
        die("❌ Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->close();
}
?>
