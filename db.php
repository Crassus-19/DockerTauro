<?php
$servername = "mysql-production-5344.up.railway.app"; // ⚠️ Host público de Railway
$username = "root";
$password = "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = "railway";
$port = 3306; // ⚠️ Puerto correcto

$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

echo "✅ Conexión exitosa a MySQL en Railway!";

?>
