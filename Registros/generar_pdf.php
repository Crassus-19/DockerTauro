<?php
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

// ✅ Verificar si la carpeta existe y tiene permisos
if (!is_dir($dirPath)) {
    if (!mkdir($dirPath, 0777, true)) {
        die("Error: No se pudo crear la carpeta '$dirPath'. Verifica los permisos.");
    }
}

// ✅ Verificar si la carpeta es escribible
if (!is_writable($dirPath)) {
    die("Error: PHP no tiene permisos de escritura en '$dirPath'. Usa 'chmod -R 777 $dirPath' dentro del contenedor.");
}

// ✅ Guardar el archivo JSON
$filePath = "/var/www/html/Registros/temp_registro.json";
file_put_contents($filePath, $data);

if (!file_exists($filePath)) {
    die("Error: No se pudo crear el archivo JSON.");
}


// ✅ Verificar si el archivo realmente existe
if (!file_exists($filePath)) {
    die("Error: No se creó el archivo JSON correctamente.");
}

// ✅ Asegurar que PHP use la ruta correcta de Node.js dentro del contenedor
$nodePath = "/usr/bin/node"; // Cambia esto si Node.js está en otro lugar dentro del contenedor

// ✅ EJECUTAR EL SCRIPT Y CAPTURAR ERRORES
$command = "$nodePath /var/www/html/Registros/generar_pdf.js $filePath 2>&1";
exec($command, $output, $return_var);

// ✅ SI HAY ERROR, MOSTRARLO
if ($return_var !== 0) {
    echo "<pre>Error al ejecutar Node.js:\n";
    print_r($output);
    echo "</pre>";
    die();
}

// ✅ ENVIAR PDF AL USUARIO
$pdfFileName = "registro_" . $id . ".pdf";
$pdfFilePath = "/var/www/html/Registros/registro.pdf";

// ✅ Verificar que el PDF fue generado antes de enviarlo
if (!file_exists($pdfFilePath)) {
    die("Error: No se generó el archivo PDF correctamente.");
}

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"$pdfFileName\"");
readfile($pdfFilePath);

// ✅ LIMPIAR ARCHIVOS TEMPORALES
unlink($filePath);
unlink($pdfFilePath);
?>
