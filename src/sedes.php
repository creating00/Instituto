<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sedes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
    if (!empty($_POST)) {
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $provincia = $_POST['provincias'];
		$ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        //$usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($nombre) || empty($provincia) || empty($direccion) ) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM sedes WHERE nombre = '$nombre'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        La Sede ya existe
                    </div>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO sedes(nombre, provincia, ciudad, direccion, email, telefono, estado) 
                VALUES('$nombre', '$provincia', '$ciudad', '$direccion', '$email', '$telefono', 1)");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Sede Registrada
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar la Sede
              </div>';
                }
            }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_sede"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 
                 <th>Sede</th>
                 <th>Provincia</th>
                 <th>Ciudad</th>
                 <th>Direccion</th>
                 <th>Email</th>
                 <th>Telefono</th>
                 <th>Estado</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";

                $query = mysqli_query($conexion, "SELECT idsede, nombre, provincia, ciudad, direccion, email, telefono, estado FROM sedes");
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
                       
                         <td><?php echo $data['nombre']; ?></td>
                         <td><?php echo $data['provincia']; ?></td>
                         <td><?php echo $data['ciudad']; ?></td>
                         <td><?php echo $data['direccion']; ?></td>
                         <td><?php echo $data['email']; ?></td>
                         <td><?php echo $data['telefono']; ?></td>
                         <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 

                                 <a href="editar_sedes.php?id=<?php echo $data['idsede']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_sedes.php?id=<?php echo $data['idsede']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             <?php } else{
                                       
                                       echo "<a href='alta_sedes.php?id=".$data['idsede']."'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
      
   
                                }?>
                         </td>
                     
                     </tr>
             <?php }
                } ?>
         </tbody>

     </table>
 </div>
 <div id="nueva_sede" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">NuevaSede</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     
                     <div class="form-group">
                        <label for="sede">Sede</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="sede">Provincia</label>
                        <select name="provincias" class="form-control">
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
                        <input type="text" class="form-control" placeholder="Ingrese Ciudad" name="ciudad" id="ciudad">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion">
                    </div>
                     <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email">
                    </div>
                    <div class="form-group">
                         <label for="Telefono">Telefono</label>
                         <input type="number" placeholder="Ingrese Telefono" class="form-control" name="telefono" id="telefono">
                     </div>
                     <input type="submit" value="Guardar Sede" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>