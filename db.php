<?php
// ✅ Configurar la zona horaria de PHP
date_default_timezone_set('America/Mexico_City'); 

// ✅ Obtener credenciales de Railway
$servername = getenv("MYSQLHOST") ?: "viaduct.proxy.rlwy.net"; 
$username = getenv("MYSQLUSER") ?: "root";
$password = getenv("MYSQLPASSWORD") ?: "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = getenv("MYSQLDATABASE") ?: "railway";
$port = getenv("MYSQLPORT") ?: 26846;

// ✅ Conectar a MySQL
$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

// ✅ Forzar que MySQL use la zona horaria correcta
$connection->query("SET time_zone = '-06:00'"); // UTC-6 para CDMX

?>
