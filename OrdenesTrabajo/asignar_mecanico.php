<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: ordenes_trabajo.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mecanico_id = $_POST["mecanico_id"];
    $prioridad = $_POST["prioridad"];
    $sql = "UPDATE ordenes SET Mecanico_ID = ?, Prioridad = ?, Estado = 'Activa' WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isi", $mecanico_id, $prioridad, $id);
    $stmt->execute();
    header("Location: ordenes_trabajo.php");
    exit;
}

$sql_mecanicos = "SELECT * FROM mecanicos WHERE Estatus = 'Activo'";
$result_mecanicos = $connection->query($sql_mecanicos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Mecánico y Prioridad</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Asignar Mecánico y Prioridad</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label"><strong>Mecánico:</strong></label>
                        <select name="mecanico_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione un mecánico</option>
                            <?php while ($row = $result_mecanicos->fetch_assoc()): ?>
                                <option value="<?= $row["ID"] ?>"><?= $row["Nombre"] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Prioridad:</strong></label>
                        <select name="prioridad" class="form-select" required>
                            <option value="" disabled selected>Seleccione prioridad</option>
                            <option value="ALTA" class="text-danger">🔴 PRIORIDAD ALTA</option>
                            <option value="MEDIA" class="text-warning">🟡 PRIORIDAD MEDIA</option>
                            <option value="BAJA" class="text-success">🟢 PRIORIDAD BAJA</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="ordenes_trabajo.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Asignar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
