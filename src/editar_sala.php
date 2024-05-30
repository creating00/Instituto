<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sala";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['sala']) || empty($_POST['descripcion'])){
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idsala = $_POST['id'];
        $sala = $_POST['sala'];
        $descripcion = $_POST['descripcion'];
        
            $sql_update = mysqli_query($conexion, "UPDATE sala SET sala='$sala', descripcion='$descripcion' WHERE idsala='$idsala'");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Sala Actualizada correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar la Sala</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: sala.php");
}
$idsala = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM sala WHERE idsala = $idsala");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: sala.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        
        $sala = $data['sala'];
        $descripcion = $data['descripcion'];
       
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Sala
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>

                        <input type="hidden" name="id" value="<?php echo $idsala; ?>">
                        <div class="form-group">
                         <label for="cantidad">Sala</label>
                         <input type="text" placeholder="Ingrese nombre" class="form-control" name="sala" id="sala" class="form-control" value="<?php echo $data['sala']; ?>">
                     </div>
                     <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <input type="text" class="form-control" placeholder="Ingrese descripcion" name="descripcion" id="descripcion" class="form-control" value="<?php echo $data['descripcion']; ?>">
                    </div>
                    
                   
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Sala</button>
                        <a href="sala.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>