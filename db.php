<?php
$servername = getenv("MYSQLHOST") ?: "mysql-production-5344.up.railway.app";
$username = getenv("MYSQLUSER") ?: "root";
$password = getenv("MYSQLPASSWORD") ?: "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = getenv("MYSQLDATABASE") ?: "railway";
$port = getenv("MYSQLPORT") ?: 3306;

// Conexión a MySQL
$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

echo "✅ Conexión exitosa a MySQL en Railway!";

?>
