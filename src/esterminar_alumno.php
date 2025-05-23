<?php
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "cuotas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: alumno.php");
}
if (!empty($_GET['id'])) {
    $idalumno = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM alumno WHERE idalumno = $idalumno");
    mysqli_close($conexion);
    header("Location: alumno.php");
}