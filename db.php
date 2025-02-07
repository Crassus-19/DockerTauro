<?php
$servername = "db";
$username = "user";
$password = "password";
$database = "vehiculos";
$port = 3306; // 🔹 Esto sigue en 3306 porque es dentro del contenedor

$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

?>
