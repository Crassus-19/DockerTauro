<?php
require "../db.php";

// Verificar si hay un ID en la URL
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET["id"];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Editar Usuario</h2>
        <form action="procesar.php" method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div class="mb-3">
                <label>Usuario:</label>
                <input type="text" name="usuario" class="form-control" value="<?= $usuario['usuario'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="<?= $usuario['apellido'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Apodo:</label>
                <input type="text" name="apodo" class="form-control" value="<?= $usuario['apodo'] ?>">
            </div>
            <div class="mb-3">
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?= $usuario['telefono'] ?>">
            </div>
            <div class="mb-3">
                <label>Nueva Contraseña (opcional):</label>
                <input type="password" name="contraseña" class="form-control">
            </div>
            <div class="mb-3">
                <label>Rol:</label>
                <select name="rol" class="form-control">
                    <option value="usuario" <?= $usuario['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                    <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="mecanico" <?= $usuario['rol'] == 'mecanico' ? 'selected' : '' ?>>Mecánico</option>
                    <option value="almacen" <?= $usuario['rol'] == 'almacen' ? 'selected' : '' ?>>Almacén</option>
                    <option value="chofer" <?= $usuario['rol'] == 'chofer' ? 'selected' : '' ?>>Chofer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
