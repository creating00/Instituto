<?php
require_once "../conexion.php";

header('Content-Type: application/json'); // Asegúrate de especificar el tipo de contenido

$query = mysqli_query($conexion, "SELECT contenidoMensaje FROM mensaje_recordatorio WHERE id = 1");
$mensaje = mysqli_fetch_assoc($query);
$contenidoMensaje = $mensaje ? $mensaje['contenidoMensaje'] : '';

$response = json_encode(['contenidoMensaje' => $contenidoMensaje]);

// Agregar depuración
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error al codificar JSON: " . json_last_error_msg();
    exit;
}

echo $response;
