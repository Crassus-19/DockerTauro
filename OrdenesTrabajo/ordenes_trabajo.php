<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


// Lista de columnas permitidas para ordenamiento
$valid_columns = ['Unidad', 'Descripcion', 'Asignacion', 'Estado', 'Mecanico', 'Prioridad', 'Fecha'];

// Determinar la columna y el orden a utilizar
$column = isset($_GET['column']) && in_array($_GET['column'], $valid_columns) ? $_GET['column'] : 'Fecha';
$order = isset($_GET['order']) && $_GET['order'] == 'ASC' ? 'ASC' : 'DESC';

// Alternar orden para la siguiente consulta
$new_order = $order === 'ASC' ? 'DESC' : 'ASC';

// 🟢 Obtener órdenes nuevas (excluyendo rechazadas)
$sql_nuevas = "SELECT r.ID, v.Nombre AS Unidad, r.Cantidad, r.Tipo_Orden, r.Comentarios, 
                      r.Reportado_Por, IFNULL(v.Asignacion, 'Sin asignar') AS Asignacion, r.Fecha_Registro 
               FROM registros r
               INNER JOIN vehiculos v ON r.Unidad_ID = v.ID
               WHERE r.Estado = 'Pendiente'
               AND r.ID NOT IN (SELECT Unidad_ID FROM ordenes WHERE Estado != 'Terminada')";
$result_nuevas = $connection->query($sql_nuevas);



// 🟢 Obtener órdenes activas con orden dinámico
$sql_ordenes = "SELECT o.ID, v.Nombre AS Unidad, o.Descripcion, o.Estado, o.Fecha, 
                IFNULL(v.Asignacion, 'Sin asignar') AS Asignacion,
                IFNULL(m.Nombre, 'No asignado') AS Mecanico, 
                IFNULL(o.Prioridad, 'Sin asignar') AS Prioridad
                FROM ordenes o
                INNER JOIN vehiculos v ON o.Unidad_ID = v.ID 
                LEFT JOIN mecanicos m ON o.Mecanico_ID = m.ID
                WHERE o.Estado = 'Pendiente' OR o.Estado = 'Activa'
                ORDER BY $column $order";
$result_ordenes = $connection->query($sql_ordenes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de Trabajo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        .prioridad-alta { background-color: #ff6b6b !important; color: white; }  /* Rojo */
        .prioridad-media { background-color: #feca57 !important; color: black; } /* Amarillo */
        .prioridad-baja { background-color: #1dd1a1 !important; color: white; }  /* Verde */
        .sin-asignacion { background-color: #cce5ff !important; }  /* Azul claro */

        /* Estilo para los enlaces en la cabecera de la tabla */
        th a {
            color: white;
            text-decoration: none;
        }
        th a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <!-- Sección de órdenes nuevas -->
        <h3 class="text-success">Nuevas Órdenes</h3>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Unidad</th>
                    <th>KM / HORAS</th>
                    <th>Tipo de Orden</th>
                    <th>Comentarios</th>
                    <th>Reportado Por</th>
                    <th>Asignación</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_nuevas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["Unidad"]; ?></td>
                        <td><?php echo number_format($row["Cantidad"], 2, '.', ','); ?></td>
                        <td><?php echo $row["Tipo_Orden"]; ?></td>
                        <td><?php echo $row["Comentarios"]; ?></td>
                        <td><?php echo $row["Reportado_Por"]; ?></td>
                        <td><?php echo $row["Asignacion"]; ?></td>
                        <td><?php echo $row["Fecha_Registro"]; ?></td>
                        <td>
                            <a href="aceptar_orden.php?id=<?php echo $row['ID']; ?>" class="btn btn-success btn-sm">Aceptar</a>
                            <a href="rechazar_orden.php?id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm">Rechazar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Sección de órdenes activas -->
        <h3 class="text-warning">Órdenes Activas</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th><a href="?column=Unidad&order=<?= $new_order ?>" class="text-white">Unidad</a></th>
                    <th><a href="?column=Descripcion&order=<?= $new_order ?>" class="text-white">Descripción</a></th>
                    <th><a href="?column=Asignacion&order=<?= $new_order ?>" class="text-white">Asignación</a></th>
                    <th><a href="?column=Estado&order=<?= $new_order ?>" class="text-white">Estado</a></th>
                    <th><a href="?column=Mecanico&order=<?= $new_order ?>" class="text-white">Mecánico Asignado</a></th>
                    <th><a href="?column=Prioridad&order=<?= $new_order ?>" class="text-white">Prioridad</a></th>
                    <th><a href="?column=Fecha&order=<?= $new_order ?>" class="text-white">Fecha</a></th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_ordenes->fetch_assoc()): ?>
                    <?php
                    $prioridadClase = match ($row["Prioridad"]) {
                        "ALTA" => "prioridad-alta",
                        "MEDIA" => "prioridad-media",
                        "BAJA" => "prioridad-baja",
                        default => ""
                    };

                    // Si no tiene asignación, mecánico o prioridad, resaltar en azul claro
                    $filaClase = ($row["Asignacion"] == "Sin asignar" || $row["Mecanico"] == "No asignado" || $row["Prioridad"] == "Sin asignar") ? "sin-asignacion" : "";
                    ?>
                    <tr class="<?= $filaClase; ?>">
                        <td><?= $row["Unidad"]; ?></td>
                        <td><?= $row["Descripcion"]; ?></td>
                        <td><?= $row["Asignacion"]; ?></td>
                        <td><?= $row["Estado"]; ?></td>
                        <td><?= $row["Mecanico"]; ?></td>
                        <td class="<?= $prioridadClase; ?>"><?= $row["Prioridad"]; ?></td>
                        <td><?= $row["Fecha"]; ?></td>
                        <td>
                            <a href="asignar_mecanico.php?id=<?= $row['ID']; ?>" class="btn btn-primary btn-sm">Asignar Mecánico y Prioridad</a>
                            <?php if ($row["Mecanico"] != "No asignado" && $row["Prioridad"] != "Sin asignar"): ?>
                                <a href="terminar_orden.php?id=<?= $row['ID']; ?>" class="btn btn-success btn-sm">Terminar Orden</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
