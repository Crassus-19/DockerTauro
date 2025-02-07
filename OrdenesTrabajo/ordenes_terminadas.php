<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


// 🟢 Consulta para obtener órdenes terminadas con datos de unidad, mecánico, materiales y tiempo de trabajo
$sql = "SELECT o.ID, v.Nombre AS Unidad, m.Nombre AS Mecanico, 
               o.Detalle_Trabajo, o.Materiales_Usados, o.Tiempo_Trabajo, o.Fecha
        FROM ordenes o
        LEFT JOIN vehiculos v ON o.Unidad_ID = v.ID
        LEFT JOIN mecanicos m ON o.Mecanico_ID = m.ID
        WHERE o.Estado = 'Terminada'
        ORDER BY o.Fecha DESC";

$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes Terminadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4 text-center text-success">Órdenes de Trabajo Terminadas</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Unidad: <?= $row["Unidad"] ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Mecánico Asignado:</strong> 
                                    <span class="badge bg-primary"><?= $row["Mecanico"] ?? "No asignado" ?></span>
                                </p>
                                <p><strong>Detalles del Trabajo:</strong> <?= nl2br(htmlspecialchars($row["Detalle_Trabajo"])) ?></p>
                                <p><strong>Materiales Utilizados:</strong> <?= nl2br(htmlspecialchars($row["Materiales_Usados"])) ?></p>
                                <p><strong>Tiempo de Trabajo:</strong> <?= number_format($row["Tiempo_Trabajo"], 1) ?> horas</p>
                            </div>
                            <div class="card-footer text-muted">
                                <small>Fecha de Terminación: <?= $row["Fecha"] ?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No hay órdenes terminadas aún.</p>
        <?php endif; ?>
    </div>
</body>
</html>
