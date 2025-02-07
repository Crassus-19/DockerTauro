<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $area = $_POST["area"];
    $estatus = $_POST["estatus"];

    if (empty($nombre) || empty($area) || empty($estatus)) {
        $errorMessage = "Todos los campos son obligatorios.";
    } else {
        // Corrección: solo tres valores en la consulta
        $stmt = $connection->prepare("INSERT INTO mecanicos (Nombre, Area, Estatus) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $area, $estatus); // Se corrige a tres parámetros
        
        if ($stmt->execute()) {
            $successMessage = "Mecánico agregado correctamente.";
        } else {
            $errorMessage = "Error: " . $stmt->error;
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
    <title>Agregar Mecánico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Agregar Mecánico</h2>

        <?php if (!empty($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
        <?php if (!empty($successMessage)) echo "<div class='alert alert-success'>$successMessage</div>"; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Área</label>
                <select class="form-select" name="area" required>
                    <option value="Llantero">Llantero</option>
                    <option value="Mecanico Diesel">Mecánico Diesel</option>
                    <option value="Mecanico Gasolina">Mecánico Gasolina</option>
                    <option value="Electrico">Eléctrico</option>
                    <option value="Pintor">Pintor</option>
                    <option value="Soldador">Soldador</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estatus</label>
                <select class="form-select" name="estatus" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                    <option value="Exempleado">Exempleado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="mecanicosV.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
