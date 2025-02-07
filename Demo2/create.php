<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST["nombre"]);
    $numero = trim($_POST["numero"]);
    $tipo = trim($_POST["tipo"]);
    $marca = trim($_POST["marca"]);
    $modelo = trim($_POST["modelo"]);
    $ano = trim($_POST["ano"]);
    $serie = trim($_POST["serie"]);
    $placas = trim($_POST["placas"]);
    $motor = trim($_POST["motor"]);
    $combustible = trim($_POST["combustible"]);
    $uso = trim($_POST["uso"]);
    $cantidad = trim($_POST["cantidad"]);
    $estado = trim($_POST["estado"]);
    $decripcion = trim($_POST["decripcion"]);
    $asignacion = trim($_POST["asignacion"]);

    do {
        // Validar que todos los campos obligatorios estén llenos
        if (empty($nombre) || empty($numero) || empty($tipo) || empty($marca) || empty($modelo) || empty($ano) || empty($serie) || empty($placas) || 
            empty($motor) || empty($combustible) || empty($uso) || empty($cantidad) || empty($estado) || 
            empty($decripcion) || empty($asignacion)) {
            $errorMessage = "Todos los campos son obligatorios.";
            break;
        }

        // Validación de Cantidad: debe ser un número positivo
        if (!is_numeric($cantidad) || (float)$cantidad <= 0) {
            $errorMessage = "El campo 'Cantidad' debe ser un número positivo.";
            break;
        }

        // Escapar valores para evitar inyección SQL
        $nombre = $connection->real_escape_string($nombre);
        $numero = $connection->real_escape_string($numero);
        $tipo = $connection->real_escape_string($tipo);
        $marca = $connection->real_escape_string($marca);
        $modelo = $connection->real_escape_string($modelo);
        $ano = $connection->real_escape_string($ano);
        $serie = $connection->real_escape_string($serie);
        $placas = $connection->real_escape_string($placas);
        $motor = $connection->real_escape_string($motor);
        $combustible = $connection->real_escape_string($combustible);
        $uso = $connection->real_escape_string($uso);
        $cantidad = (float)$cantidad;
        $estado = $connection->real_escape_string($estado);
        $decripcion = $connection->real_escape_string($decripcion);
        $asignacion = $connection->real_escape_string($asignacion);

        // Insertar en la base de datos
        $sql = "INSERT INTO vehiculos (Nombre, Numero, Tipo, Marca, Modelo, Ano, Serie, Placas, Motor, Combustible, Uso, Cantidad, Estado, Decripcion, Asignacion)
        VALUES ('$nombre', '$numero', '$tipo', '$marca', '$modelo', '$ano', '$serie', '$placas', '$motor', '$combustible', '$uso', '$cantidad', '$estado', '$decripcion', '$asignacion')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Error en la consulta: " . $connection->error;
            break;
        }

        $successMessage = "Vehículo agregado correctamente";
        header("location: index.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Vehículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Agregar Nuevo Vehículo</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><?php echo $errorMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nombre de la Unidad</label>
                <input type="text" class="form-control" name="nombre" required placeholder="Ejemplo: Volvo N191" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Numero Economico</label>
                <input type="number" class="form-control" name="numero" step="1" min="1" required placeholder="Ejemplo: 12345">
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Unidad</label>
                <select class="form-select" name="tipo" required>
                    <option value="Tractocamion">Tractocamión</option>
                    <option value="Maquinaria_Construccion">Maquinaria de Construcción</option>
                    <option value="Camion_Reparto">Camión de Reparto</option>
                    <option value="Automovil">Automóvil</option>
                    <option value="Montacargas">Montacargas</option>
                    <option value="Maquinaria_agricola">Maquinaria Agrícola</option>
                    <option value="Planta">Plantas de luz</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" required placeholder="Ejemplo: Volvo, Kenworth">
            </div>
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" name="modelo" required placeholder="Ejemplo: FH16, T680">
            </div>
            <div class="mb-3">
                <label class="form-label">Año</label>
                <input type="number" class="form-control" name="ano" step="1" min="1900" max="2099" required placeholder="Ejemplo: 2015">
            </div>
            <div class="mb-3">
                <label class="form-label">Numero de Serie</label>
                <input type="text" class="form-control" name="serie" required placeholder="Ejemplo: 1XKYDP9X6JJ123456">
            </div>
            <div class="mb-3">
                <label class="form-label">Placas (DEJAR VACIO SI NO APLICA)</label>
                <input type="text" class="form-control" name="placas" required placeholder="Ejemplo: ABC-1234">
            </div>
            <div class="mb-3">
                <label class="form-label">Numero de Motor</label>
                <input type="text" class="form-control" name="motor" required placeholder="Ejemplo: Y1234567">
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Combustible</label>
                <select class="form-select" name="combustible" required>
                    <option value="GASOLINA">Gasolina</option>
                    <option value="DIESEL">Diesel</option>
                    <option value="ELECTRICO">Eléctrico</option>
                    <option value="HIBRIDO">Híbrido</option>
                    <option value="GAS_LP">Gas LP</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Uso</label>
                <select class="form-select" name="uso" required>
                    <option value="KILOMETROS">Kilómetros</option>
                    <option value="HORAS">Horas</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Cantidad (Horas o Kilómetros)</label>
                <input type="number" class="form-control" name="cantidad" step="1" min="1" required placeholder="Ejemplo: 200,000 km">
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado" required>
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
                    <option value="MANTENIMIENTO">En Mantenimiento</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="decripcion" rows="4" required placeholder="Ejemplo: Unidad en buen estado, lista para operaciones."></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Asignación</label>
                <select class="form-select" name="asignacion" required>
                    <option value="fletes_tauro">Fletes Tauro</option>
                    <option value="tauro_logistica">Tauro Logística</option>
                    <option value="rancho_san_antonio">Rancho San Antonio</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-sm-6 d-grid">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <div class="col-sm-6 d-grid">
                    <a class="btn btn-outline-secondary" href="index.php" role="button">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
