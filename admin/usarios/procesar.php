<?php
require "../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $apodo = $_POST["apodo"];
    $telefono = $_POST["telefono"];
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT); // Encripta la contraseña
    $rol = $_POST["rol"];

    if ($accion === "agregar") {
        $sql = "INSERT INTO usuarios (usuario, nombre, apellido, apodo, telefono, contraseña, rol) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssss", $usuario, $nombre, $apellido, $apodo, $telefono, $contraseña, $rol);
        $stmt->execute();
    }
    header("Location: index.php");
    exit();
}
?>
