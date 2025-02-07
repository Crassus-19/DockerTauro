<?php
require "../db.php";

$sql = "SELECT * FROM usuarios";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">Gestión de Usuarios</h2>
        <a href="agregar.php" class="btn btn-primary mb-3">Agregar Usuario</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Apodo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><?= $row["usuario"] ?></td>
                        <td><?= $row["nombre"] ?></td>
                        <td><?= $row["apellido"] ?></td>
                        <td><?= $row["apodo"] ?></td>
                        <td><?= $row["telefono"] ?></td>
                        <td><?= $row["rol"] ?></td>
                        <td>
                            <a href="editar.php?id=<?= $row["id"] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
