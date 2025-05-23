<?php
session_start(); 
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "inscripcion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
$alertEliminar = ''; // Variable para almacenar la alerta

if (empty($existe) && $id_user != 1) {
    header("Location: inscripcion.php");
}

if (!empty($_POST['idinscripcion'])) {
    $idinscripcion = $_POST['idinscripcion']; // Cambiar de $_GET a $_POST
    $query_delete = mysqli_query($conexion, "DELETE FROM inscripcion WHERE idinscripcion = $idinscripcion");

    if ($query_delete) {
        // Alerta de éxito
        $alertEliminar = '<div class="alert alert-success" role="alert">
                            Inscripción eliminada correctamente.
                          </div>';
    } else {
        // Alerta de error
        $alertEliminar = '<div class="alert alert-danger" role="alert">
                            Error al eliminar la inscripción. Intente más tarde.
                          </div>';
    }

    mysqli_close($conexion);
    header("Location: inscripcion.php?alert=" . urlencode($alertEliminar)); // Pasar la alerta como parámetro en la URL
}
?>