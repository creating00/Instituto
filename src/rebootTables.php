<?php
include_once "includes/header.php";
include "../conexion.php"; // Conexión a la base de datos

// Obtener las tablas de la base de datos
$queryTables = "SHOW TABLES";
$resultTables = mysqli_query($conexion, $queryTables); // Usamos $conexion en lugar de $mysqli

if (!$resultTables) {
    die("Error al obtener las tablas: " . mysqli_error($conexion));
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    $tablaSeleccionada = $_POST['tabla'];

    // Delete masivo
    $sqlDelete = "DELETE FROM $tablaSeleccionada";
    if (mysqli_query($conexion, $sqlDelete)) {
        echo "Registros eliminados de la tabla <strong>$tablaSeleccionada</strong> correctamente.<br>";
    } else {
        echo "Error al eliminar registros de <strong>$tablaSeleccionada</strong>: " . mysqli_error($conexion) . "<br>";
    }

    // Reiniciar índice auto_increment
    $sqlReset = "ALTER TABLE $tablaSeleccionada AUTO_INCREMENT = 1";
    if (mysqli_query($conexion, $sqlReset)) {
        echo "Índice AUTO_INCREMENT reiniciado para la tabla <strong>$tablaSeleccionada</strong>.<br>";
    } else {
        echo "Error al reiniciar el índice de <strong>$tablaSeleccionada</strong>: " . mysqli_error($conexion) . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Masivo y Reinicio de Índice</title>
</head>
<body>
    <h1>Delete Masivo y Reinicio del Auto Increment</h1>
    <form method="POST">
        <label for="tabla">Selecciona una tabla:</label>
        <select name="tabla" id="tabla" required>
            <option value="">-- Seleccionar --</option>
            <?php
            // Poblar el dropdown con las tablas
            while ($fila = mysqli_fetch_row($resultTables)) {
                echo "<option value=\"{$fila[0]}\">{$fila[0]}</option>";
            }
            ?>
        </select>
        <br><br>
        <button type="submit">Eliminar Registros y Reiniciar Índice</button>
    </form>
</body>
</html>
