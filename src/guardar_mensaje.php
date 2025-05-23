<?php
include "../conexion.php";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanear los datos de entrada para evitar inyecciones
    $contenido = mysqli_real_escape_string($conexion, $_POST['contenidoMensaje']);
    $estado = isset($_POST['estado']) ? 1 : 0;

    // Verificar si el contenido no está vacío
    if (empty($contenido)) {
        echo "false"; // Devuelve false si el contenido está vacío
        exit;
    }

    // Verificar si ya existe un registro
    $query = mysqli_query($conexion, "SELECT id FROM mensaje_recordatorio WHERE id = 1");

    if (!$query) {
        error_log('Error al ejecutar la consulta de selección: ' . mysqli_error($conexion));
        echo "false"; // Devuelve false si hay error en la consulta
        exit;
    }

    if (mysqli_num_rows($query) > 0) {
        // Actualizar el registro existente
        $query_update = mysqli_prepare(
            $conexion,
            "UPDATE mensaje_recordatorio 
            SET contenidoMensaje = ?, estado = ?, fechaUltimaActualizacion = NOW() 
            WHERE id = (SELECT id FROM mensaje_recordatorio WHERE id = 1)"
        );

        if ($query_update === false) {
            error_log('Error al preparar la consulta de actualización: ' . mysqli_error($conexion));
            echo "false"; // Error al preparar la consulta
            exit;
        }

        mysqli_stmt_bind_param($query_update, 'si', $contenido, $estado);

        if (mysqli_stmt_execute($query_update)) {
            echo "true"; // Devuelve true si la actualización fue exitosa
        } else {
            error_log('Error al ejecutar la consulta de actualización: ' . mysqli_error($conexion));
            echo "false"; // Error al ejecutar la consulta
        }
    } else {
        // Insertar un nuevo registro
        $query_insert = mysqli_prepare(
            $conexion,
            "INSERT INTO mensaje_recordatorio (contenidoMensaje, estado) 
            VALUES (?, ?)"
        );

        if ($query_insert === false) {
            error_log('Error al preparar la consulta de inserción: ' . mysqli_error($conexion));
            echo "false"; // Error al preparar la consulta
            exit;
        }

        mysqli_stmt_bind_param($query_insert, 'si', $contenido, $estado);

        if (mysqli_stmt_execute($query_insert)) {
            echo "true"; // Devuelve true si la inserción fue exitosa
        } else {
            error_log('Error al ejecutar la consulta de inserción: ' . mysqli_error($conexion));
            echo "false"; // Error al ejecutar la consulta
        }
    }
} else {
    echo "false"; // Método no permitido
}
