<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


// Obtener lista de mecánicos
$sql = "SELECT * FROM mecanicos ORDER BY Nombre ASC";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mecánicos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lista de Mecánicos</h2>

        <!-- Botón para agregar un nuevo mecánico -->
        <a href="mecanicosU.php" class="btn btn-primary mb-3">Agregar Mecánico</a>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Área</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["ID"]; ?></td>
                        <td><?php echo $row["Nombre"]; ?></td>
                        <td><?php echo $row["Area"]; ?></td>
                        <td><?php echo $row["Estatus"]; ?></td>
                        <td>
                            <a href="mecanicosE.php?id=<?php echo $row["ID"]; ?>" class="btn btn-warning btn-sm">Editar</a>
                            
                            <!-- Botón de eliminar, desactivado -->
                            <!-- <a href="mecanicosD.php?id=<?php echo $row["ID"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar?')">Eliminar</a> -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
