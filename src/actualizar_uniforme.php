<?php
include "../conexion.php";

// Establecer el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $alert = "";
    $id_uniforme = $_POST['id']; // Obtener solo el id

    // Si se pasa la nueva información del uniforme
    if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['talla']) && isset($_POST['genero']) && isset($_POST['precio']) && isset($_POST['stock'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $talla = $_POST['talla'];
        $genero = $_POST['genero'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        // Validación de campos vacíos
        if (empty($nombre) || empty($talla) || empty($genero) || empty($precio) || empty($stock)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
        } else {
            // Realizar la actualización en la base de datos
            $sql_update = mysqli_query($conexion, "UPDATE uniformes SET nombre='$nombre', descripcion='$descripcion', talla='$talla', genero='$genero', precio='$precio', stock='$stock' WHERE id_uniforme='$id_uniforme'");

            if ($sql_update) {
                echo json_encode(['success' => true, 'message' => 'Uniforme actualizado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el uniforme.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan algunos campos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud no válida.']);
}
?>