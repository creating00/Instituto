<?php
// Incluir el archivo de conexión a la base de datos
include "../conexion.php";

// Realizar la consulta para obtener los datos de configuración
$query = mysqli_query($conexion, "SELECT id, nombre, telefono, email, direccion, detalle_fac FROM configuracion");

if (!$query) {
    // Si la consulta falla, devolver un error en formato JSON
    echo json_encode(['error' => 'Error al cargar la configuración: ' . mysqli_error($conexion)]);
    exit;
}

$result = mysqli_num_rows($query);
$configuraciones = [];

if ($result > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
        // Validar y formatear los datos
        $configuraciones[] = [
            'id' => (int)$data['id'], // Asegurar que el ID sea un número
            'nombre' => htmlspecialchars($data['nombre']), // Evitar XSS
            'telefono' => htmlspecialchars($data['telefono']), // Evitar XSS
            'email' => htmlspecialchars($data['email']), // Evitar XSS
            'direccion' => htmlspecialchars($data['direccion']), // Evitar XSS
            'detalle_fac' => htmlspecialchars($data['detalle_fac'] ?? ''), // Evitar XSS, manejar valores nulos
        ];
    }
}

// Devolver los datos en formato JSON
echo json_encode($configuraciones);
?>