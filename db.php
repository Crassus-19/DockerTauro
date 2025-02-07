<?php
$host = "tu-host-publico.railway.app"; // Copia el host desde Railway
$user = "root";
$password = "YhlaYTTFQBaLCmuUZMROtwmcpCXrOTJg";
$database = "railway";
$port = 3306;

$connection = new mysqli($host, $user, $password, $database, $port);

if ($connection->connect_error) {
    die("❌ Error de conexión: " . $connection->connect_error);
}
echo "✅ Conexión exitosa a MySQL en Railway!";
?>
