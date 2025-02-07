<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


    // Proteger contra inyección SQL
    $id = $connection->real_escape_string($id);

    // Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM vehiculos WHERE ID = $id";
    $result = $connection->query($sql);

    if (!$result) {
        die("Error al eliminar el registro: " . $connection->error);
    }

    // Cerrar conexión
    $connection->close();
}

// Redirigir de vuelta a la página principal
header("location: index.php");
exit;
?>
