<?php
$servername = getenv("MYSQLHOST") ?: "viaduct.proxy.rlwy.net"; 
$username = getenv("MYSQLUSER") ?: "root";
$password = getenv("MYSQLPASSWORD") ?: "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = getenv("MYSQLDATABASE") ?: "railway";
$port = getenv("MYSQLPORT") ?: 26846;

$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

echo "✅ Conexión exitosa a MySQL!";
?>