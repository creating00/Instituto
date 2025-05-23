<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sedes";// editar esto por uniformes
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['provincia']) || empty($_POST['ciudad'])){
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idsede = $_POST['id'];
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $provincia = $_POST['provincia'];
		$ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
       
        
            $sql_update = mysqli_query($conexion, "UPDATE sedes SET nombre='$nombre', provincia='$provincia', ciudad='$ciudad', direccion='$direccion', email='$email', telefono='$telefono' WHERE idsede='$idsede'");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Sede Actualizada correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar la Sede/div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: sedes.php");
}
$idsede = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM sedes WHERE idsede = $idsede");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: sedes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        
        $nombre = $data['nombre'];
        $provincia = $data['provincia'];
        $ciudad = $data['ciudad'];
        $direccion = $data['direccion'];
        $email = $data['email'];
        $telefono = $data['telefono'];
        
        
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

                        <input type="hidden" name="id" value="<?php echo $idsede; ?>">
                        
                     <div class="form-group" hidden>
                        <label for="sede">Sede</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data['nombre']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="sede">Provincia</label>
                        <select name="provincia" class="form-control">
                        <option value="" disabled="" selected="">Seleccionar Provincia</option>
													<option value="BUENOS AIRES">BUENOS AIRES</option>
													<option value="CABA">CABA</option>
                                                    <option value="CATAMARCA">CATAMARCA</option>
                                                    <option value="CHACO">CHACO</option>
                                                    <option value="CHUBUT">CHUBUT</option>
                                                    <option value="CORDOBA">CORDOBA</option>
                                                    <option value="CORRIENTES">CORRIENTES</option>
                                                    <option value="ENTRE RIOS">ENTRE RIOS</option>
                                                    <option value="FORMOSA">FORMOSA</option>
                                                    <option value="JUJUY">JUJUY</option>
                                                    <option value="LA PAMPA">LA PAMPA</option>
                                                    <option value="LA RIOJA">LA RIOJA</option>
                                                    <option value="MENDOZA">MENDOZA</option>
                                                    <option value="MISIONES">MISIONES</option>
                                                    <option value="NEUQUEN">NEUQUEN</option>
                                                    <option value="RIO NEGRO">RIO NEGRO</option>
                                                    <option value="SALTA">SALTA</option>
                                                    <option value="SAN JUAN">SAN JUAN</option>
                                                    <option value="SAN LUIS">SAN LUIS</option>
                                                    <option value="SANTA CRUZ">SANTA CRUZ</option>
                                                    <option value="SANTA FE">SANTA FE</option>
                                                    <option value="SANTIAGO DEL ESTERO">SANTIAGO DEL ESTERO</option>
                                                    <option value="TIERRA DE FUEGO">TIERRA DE FUEGO</option>
                                                    <option value="TUCUMAN">TUCUMAN</option>
                                                    
												</select>
                    </div>
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" class="form-control" placeholder="Ingrese Ciudad" name="ciudad" id="ciudad" class="form-control" value="<?php echo $data['ciudad']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control" value="<?php echo $data['direccion']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email" class="form-control" value="<?php echo $data['email']; ?>">
                    </div>
                    <div class="form-group">
                         <label for="telefono">Telefono</label>
                         <input type="number" placeholder="Ingrese cantidad" class="form-control" name="telefono" id="telefono" class="form-control" value="<?php echo $data['telefono']; ?>">
                     </div>
                    
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Sede</button>
                        <a href="sedes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>