<?php
require_once "/Applications/MAMP/htdocs/DEMOS/db.php";  // Include centralized database connection

$sql = "SELECT * FROM logs ORDER BY Fecha DESC";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cambios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>




    <div class="container my-5">
        <h3 class="text-primary">Registro de Cambios</h3>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tabla</th>
                    <th>Operación</th>
                    <th>ID Registro</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["ID"]; ?></td>
                        <td><?php echo $row["Tabla"]; ?></td>
                        <td><?php echo $row["Operacion"]; ?></td>
                        <td><?php echo $row["Registro_ID"]; ?></td>
                        <td><?php echo $row["Usuario"]; ?></td>
                        <td><?php echo $row["Fecha"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
