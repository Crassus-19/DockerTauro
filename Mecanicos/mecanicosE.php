<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


$id = $_GET["id"] ?? null;
$errorMessage = "";
$successMessage = "";

if (!$id) {
    header("Location: mecanicosV.php");
    exit;
}

// Obtener datos del mecánico
$sql = "SELECT * FROM mecanicos WHERE ID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$mecanico = $result->fetch_assoc();
$stmt->close();

if (!$mecanico) {
    header("Location: mecanicosV.php");
    exit;
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $area = $_POST["area"];
    $estatus = $_POST["estatus"];

    if (empty($nombre) || empty($fecha_nacimiento) || empty($area) || empty($estatus)) {
        $errorMessage = "Todos los campos son obligatorios.";
    } else {
        $sql = "UPDATE mecanicos SET Nombre=?, Area=?, Estatus=? WHERE ID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $fecha_nacimiento, $area, $estatus, $id);

        if ($stmt->execute()) {
            $successMessage = "Mecánico actualizado correctamente.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mecánico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Editar Mecánico</h2>

        <?php if (!empty($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
        <?php if (!empty($successMessage)) echo "<div class='alert alert-success'>$successMessage</div>"; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $mecanico['Nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Área</label>
                <select class="form-select" name="area" required>
                    <option value="Llantero" <?php echo ($mecanico['Area'] == 'Llantero') ? 'selected' : ''; ?>>Llantero</option>
                    <option value="Mecanico Diesel" <?php echo ($mecanico['Area'] == 'Mecanico Diesel') ? 'selected' : ''; ?>>Mecánico Diesel</option>
                    <option value="Mecanico Gasolina" <?php echo ($mecanico['Area'] == 'Mecanico Gasolina') ? 'selected' : ''; ?>>Mecánico Gasolina</option>
                    <option value="Electrico" <?php echo ($mecanico['Area'] == 'Electrico') ? 'selected' : ''; ?>>Eléctrico</option>
                    <option value="Pintor" <?php echo ($mecanico['Area'] == 'Pintor') ? 'selected' : ''; ?>>Pintor</option>
                    <option value="Soldador" <?php echo ($mecanico['Area'] == 'Soldador') ? 'selected' : ''; ?>>Soldador</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estatus</label>
                <select class="form-select" name="estatus" required>
                    <option value="Activo" <?php echo ($mecanico['Estatus'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo ($mecanico['Estatus'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    <option value="Exempleado" <?php echo ($mecanico['Estatus'] == 'Exempleado') ? 'selected' : ''; ?>>Exempleado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="mecanicosV.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
