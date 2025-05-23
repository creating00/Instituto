<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "gastos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    //header("Location: permisos.php");
}
if (!empty($_POST)) {
$proveedor = $_POST['proveedor'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $idsede = $_POST['sedes'];

        $alert = "";
        if (empty($proveedor)) {
            echo '<script language="javascript">';
            echo 'alert("todos los campos son Obligatorios");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM proveedores WHERE nombre = '$proveedor'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                echo '<script language="javascript">';
            echo 'alert("El proveedor ya existe");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO proveedores(nombre, direccion, telefono, correo, idsede, idusuario) values ('$proveedor', '$direccion','$telefono','$correo', '$idsede', '$id_user')");
                if ($query_insert) {
                    echo '<script language="javascript">';
                    echo 'alert("Se guardo correctamente");';
                    echo 'window.location.href = "gastos.php"';
                    echo '</script>';
                } else {
                    echo '<script language="javascript">';
            echo 'alert("Error al guardar");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
                }
            }
        }
    }

?>        