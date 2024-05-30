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
       
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $precio = $_POST['precio'];
		$duracion = $_POST['duracion'];
        $sede1 = $_POST['sedes'];
        //sedes
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            }
    
        //$usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($nombre) || empty($precio) || empty($duracion)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM curso WHERE nombre = '$nombre' and idsede='$idsede'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El Curso ya existe
                    </div>';
            } else {
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
				$query_insert = mysqli_query($conexion,"INSERT INTO curso( nombre, precio, duracion, estado, tipo, idsede) 
                VALUES('$nombre', '$precio', '$duracion',1,'$tipo', '$idsede')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Curso Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el Curso
              </div>';
                }
            }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_curso"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 
                 <th>Curso</th>
                 <th>Precio</th>
                 <th>Duracion</th>
                 <th>Sede</th>
                 <th>Estado</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";
                $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario 
                INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario='$id_user'");
                while($row = mysqli_fetch_array($rs))
                {
                    $sede=$row['nombre'];
                
                }
                if($sede=="GENERAL"){

                    $query = mysqli_query($conexion, "SELECT idcurso, curso.nombre, precio, duracion, tipo, sedes.nombre'sede', curso.estado FROM curso 
                     INNER JOIN sedes on curso.idsede=sedes.idsede");

                }
                else{

                $query = mysqli_query($conexion, "SELECT idcurso, curso.nombre, precio, duracion, sedes.nombre'sede', tipo, curso.estado FROM curso 
                INNER JOIN sedes on curso.idsede=sedes.idsede WHERE sedes.nombre='$sede'");
                
                }
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
                         <td><?php echo $data['precio']; ?></td>
                         <td><?php echo $data['tipo']; ?></td>
                         <td><?php echo $data['sede']; ?></td>
                         <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 
                                <a href="editar_curso.php?id=<?php echo $data['idcurso']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_curso.php?id=<?php echo $data['idcurso']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             <?php }else{
                                       
                                    echo "<a href='alta_curso.php?id=".$data['idcurso']."'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
   

                             } ?>
                         </td>
                     
                     </tr>
             <?php }
                } ?>
         </tbody>

     </table>
 </div>
 <div id="nuevo_curso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">NuevoCurso</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     
                     <div class="form-group">
                        <label for="nombre">Curso</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" class="form-control" placeholder="Ingrese el Precio" name="precio" id="precio">
                    </div>
                    <div class="form-group">
                    <label for="duracion">Duracion</label>
                    <select name="duracion" class="form-control">
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
                                                        $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario 
                                                        INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario='$id_user'");
                                                        while($row = mysqli_fetch_array($rs))
                                                        {
                                                            $sede=$row['nombre'];
                                                        
                                                        }
                                                        if($sede=="GENERAL"){

                                                            $query = mysqli_query($conexion, "SELECT nombre FROM sedes");
                                                        }else{

                                                            $query = mysqli_query($conexion, "SELECT nombre FROM sedes where sedes.nombre='$sede'");
                                                        }

                                                    
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
                     <input type="submit" value="Guardar Curso" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>