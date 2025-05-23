<?php
include "../conexion.php";

// Realizar la consulta para obtener los uniformes
$query = mysqli_query($conexion, "SELECT id_uniforme, nombre, descripcion, talla, genero, precio, stock FROM uniformes");

if (!$query) {
    // Si la consulta falla, devolver un error
    echo json_encode(['error' => 'Error al cargar los uniformes: ' . mysqli_error($conexion)]);
    exit;
}

$result = mysqli_num_rows($query);
$uniformes = [];

if ($result > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
        // Validar y formatear los datos
        $uniformes[] = [
            'id' => (int)$data['id_uniforme'], // Asegurar que el ID sea un número
            'nombre' => htmlspecialchars($data['nombre']), // Evitar XSS
            'descripcion' => htmlspecialchars($data['descripcion']), // Evitar XSS
            'talla' => htmlspecialchars($data['talla']), // Evitar XSS
            'genero' => htmlspecialchars($data['genero']), // Evitar XSS
            'precio' => "$" . number_format((float)$data['precio'], 2, '.', ','), // Formatear precio
            'stock' => (int)$data['stock'] // Asegurar que el stock sea un número
        ];
    }
}

// Devolver los datos en formato JSON
echo json_encode($uniformes);
?>