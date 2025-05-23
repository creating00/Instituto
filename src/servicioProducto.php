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
        $descripcion = $_POST['descripcion'];
        $tipo = $_POST['tipo'];
        $proveedor = $_POST['proveedor'];
        $importe = $_POST['importe'];
        $idsede = $_POST['sedes'];
        $rs = mysqli_query($conexion, "SELECT idproveedor FROM proveedores WHERE nombre ='$proveedor'");
            while($row = mysqli_fetch_array($rs))
            {
                $idproveedor=$row['idproveedor'];
            
            }

        $alert = "";
        if (empty($descripcion)) {
            echo '<script language="javascript">';
            echo 'alert("El campo descripcion es obligatorio");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM servicioproducto WHERE descripcion = '$descripcion'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                echo '<script language="javascript">';
            echo 'alert("Ya se registro el servico o alquiler");';
            echo 'window.location.href = "gastos.php"';
            echo '</script>';
                    
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO servicioproducto(descripcion,tipo,idproveedor,importe, idsede, idusuario) values ('$descripcion', '$tipo','$idproveedor', '$importe', '$idsede', '$id_user')");
                if ($query_insert) {
                    echo '<script language="javascript">';
            echo 'alert("Se guardo Correctamente");';
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