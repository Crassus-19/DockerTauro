<?php
$servername = "viaduct.proxy.rlwy.net"; // 🔹 Host público de Railway
$port = 26846; // 🔹 Puerto público de Railway
$username = "root";
$password = "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = "railway";

// Intentar conexión a MySQL
$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

echo "✅ Conexión exitosa a MySQL!";
?>
