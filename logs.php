<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");

function registrar_log($tabla, $accion, $registro_id, $descripcion) {
    global $connection;

    $sql = "INSERT INTO logs (Tabla, Operacion, Registro_ID, Detalles) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $connection->error);
    }

    // 🔹 Corrección: Usamos los parámetros correctos
    $stmt->bind_param("ssis", $tabla, $accion, $registro_id, $descripcion);

    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    // 🔹 Cerrar la consulta para evitar problemas de memoria
    $stmt->close();
}
?>
