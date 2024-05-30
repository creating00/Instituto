<?php
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "inscripcion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: inscripcion.php");
}
if (!empty($_GET['id'])) {
    $idinscripcion = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM inscripcion WHERE idinscripcion = $idinscripcion");
    mysqli_close($conexion);
    header("Location: inscripcion.php");
}