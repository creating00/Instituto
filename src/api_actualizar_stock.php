<?php
// Conexión a la base de datos
require_once('../conexion.php');

header('Content-Type: application/json');  // Esto asegura que la respuesta es JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_uniforme = $_POST['id_uniforme'];
    $cantidad = $_POST['cantidad'];

    // Verificar si los valores son válidos
    if (!is_numeric($id_uniforme) || !is_numeric($cantidad) || $cantidad <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
        exit;
    }

    // Obtener el stock actual del uniforme
    $query = "SELECT stock FROM uniformes WHERE id_uniforme = $id_uniforme";
    $resultado = mysqli_query($conexion, $query);
    $uniforme = mysqli_fetch_assoc($resultado);

    if (!$uniforme) {
        echo json_encode(['status' => 'error', 'message' => 'Uniforme no encontrado.']);
        exit;
    }

    $nuevo_stock = $uniforme['stock'] - $cantidad;

    // Actualizar el stock en la base de datos
    if ($nuevo_stock >= 0) {
        $updateQuery = "UPDATE uniformes SET stock = $nuevo_stock WHERE id_uniforme = $id_uniforme";
        if (mysqli_query($conexion, $updateQuery)) {
            echo json_encode(['status' => 'success', 'message' => 'Stock actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el stock.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No hay suficiente stock.']);
    }
}
