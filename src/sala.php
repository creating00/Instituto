<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "salas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
    if (!empty($_POST)) {
        $sala = $_POST['sala'];
        $descripcion = $_POST['descripcion'];
		
        //$usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($sala) || empty($descripcion)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM sala WHERE sala = '$sala'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        La Sala ya existe
                    </div>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO sala(sala, descripcion, estado) 
                VALUES('$sala', '$descripcion',1)");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Sala Registrada
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar la Sala
              </div>';
                }
            }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_sala"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 
                 <th>Sala</th>
                 <th>Descripcion</th>
                 <th>Estado</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";

                $query = mysqli_query($conexion, "SELECT idsala, sala, descripcion, estado FROM sala");
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                        if ($data['estado'] == 1) {
                            $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                        } else {
                            $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                        }
                ?>
                     <tr>
                       
                         <td><?php echo $data['sala']; ?></td>
                         <td><?php echo $data['descripcion']; ?></td>
                         <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 

                                 <a href="editar_sala.php?id=<?php echo $data['idsala']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_sala.php?id=<?php echo $data['idsala']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             <?php }else{
                                       
                                       echo "<a href='alta_sala.php?id=".$data['idsala']."'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
      
   
                                } ?>
                         </td>
                     
                     </tr>
             <?php }
                } ?>
         </tbody>

     </table>
 </div>
 <div id="nueva_sala" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">NuevaSala</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     
                     <div class="form-group">
                        <label for="sala">Sala</label>
                        <input type="text" class="form-control" placeholder="Ingrese Sala" name="sala" id="sala">
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Descripcion" name="descripcion" id="descripcion">
                    </div>
                    
                     <input type="submit" value="Guardar Sede" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>