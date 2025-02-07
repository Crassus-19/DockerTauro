<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");


// Definir la columna y el orden predeterminado
$column = isset($_GET['column']) ? $_GET['column'] : 'Fecha_Registro';
$order = isset($_GET['order']) && $_GET['order'] == 'ASC' ? 'ASC' : 'DESC';

// Validar que la columna solicitada sea válida para evitar inyecciones SQL
$valid_columns = ['ID', 'Unidad', 'Uso', 'Cantidad', 'Tipo_Orden', 'Comentarios', 'Reportado_Por', 'Asignacion', 'Fecha_Registro'];
if (!in_array($column, $valid_columns)) {
    $column = 'Fecha_Registro';
}

// Alternar el orden para la próxima vez que se haga clic
$new_order = $order === 'ASC' ? 'DESC' : 'ASC';

// Consulta SQL con orden dinámico
$sql = "SELECT registros.ID, vehiculos.Nombre AS Unidad, vehiculos.Uso, registros.Cantidad, 
               registros.Tipo_Orden, registros.Comentarios, registros.Reportado_Por, vehiculos.Asignacion, registros.Fecha_Registro
        FROM registros 
        INNER JOIN vehiculos ON registros.Unidad_ID = vehiculos.ID 
        ORDER BY $column $order";

$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Unidades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script>
        function imprimirPagina() {
            window.print();
        }

        function imprimirRegistro(id) {
            let fila = document.getElementById('fila-' + id);
            if (fila) {
                let ventanaImpresion = window.open('', '', 'width=600,height=400');
                ventanaImpresion.document.write('<html><head><title>Registro ' + id + '</title>');
                ventanaImpresion.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">');
                ventanaImpresion.document.write('</head><body>');
                ventanaImpresion.document.write('<h3 class="text-center">Registro de Unidad</h3>');
                ventanaImpresion.document.write('<table class="table table-bordered">');
                ventanaImpresion.document.write(fila.outerHTML);
                ventanaImpresion.document.write('</table>');
                ventanaImpresion.document.write('</body></html>');
                ventanaImpresion.document.close();
                ventanaImpresion.print();
            }
        }
    </script>
</head>
<body>
    <div class="container my-5">

        <!-- Botón para agregar un nuevo registro -->
        <a href="registrosU.php" class="btn btn-primary mb-3">Agregar Registro</a>
        
        <!-- Botón para imprimir todo -->
        <button onclick="imprimirPagina()" class="btn btn-secondary mb-3">Imprimir Todo</button>

        <!-- Tabla de registros -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th><a href="?column=ID&order=<?= $new_order ?>" class="text-white">ID</a></th>
                    <th><a href="?column=Unidad&order=<?= $new_order ?>" class="text-white">Unidad</a></th>
                    <th><a href="?column=Uso&order=<?= $new_order ?>" class="text-white">Tipo de Uso</a></th>
                    <th><a href="?column=Cantidad&order=<?= $new_order ?>" class="text-white">KM / HORAS</a></th>
                    <th><a href="?column=Tipo_Orden&order=<?= $new_order ?>" class="text-white">Tipo de Orden</a></th>
                    <th><a href="?column=Comentarios&order=<?= $new_order ?>" class="text-white">Comentarios</a></th>
                    <th><a href="?column=Reportado_Por&order=<?= $new_order ?>" class="text-white">Reportado por</a></th>
                    <th><a href="?column=Asignacion&order=<?= $new_order ?>" class="text-white">Asignación</a></th>
                    <th><a href="?column=Fecha_Registro&order=<?= $new_order ?>" class="text-white">Fecha</a></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="fila-<?= $row['ID']; ?>">
                            <td><?php echo $row["ID"]; ?></td>
                            <td><?php echo $row["Unidad"]; ?></td>
                            <td><?php echo $row["Uso"]; ?></td>
                            <td><?php echo number_format($row["Cantidad"], 0, ',', '.'); ?></td>
                            <td><?php echo $row["Tipo_Orden"]; ?></td>
                            <td><?php echo $row["Comentarios"]; ?></td>
                            <td><?php echo $row["Reportado_Por"]; ?></td>
                            <td><?php echo $row["Asignacion"]; ?></td>
                            <td><?php echo $row["Fecha_Registro"]; ?></td>
                            <td>
                                <a href="generar_pdf.php?id=<?= $row['ID']; ?>" class="btn btn-outline-dark btn-sm" target="_blank">
                                    🖨️ Generar PDF
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No hay registros disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
