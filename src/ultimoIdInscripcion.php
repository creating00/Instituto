<?php
// Conexión a la base de datos
require("../conexion.php");

// Consulta para obtener el último ID insertado
$query = "SELECT MAX(idinscripcion) AS last_id FROM inscripcion";
$result = mysqli_query($conexion, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $last_id = $row['last_id'];

    // Devolver el último ID como respuesta JSON
    echo json_encode(['success' => true, 'last_id' => $last_id]);
} else {
    // Si hubo un error en la consulta
    echo json_encode(['success' => false, 'message' => 'Error al obtener el último ID']);
}

mysqli_close($conexion);
