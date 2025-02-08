<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/logs.php"); // Asegúrate de que logs.php existe

ob_start(); // 🔹 Inicia el buffer de salida para evitar problemas con header()

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $registro_id = $_GET["id"];

    // ✅ Insertar en la tabla ordenes
    $sql_insert = "INSERT INTO ordenes (Unidad_ID, Descripcion, Estado) 
                   SELECT Unidad_ID, Comentarios, 'Pendiente' FROM registros WHERE ID = ?";
    $stmt_insert = $connection->prepare($sql_insert);
    $stmt_insert->bind_param("i", $registro_id);

    if ($stmt_insert->execute()) {
        // ✅ Registrar en logs
        registrar_log("ordenes", "INSERT", $registro_id, "Se agregó una nueva orden desde registros");

        // ✅ Actualizar el estado en la tabla registros
        $sql_update = "UPDATE registros SET Estado = 'Aceptada' WHERE ID = ?";
        $stmt_update = $connection->prepare($sql_update);
        $stmt_update->bind_param("i", $registro_id);
        $stmt_update->execute();

        // ✅ Registrar en logs
        registrar_log("registros", "UPDATE", $registro_id, "Estado cambiado a 'Aceptada'");
    }

    // ✅ Redirigir solo después de que se haya ejecutado todo
    ob_end_clean(); // 🔹 Limpia el buffer de salida antes de redirigir
    header("Location: ordenes_trabajo.php");
    exit();
}

ob_end_flush(); // 🔹 Limpia y envía cualquier salida restante
?>
