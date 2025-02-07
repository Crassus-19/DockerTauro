<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orden_id = $_POST["id"];
    $detalle = $_POST["detalle_trabajo"];
    $materiales = $_POST["materiales"];
    $tiempo = $_POST["tiempo_trabajo"]; // En horas

    $sql = "UPDATE ordenes 
            SET Estado = 'Terminada', Detalle_Trabajo = ?, Materiales_Usados = ?, Tiempo_Trabajo = ? 
            WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssdi", $detalle, $materiales, $tiempo, $orden_id);
    $stmt->execute();

    header("Location: ordenes_trabajo.php");
    exit();
}

// Obtener datos de la orden para mostrar en el formulario
$orden_id = $_GET["id"];
$sql_orden = "SELECT * FROM ordenes WHERE ID = ?";
$stmt_orden = $connection->prepare($sql_orden);
$stmt_orden->bind_param("i", $orden_id);
$stmt_orden->execute();
$result_orden = $stmt_orden->get_result();
$orden = $result_orden->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Orden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4 text-primary">Finalizar Orden</h2>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $orden_id; ?>">

            <!-- Descripción del trabajo realizado -->
            <div class="mb-3">
                <label class="form-label">Descripción del Trabajo Realizado</label>
                <textarea name="detalle_trabajo" class="form-control" rows="4" required placeholder="Ejemplo: Cambio de aceite, revisión de frenos y ajuste de válvulas."><?php echo htmlspecialchars($orden["Detalle_Trabajo"] ?? ""); ?></textarea>
            </div>

            <!-- Materiales utilizados -->
            <div class="mb-3">
                <label class="form-label">Materiales Utilizados</label>
                <textarea name="materiales" class="form-control" rows="3" required placeholder="Ejemplo: 4 litros de aceite, 2 filtros de aire."><?php echo htmlspecialchars($orden["Materiales_Usados"] ?? ""); ?></textarea>
            </div>

            <!-- Tiempo de trabajo -->
            <div class="mb-3">
                <label class="form-label">Tiempo de Trabajo (Horas)</label>
                <input type="number" name="tiempo_trabajo" class="form-control" step="0.1" min="0.1" required placeholder="Ejemplo: 3.5" value="<?php echo $orden["Tiempo_Trabajo"] ?? ""; ?>">
            </div>

            <!-- Botones -->
            <div class="row mb-3">
                <div class="col-sm-6 d-grid">
                    <button type="submit" class="btn btn-success">Finalizar Orden</button>
                </div>
                <div class="col-sm-6 d-grid">
                    <a href="ordenes_trabajo.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
