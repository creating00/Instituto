<?php
include "../conexion.php";

// Establecer el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verificar si se pasa el id de la venta por POST
if (isset($_POST['id_venta'])) {
    $id_venta = $_POST['id_venta'];

    // Eliminar la venta de la tabla 'ventas'
    $sql_delete_venta = mysqli_query($conexion, "DELETE FROM ventas_uniformes WHERE id_venta = '$id_venta'");

    if ($sql_delete_venta) {
        // Enviamos una respuesta JSON de éxito
        echo json_encode(['success' => true, 'message' => 'Venta eliminada correctamente.']);
    } else {
        // Enviamos una respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la venta.']);
    }
} else {
    // Enviamos una respuesta JSON si no se pasa el ID de la venta
    echo json_encode(['success' => false, 'message' => 'ID de venta no especificado.']);
}
?>