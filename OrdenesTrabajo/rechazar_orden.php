<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/logs.php"); // ✅ Asegurar que logs.php esté incluido

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $registro_id = $_GET["id"];

    // ✅ Cambiar el estado a "Rechazada" en la tabla registros
    $sql = "UPDATE registros SET Estado = 'Rechazada' WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $registro_id);

    if ($stmt->execute()) {
        // ✅ Registrar en logs: Cambio de estado en "registros"
        registrar_log("registros", "UPDATE", $registro_id, "Estado cambiado a 'Rechazada'");
    }

    // ✅ Redirigir de vuelta a la lista de órdenes de trabajo
    header("Location: ordenes_trabajo.php");
    exit();
} else {
    die("❌ Error: ID inválido o no proporcionado.");
}
?>
