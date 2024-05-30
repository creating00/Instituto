<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "curso";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['duracion']) || empty($_POST['precio'])){
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcurso = $_POST['id'];
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $precio = $_POST['precio'];
        $duracion = $_POST['duracion'];
        $sede1 = $_POST['sedes'];
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            }
        
        if($duracion==4){
            $tipo="4 meses";
        }
        if($duracion==5){
            $tipo="5 meses";
        }
        if($duracion==6){
            $tipo="6 meses";
        }
        if($duracion==10){
            $tipo="10 meses";
        }
        if($duracion==12){
            $tipo="12 meses";
        }
        if($duracion==24){
            $tipo="2 años";
        }
        if($duracion==30){
            $tipo="2 años y 6 Meses ";
        }
        if($duracion==36){
            $tipo="3 años";
        }
        
            $sql_update = mysqli_query($conexion, "UPDATE curso SET nombre='$nombre', precio='$precio', duracion='$duracion', tipo='$tipo', idsede='$idsede' WHERE idcurso='$idcurso'");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Curso Actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Curso</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: curso.php");
}
$idcurso = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM curso WHERE idcurso = $idcurso");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: curso.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        $nombre = $data['nombre'];
        $precio = $data['precio'];
        $duracion = $data['duracion'];
        
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Curso
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>

                        <input type="hidden" name="id" value="<?php echo $idcurso; ?>">
                        
                     <div class="form-group">
                        <label for="nombre">Curso</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data['nombre']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Precio</label>
                        <input type="text" class="form-control" placeholder="Ingrese Precio" name="precio" id="precio" class="form-control" value="<?php echo $data['precio']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duracion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Duracion" name="duracion" id="duracion" class="form-control" value="<?php echo $data['duracion']; ?>">
                    </div>
                    <div class="form-group">
                    
                    <select name="duracion" class="form-control" id="duracionCMB" onchange="ShowSelected();">
                    <option value="Select">Seleccionar Duracion..</option>
                                <option value="4">4 Meses</option>
                                <option value="5">5 Meses</option>
								<option value="6">6 Meses</option>
                                <option value="10">10 Meses</option>
								<option value="12">12 Meses</option>
								</select>
                    </div>
                    <div class="form-group">
                    <label for="sedes">Sede</label>
                    <select name="sedes" class="form-control">
                    <?php
                                                        //traer sedes
                                                        include "../conexion.php";
                                                    $query = mysqli_query($conexion, "SELECT nombre FROM sedes");
                                                    $result = mysqli_num_rows($query);
                                                    
                                                    while($row = mysqli_fetch_assoc($query))
                                                    {
	                                                //$idrol = $row['idrol'];
                                                    $prov = $row['nombre'];

													?>
													
                                                    <option values="<?php echo $prov; ?>"><?php echo $prov; ?></option>  

                                                    <?php
                                                     }
                                                    
                                                     ?>
								</select>
                    </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Curso</button>
                        <a href="curso.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<script type="text/javascript">
function ShowSelected()
{
/* Para obtener el valor */
var cod = document.getElementById("duracionCMB").value;
$('#duracion').val(cod);
//alert(cod);

}

</script>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>