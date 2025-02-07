<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


// Conectar a la base de datos de vehículos
$vehiculos_db = new mysqli($servername, $username, $password, "vehiculos");

// Obtener unidades desde la base de datos vehiculos
$sql = "SELECT ID, Nombre, Numero FROM vehiculos ORDER BY Nombre ASC";
$result = $vehiculos_db->query($sql);

// Manejar el formulario
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unidad_id = $_POST["unidad_id"];
    $cantidad = $_POST["cantidad"];
    $tipo_orden = $_POST["tipo_orden"];
    $comentarios = trim($_POST["comentarios"]);
    $reportado_por = trim($_POST["reportado_por"]);

    if (empty($unidad_id) || empty($cantidad) || empty($tipo_orden) || empty($comentarios) || empty($reportado_por)) {
        $errorMessage = "Todos los campos son obligatorios.";
    } else {
        // Insertar el nuevo registro en la tabla registros
        $stmt = $connection->prepare("INSERT INTO registros (Unidad_ID, Cantidad, Tipo_Orden, Comentarios, Reportado_Por) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idsss", $unidad_id, $cantidad, $tipo_orden, $comentarios, $reportado_por);
        
        if ($stmt->execute()) {
            // Actualizar la cantidad en la tabla vehiculos con el nuevo valor ingresado
            $update_sql = "UPDATE vehiculos SET Cantidad = ? WHERE ID = ?";
            $update_stmt = $vehiculos_db->prepare($update_sql);
            $update_stmt->bind_param("di", $cantidad, $unidad_id);
            $update_stmt->execute();

            $successMessage = "Orden registrada exitosamente.";
        } else {
            $errorMessage = "Error en la consulta: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Orden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Registrar Orden de Mantenimiento/Reparación</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-danger'><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class='alert alert-success'><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form method="post">
            <!-- Selección de Unidad -->
            <div class="mb-3">
                <label class="form-label">Unidad</label>
                <select class="form-select" name="unidad_id" required>
                    <option value="">-- Selecciona una unidad --</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row["ID"]; ?>">
                            <?php echo $row["Nombre"] . " - " . $row["Numero"]; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Cantidad -->
            <div class="mb-3">
                <label class="form-label">Cantidad a actualizar</label>
                <input type="number" class="form-control" name="cantidad" step="0.01" min="0.1" required placeholder="Ejemplo: 1000">
            </div>

            <!-- Tipo de Orden -->
            <div class="mb-3">
                <label class="form-label">Tipo de Orden</label>
                <select class="form-select" name="tipo_orden" required>
                    <option value="">-- Selecciona un tipo --</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Reparacion">Reparación</option>
                </select>
            </div>

            <!-- Comentarios -->
            <div class="mb-3">
                <label class="form-label">Comentarios</label>
                <textarea class="form-control" name="comentarios" rows="3" required placeholder="Ejemplo: Cambio de aceite y revisión de frenos."></textarea>
            </div>

            <!-- Reportado por -->
            <div class="mb-3">
                <label class="form-label">Reportado por</label>
                <input type="text" class="form-control" name="reportado_por" required placeholder="Ejemplo: Juan Pérez">
            </div>

            <!-- Botones -->
            <div class="row mb-3">
                <div class="col-sm-6 d-grid">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
                <div class="col-sm-6 d-grid">
                    <a class="btn btn-outline-secondary" href="registrosV.php" role="button">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
