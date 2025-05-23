<?php
include "../conexion.php";

// Verificar si se pasa el id por GET
if (isset($_GET['id'])) {
    $id_uniforme = $_GET['id'];

    // Obtener el estado actual del uniforme
    $sql_check = mysqli_query($conexion, "SELECT estado FROM uniformes WHERE id_uniforme = '$id_uniforme'");
    $data = mysqli_fetch_assoc($sql_check);

    if ($data) {
        // Cambiar el estado: Si está activo (1), desactivarlo (0), y viceversa
        $nuevo_estado = ($data['estado'] == 1) ? 0 : 1;

        // Actualizar el estado del uniforme
        $sql_update_estado = mysqli_query($conexion, "UPDATE uniformes SET estado = '$nuevo_estado' WHERE id_uniforme = '$id_uniforme'");

        if ($sql_update_estado) {
            echo '<div class="alert alert-success" role="alert">Estado del uniforme actualizado correctamente.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al actualizar el estado del uniforme.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Uniforme no encontrado.</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">ID de uniforme no especificado.</div>';
}
