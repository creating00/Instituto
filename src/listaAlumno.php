<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "alumnos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
    if (!empty($_POST)) {
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
		$direccion = $_POST['direccion'];
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        $tutor = $_POST['tutor'];
        $contacto = $_POST['contacto'];
        $sede1 = $_POST['sedes'];
        $sede1 = $_POST['sedes'];
       $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            } 
        //$usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($dni) || empty($nombre) || empty($apellido) || empty($tutor) || empty($contacto) ) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM alumno WHERE dni = '$dni'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El Alumno ya existe
                    </div>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO alumno(dni, nombre, apellido, direccion, celular, email, tutor, contacto, idsede, estado) 
                VALUES('$dni', '$nombre', '$apellido', '$direccion', '$celular', '$email', '$tutor', '$contacto', '$idsede',1)");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Alumno Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el Alumno
              </div>';
                }
            }
        }
    }
    ?>

 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 <th>CÉDULA</th>
                 <th>Nombre</th>
                 <th>Apellido</th>
                 
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
                if($sede=="general"){

                
                $query = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
                INNER JOIN sedes on alumno.idsede=sedes.idsede");
                }else{
                  
                $query = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
                INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE sedes.nombre='$sede'");    

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
                         <td><?php echo $data['dni']; ?></td>
                         <td><?php echo $data['nombre']; ?></td>
                         <td><?php echo $data['apellido']; ?></td>
                         
                         <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 
                                
                                 <a href="editar_alumno.php?id=<?php echo $data['idalumno']; ?>" class="btn btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i></a>

                                 
                                     
                                 </form>
                             <?php } ?>
                         </td>
                     
                     </tr>
             <?php }
                } ?>
               
         </tbody>

     </table>
 </div>
 <?php include_once "includes/footer.php"; ?>