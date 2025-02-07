<?php
$usePublic = getenv("RAILWAY_ENVIRONMENT") ? false : true;

$servername = $usePublic ? "viaduct.proxy.rlwy.net" : "mysql-production-5344.up.railway.app";
$port = $usePublic ? 26846 : 3306;
$username = "root";
$password = "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = "railway";

// Conexión a MySQL
$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}

echo "✅ Conexión exitosa a MySQL!";

?>
