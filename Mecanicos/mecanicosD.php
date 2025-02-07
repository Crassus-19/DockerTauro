<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


$id = $_GET["id"] ?? null;

if ($id) {
    $sql = "DELETE FROM mecanicos WHERE ID=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: mecanicosV.php");
        exit;
    } else {
        die("Error al eliminar: " . $stmt->error);
    }
}
?>
