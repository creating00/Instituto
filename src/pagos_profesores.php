<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
//$permiso = "gastos";
//$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
//$existe = mysqli_fetch_all($sql);
//if (empty($existe) && $id_user != 1) {
//header("Location: permisos.php");
//}
if (!empty($_POST)) {
    $alert = "";
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $feha_actual = date("Y-m-d H:i:s");

    $fecha = $_POST['fechaPago'];
    $idprofesor = $_POST['idProfesor'];
    $importe = $_POST['importeD'];
    $idsede = $_POST['sedes'];
    $descripcion = $_POST['descripcion'];
    $fechaComoEntero = strtotime($feha_actual);
    $anio = date("Y", $fechaComoEntero);
    $mes = date("m", $fechaComoEntero);

    if (empty($idprofesor) || empty($importe)) {
        echo '<script language="javascript">';
        echo 'alert("todos los campos son Obligatorios");';
        echo 'window.location.href = "gastos.php"';
        echo '</script>';
    } else {
        $query = mysqli_query($conexion, "SELECT idpagosprofesores FROM pagosprofesores WHERE idprofesor ='$idprofesor' and mes='$mes' and año='$anio'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            echo '<script language="javascript">';
            echo 'alert("Ya se pago al docente en esa fecha");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO pagosprofesores(idprofesor,idsede,importe,fecha,mes,año, descripcion) values ('$idprofesor', '$idsede', '$importe', '$fecha', '$mes', '$anio', '$descripcion')");
            if ($query_insert) {
                echo '<script language="javascript">';
                echo 'alert("Se pago al docente correctamente");';
                echo 'window.location.href = "gastos.php"';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("Error al Pagar");';
                echo 'window.location.href = "gastos.php"';
                echo '</script>';
            }
        }
    }
}
