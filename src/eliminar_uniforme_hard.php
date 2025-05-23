<?php 
include "../conexion.php";

// Verificar si se pasa el id por GET
if (isset($_GET['id'])) {
    $id_uniforme = $_GET['id'];

    // Comenzamos por eliminar los registros asociados al uniforme (por ejemplo, ventas)
    $sql_delete_ventas = mysqli_query($conexion, "DELETE FROM ventas_uniformes WHERE id_uniforme = '$id_uniforme'");

    // Verificamos si hubo algún error al eliminar los registros relacionados
    if ($sql_delete_ventas) {
        // Ahora eliminamos el uniforme de la tabla 'uniformes'
        $sql_delete_uniforme = mysqli_query($conexion, "DELETE FROM uniformes WHERE id_uniforme = '$id_uniforme'");

        if ($sql_delete_uniforme) {
            echo '<span style="color: green; font-weight: bold;">Uniforme y sus registros asociados eliminados correctamente. <i class="fas fa-check-circle"></i></span>';
        } else {
            echo '<span style="color: red; font-weight: bold;">Error al eliminar el uniforme. <i class="fas fa-times-circle"></i></span>';
        }
    } else {
        echo '<span style="color: red; font-weight: bold;">Error al eliminar los registros asociados al uniforme. <i class="fas fa-times-circle"></i></span>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">ID de uniforme no especificado.</div>';
}
