<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unidades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <a class="btn btn-primary mb-3" href="create.php" role="button">Nueva Unidad</a>

        <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");



        // Definir orden y columna por defecto
        $column = isset($_GET['column']) ? $_GET['column'] : 'ID';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        // Validar columnas permitidas para evitar inyecciones SQL
        $valid_columns = [
            'ID', 'Nombre', 'Numero', 'Tipo', 'Marca', 'Modelo', 'Ano', 'Serie',
            'Placas', 'Motor', 'Combustible', 'Uso', 'Cantidad', 'Estado', 'Decripcion', 'Asignacion'
        ];
        if (!in_array($column, $valid_columns)) {
            $column = 'ID';
        }

        // Alternar entre ASC y DESC
        $new_order = ($order === 'ASC') ? 'DESC' : 'ASC';

        // Obtener datos ordenados
        $sql = "SELECT * FROM vehiculos ORDER BY $column $order";
        $result = $connection->query($sql);
        ?>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <?php
                    $headers = [
                        "ID" => "ID", "Nombre" => "Nombre", "Número" => "Numero", "Tipo" => "Tipo", "Marca" => "Marca",
                        "Modelo" => "Modelo", "Año" => "Ano", "Serie" => "Serie", "Placas" => "Placas", "Motor" => "Motor",
                        "Combustible" => "Combustible", "Uso" => "Uso", "Cantidad KM/HORAS" => "Cantidad", "Estado" => "Estado",
                        "Descripción" => "Decripcion", "Asignación" => "Asignacion"
                    ];
                    
                    foreach ($headers as $header_text => $col_name) {
                        $arrow = ($column === $col_name) ? ($order === 'ASC' ? '↑' : '↓') : '';
                        echo "<th><a href='?column=$col_name&order=$new_order' class='text-white text-decoration-none'>$header_text $arrow</a></th>";
                    }
                    ?>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['Nombre']}</td>
                            <td>{$row['Numero']}</td>
                            <td>{$row['Tipo']}</td>
                            <td>{$row['Marca']}</td>
                            <td>{$row['Modelo']}</td>
                            <td>{$row['Ano']}</td>
                            <td>{$row['Serie']}</td>
                            <td>{$row['Placas']}</td>
                            <td>{$row['Motor']}</td>
                            <td>{$row['Combustible']}</td>
                            <td>{$row['Uso']}</td>
                            <td>{$row['Cantidad']}</td>
                            <td>{$row['Estado']}</td>
                            <td>{$row['Decripcion']}</td>
                            <td>{$row['Asignacion']}</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='edit.php?id={$row['ID']}'>Editar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='17' class='text-center text-muted'>No hay Unidades registradas.</td></tr>";
                }

                $connection->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
