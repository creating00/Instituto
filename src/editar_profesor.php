<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "profesor";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['dni']) || empty($_POST['nombre']) || empty($_POST['apellido'])){
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idprofesor = $_POST['id'];
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $celular = $_POST['celular'];
        $email= $_POST['email'];
        $sede = $_POST['sede'];
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
        while($row = mysqli_fetch_array($rs))
        {
            $idsede=$row['idsede'];
        
        }
        
            $sql_update = mysqli_query($conexion, "UPDATE profesor SET dni='$dni', nombre='$nombre', apellido='$apellido', direccion='$direccion', celular='$celular', email='$email', idsede='$idsede' WHERE idprofesor='$idprofesor'");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Profesor Actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Profesor</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: profesor.php");
}
$idprofesor = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM profesor WHERE idprofesor = $idprofesor");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: profesor.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        $dni = $data['dni'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $direccion = $data['direccion'];
        $celular = $data['celular'];
        $email = $data['email'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Profesor
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>

                        <input type="hidden" name="id" value="<?php echo $idprofesor; ?>">
                        <div class="form-group">
                         <label for="cantidad">DNI</label>
                         <input type="number" placeholder="Ingrese DNI" class="form-control" name="dni" id="dni" class="form-control" value="<?php echo $data['dni']; ?>">
                     </div>
                     <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data['nombre']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" placeholder="Ingrese Apellido" name="apellido" id="apellido" class="form-control" value="<?php echo $data['apellido']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control" value="<?php echo $data['direccion']; ?>">
                    </div>
                    <div class="form-group">
                         <label for="celular">Celular</label>
                         <input type="number" placeholder="Ingrese cantidad" class="form-control" name="celular" id="celular" class="form-control" value="<?php echo $data['celular']; ?>">
                     </div>
                     <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email" class="form-control" value="<?php echo $data['email']; ?>">
                    </div>
                    <div class="form-group">
                     <label for="sede">Sede</label>
                    <select name="sede" class="form-control">
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
                   
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Profesor</button>
                        <a href="profesor.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>