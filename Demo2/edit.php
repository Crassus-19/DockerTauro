<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        header("location: index.php");
        exit;
    }

    $id = $_GET["id"];

    // Obtener datos del vehículo
    $sql = "SELECT * FROM vehiculos WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: index.php");
        exit;
    }

    // Asignar valores obtenidos
    $nombre = $row["Nombre"];
    $numero = $row["Numero"];
    $tipo = $row["Tipo"];
    $marca = $row["Marca"];
    $modelo = $row["Modelo"];
    $ano = $row["Ano"];
    $serie = $row["Serie"];
    $placas = $row["Placas"] ?? "";
    $motor = $row["Motor"];
    $combustible = $row["Combustible"];
    $uso = $row["Uso"];
    $cantidad = $row["Cantidad"];
    $estado = $row["Estado"];
    $descripcion = $row["Decripcion"]; // ⚠️ Se mantiene "Decripcion" como está en la base de datos
    $asignacion = $row["Asignacion"];
} else {
    // POST: Guardar cambios
    $id = $_POST["id"] ?? "";
    $nombre = trim($_POST["nombre"] ?? "");
    $numero = trim($_POST["numero"] ?? "");
    $tipo = trim($_POST["tipo"] ?? "");
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = trim($_POST["ano"] ?? "");
    $serie = trim($_POST["serie"] ?? "");
    $placas = trim($_POST["placas"] ?? ""); // Puede estar vacío
    $motor = trim($_POST["motor"] ?? "");
    $combustible = trim($_POST["combustible"] ?? "");
    $uso = trim($_POST["uso"] ?? "");
    $cantidad = trim($_POST["cantidad"] ?? "");
    $estado = trim($_POST["estado"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $asignacion = trim($_POST["asignacion"] ?? "");

    if (empty($id) || empty($nombre) || empty($numero) || empty($tipo) || empty($marca) || empty($modelo) || empty($ano) || empty($serie) || empty($motor) || empty($combustible) || empty($uso) || empty($cantidad) || empty($estado) || empty($descripcion) || empty($asignacion)) {
        $errorMessage = "Todos los campos son obligatorios (excepto Placas).";
    } else {
        $sql = "UPDATE vehiculos 
                SET Nombre=?, Numero=?, Tipo=?, Marca=?, Modelo=?, Ano=?, Serie=?, Placas=?, Motor=?, Combustible=?, Uso=?, Cantidad=?, Estado=?, Decripcion=?, Asignacion=? 
                WHERE ID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssssssssssssi", $nombre, $numero, $tipo, $marca, $modelo, $ano, $serie, $placas, $motor, $combustible, $uso, $cantidad, $estado, $descripcion, $asignacion, $id);

        if ($stmt->execute()) {
            $successMessage = "Vehículo actualizado correctamente";
            header("location: index.php");
            exit;
        } else {
            $errorMessage = "Error en la consulta: " . $connection->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vehículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Editar Vehículo</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><?php echo $errorMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required></div>
            <div class="mb-3"><label class="form-label">Número Económico</label><input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required></div>

            <div class="mb-3">
                <label class="form-label">Tipo de Unidad</label>
                <select class="form-select" name="tipo" required>
                    <option value="Tractocamion" <?php echo ($tipo == "Tractocamion") ? "selected" : ""; ?>>Tractocamión</option>
                    <option value="Maquinaria_Construccion" <?php echo ($tipo == "Maquinaria_Construccion") ? "selected" : ""; ?>>Maquinaria de Construcción</option>
                    <option value="Camion_Reparto" <?php echo ($tipo == "Camion_Reparto") ? "selected" : ""; ?>>Camión de Reparto</option>
                    <option value="Automovil" <?php echo ($tipo == "Automovil") ? "selected" : ""; ?>>Automóvil</option>
                    <option value="Montacargas" <?php echo ($tipo == "Montacargas") ? "selected" : ""; ?>>Montacargas</option>
                    <option value="Maquinaria_agricola" <?php echo ($tipo == "Maquinaria_agricola") ? "selected" : ""; ?>>Maquinaria Agrícola</option>
                    <option value="Planta" <?php echo ($tipo == "Planta") ? "selected" : ""; ?>>Plantas de luz</option>
                    <option value="Otro" <?php echo ($tipo == "Otro") ? "selected" : ""; ?>>Otro</option>
                </select>
            </div>


            <div class="mb-3"><label class="form-label">Marca</label><input type="text" class="form-control" name="marca" value="<?php echo htmlspecialchars($marca); ?>" required></div>
            <div class="mb-3"><label class="form-label">Modelo</label><input type="text" class="form-control" name="modelo" value="<?php echo htmlspecialchars($modelo); ?>" required></div>
            <div class="mb-3"><label class="form-label">Año</label><input type="number" class="form-control" name="ano" value="<?php echo htmlspecialchars($ano); ?>" required></div>
            <div class="mb-3"><label class="form-label">Número de Serie</label><input type="text" class="form-control" name="serie" value="<?php echo htmlspecialchars($serie); ?>" required></div>
            <div class="mb-3"><label class="form-label">Placas (Opcional)</label><input type="text" class="form-control" name="placas" value="<?php echo htmlspecialchars($placas); ?>"></div>
            <div class="mb-3"><label class="form-label">Número de Motor</label><input type="text" class="form-control" name="motor" value="<?php echo htmlspecialchars($motor); ?>" required></div>

            <div class="mb-3">
                <label class="form-label">Tipo de Combustible</label>
                <select class="form-select" name="combustible" required>
                    <option value="GASOLINA" <?php echo ($combustible == "GASOLINA") ? "selected" : ""; ?>>Gasolina</option>
                    <option value="DIESEL" <?php echo ($combustible == "DIESEL") ? "selected" : ""; ?>>Diesel</option>
                    <option value="ELECTRICO" <?php echo ($combustible == "ELECTRICO") ? "selected" : ""; ?>>Eléctrico</option>
                    <option value="HIBRIDO" <?php echo ($combustible == "HIBRIDO") ? "selected" : ""; ?>>Híbrido</option>
                    <option value="GAS_LP" <?php echo ($combustible == "GAS_LP") ? "selected" : ""; ?>>Gas LP</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Uso</label>
                <select class="form-select" name="uso" required>
                    <option value="KILOMETROS" <?php echo ($uso == "KILOMETROS") ? "selected" : ""; ?>>Kilómetros</option>
                    <option value="HORAS" <?php echo ($uso == "HORAS") ? "selected" : ""; ?>>Horas</option>
                </select>
            </div>
           
            <div class="mb-3"><label class="form-label">Cantidad</label><input type="text" class="form-control" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required></div>
            
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado" required>
                    <option value="ACTIVO" <?php echo ($estado == "ACTIVO") ? "selected" : ""; ?>>Activo</option>
                    <option value="INACTIVO" <?php echo ($estado == "INACTIVO") ? "selected" : ""; ?>>Inactivo</option>
                    <option value="MANTENIMIENTO" <?php echo ($estado == "MANTENIMIENTO") ? "selected" : ""; ?>>En Mantenimiento</option>
                </select>
            </div>      

            <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea></div>
            
            <div class="mb-3">
                <label class="form-label">Asignación</label>
                <select class="form-select" name="asignacion" required>
                    <option value="fletes_tauro" <?php echo ($asignacion == "fletes_tauro") ? "selected" : ""; ?>>Fletes Tauro</option>
                    <option value="tauro_logistica" <?php echo ($asignacion == "tauro_logistica") ? "selected" : ""; ?>>Tauro Logística</option>
                    <option value="rancho_san_antonio" <?php echo ($asignacion == "rancho_san_antonio") ? "selected" : ""; ?>>Rancho San Antonio</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
