<?php
// Incluir el archivo de conexión a la base de datos
include "../conexion.php";

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados en la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que se haya enviado el campo detalle_fac
    if (!isset($data['detalle_fac'])) {
        echo json_encode(['error' => 'El campo "detalle_fac" es obligatorio.']);
        exit;
    }

    // Limpiar y validar los datos
    $detalleFac = htmlspecialchars(trim($data['detalle_fac']));

    // Construir la consulta SQL para actualizar el detalle_fac
    $query = "UPDATE configuracion SET detalle_fac = ? WHERE id = 1"; // Asumimos que actualizamos el primer registro (id = 1)

    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $query);
    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar la consulta: ' . mysqli_error($conexion)]);
        exit;
    }

    // Enlazar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 's', $detalleFac);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Si la actualización fue exitosa, devolver una respuesta exitosa
        echo json_encode(['success' => 'El detalle_fac ha sido actualizado correctamente.']);
    } else {
        // Si hubo un error en la ejecución, devolver un mensaje de error
        echo json_encode(['error' => 'Error al actualizar el detalle_fac: ' . mysqli_error($conexion)]);
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);
} else {
    // Si la solicitud no es de tipo POST, devolver un error
    echo json_encode(['error' => 'Método no permitido. Usa POST para actualizar el detalle_fac.']);
}
?>