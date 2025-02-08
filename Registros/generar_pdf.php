<?php
ob_start(); // 🔹 Iniciar buffer de salida para evitar errores de header
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");

if (!isset($_GET['id'])) {
    die("ID de registro no proporcionado.");
}

$id = intval($_GET['id']);

$sql = "SELECT registros.ID, vehiculos.Nombre AS Unidad, vehiculos.Uso, registros.Cantidad, 
               registros.Tipo_Orden, registros.Comentarios, registros.Reportado_Por, vehiculos.Asignacion, registros.Fecha_Registro
        FROM registros 
        INNER JOIN vehiculos ON registros.Unidad_ID = vehiculos.ID 
        WHERE registros.ID = $id";

$result = $connection->query($sql);

if ($result->num_rows == 0) {
    die("Registro no encontrado.");
}

$row = $result->fetch_assoc();
$data = json_encode($row, JSON_PRETTY_PRINT);

// ✅ Ruta corregida dentro del contenedor
$dirPath = "/var/www/html/Registros/";

if (!is_dir($dirPath)) {
    mkdir($dirPath, 0777, true);
}

$filePath = "/var/www/html/Registros/temp_registro.json";
file_put_contents($filePath, $data);

if (!file_exists($filePath)) {
    die("Error: No se pudo crear el archivo JSON.");
}

$nodePath = "/usr/bin/node"; 

$command = "$nodePath /var/www/html/Registros/generar_pdf.js $filePath 2>&1";
exec($command, $output, $return_var);

if ($return_var !== 0) {
    ob_end_clean(); // 🔹 Limpiar buffer antes de mostrar errores
    echo "<pre>Error al ejecutar Node.js:\n";
    print_r($output);
    echo "</pre>";
    exit();
}

$pdfFileName = "registro_" . $id . ".pdf";
$pdfFilePath = "/var/www/html/Registros/registro.pdf";

if (!file_exists($pdfFilePath)) {
    die("Error: No se generó el archivo PDF correctamente.");
}

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"$pdfFileName\"");
readfile($pdfFilePath);

unlink($filePath);
unlink($pdfFilePath);

ob_end_flush(); // 🔹 Enviar salida al navegador después de los headers
?>
