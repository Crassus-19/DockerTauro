<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Agregar Usuario</h2>
        <form action="procesar.php" method="POST">
            <input type="hidden" name="accion" value="agregar">
            <div class="mb-3">
                <label>Usuario:</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Apellido:</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Apodo:</label>
                <input type="text" name="apodo" class="form-control">
            </div>
            <div class="mb-3">
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="mb-3">
                <label>Contraseña:</label>
                <input type="password" name="contraseña" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Rol:</label>
                <select name="rol" class="form-control">
                    <option value="usuario">Usuario</option>
                    <option value="admin">Admin</option>
                    <option value="mecanico">Mecánico</option>
                    <option value="almacen">Almacén</option>
                    <option value="chofer">Chofer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
