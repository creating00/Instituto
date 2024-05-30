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
        
        $dni =  $_POST['dni'];
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);

        $ape = $_POST['apellido'];
        $apellido = strtoupper($ape);
        
		$direccion = $_POST['direccion'];
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        $tutor = $_POST['tutor'];
        $contacto = $_POST['contacto'];
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
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_alumno"><i class="fas fa-plus"></i></button>
 <button class="btn btn-danger mb-2" type="button" data-toggle="modal" data-target="#bajas"><i class="fas fa-minus"></i></button>
 <!--<button type="button" class="btn btn-outline-info"  data-toggle="modal" data-target="#correos">Enviar Correos</button>-->

 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 <th>DNI</th>
                 <th>Nombre</th>
                 <th>Apellido</th>
                 <th>Direccion</th>
                 <th>Celular</th>
                 <th>Email</th>
                 <th>Tutor</th>
                 <th>Contacto</th>
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
                         <td><?php echo $data['direccion']; ?></td>
                         <td><?php echo $data['celular']; ?></td>
                         <td><?php echo $data['email']; ?></td>
                         <td><?php echo $data['tutor']; ?></td>
                         <td><?php echo $data['contacto']; ?></td>
                         <td><?php echo $data['sede']; ?></td>
                         <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 
                                <!--<a href="rol.php?id=<//?php echo $data['idusuario']; ?>" class="btn btn-warning"><i class='fa fa-address-card'></i></a> -->
                                 <a href="editar_alumno.php?id=<?php echo $data['idalumno']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_alumno.php?id=<?php echo $data['idalumno']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             <?php } ?>
                         </td>
                     
                     </tr>
             <?php }
                } ?>
               
         </tbody>

     </table>
 </div>

<!-- Modal Alumnos dados de baja -->
<div class="modal" tabindex="-1" id="bajas">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-user-plus" aria-hidden="true"></i>Alumnos dados de Bajas</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
      </div>
      <div class="modal-body">
        <p>Listado Completo.</p>
        <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <!--<th>#</th>-->
                 <th>DNI</th>
                 <th>Nombre</th>
                 <th>Apellido</th>
                 <th>Direccion</th>
                 <th>Celular</th>
                 <th>Email</th>
                 <th>Tutor</th>
                 <th>Contacto</th>
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

                
                $query = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
                INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.estado='Inactivo'");
                }else{
                  
                $query = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
                INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE sedes.nombre='$sede' and alumno.estado='Inactivo'");    

                }
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
        
                ?>
                     <tr>
                         <?php echo $data['idalumno']; ?>
                         <td><?php echo $data['dni']; ?></td>
                         <td><?php echo $data['nombre']; ?></td>
                         <td><?php echo $data['apellido']; ?></td>
                         <td><?php echo $data['direccion']; ?></td>
                         <td><?php echo $data['celular']; ?></td>
                         <td><?php echo $data['email']; ?></td>
                         <td><?php echo $data['tutor']; ?></td>
                         <td><?php echo $data['contacto']; ?></td>
                         <td><?php echo $data['sede']; ?></td>
                         <td><?php echo $estado ?></td>
                         <td>
                             
                                 

                         <a href="alta_alumno.php?id=<?php echo $data['idalumno']; ?>" class="btn btn-warning"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
                         <form action="esterminar_alumno.php?id=<?php echo $data['idalumno']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             <?php } ?>
                         </td>
                     
                     </tr>
             <?php }
                 ?>
               
         </tbody>

     </table>
 </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



 <div id="nuevo_alumno" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">NuevoAlumno</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="cantidad">DNI</label>
                         <input type="number" placeholder="Ingrese DNI" class="form-control" name="dni" id="dni">
                     </div>
                     <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" placeholder="Ingrese Apellido" name="apellido" id="apellido">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion">
                    </div>
                    <div class="form-group">
                         <label for="celular">Celular</label>
                         <input type="number" placeholder="Ingrese celular" class="form-control" name="celular" id="celular">
                     </div>
                     <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email">
                    </div>
                     <div class="form-group">
                        <label for="tutor">Tutor</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="tutor" id="tutor">
                    </div>
                    <div class="form-group">
                         <label for="contacto">Celular Contacto</label>
                         <input type="numer" placeholder="Ingrese Contacto" class="form-control" name="contacto" id="contacto">
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
                     <input type="submit" value="Guardar Alumno" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal Correos -->
 <div class="modal" tabindex="-1" id="correos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar Correos Sistema Eduser.</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p> Correo.</p>
        <form id="form1" class="well col-lg-12" action="enviar.php" method="post" name="form1" enctype="multipart/form-data">
        <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Operador @</span>
        <input type="text" id="Nombre" name="Nombre" value="<?php echo $_SESSION['nombre']; ?>"  placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" style="width:44%;">
        </div>
        <div class="input-group mb-3">
        <input type="text" id="Email" name="Email" style="width:40%;" placeholder="Email del alumno" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <span class="input-group-text" id="basic-addon2">@example.com</span>
        </div>
        <div class="input-group">
        <span class="input-group-text">Mensaje</span>
        <textarea id="Mensaje" name="Mensaje" style="width:70%; height:80px;" aria-label="With textarea"></textarea>
        </div><br>
        <div class="input-group">
        <input type="file" name="adjunto" id="archivo-adjunto">
        </div><br>

        <button type="submit" class="btn btn-primary">Enviar Correo</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
      </form>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
    
 <?php include_once "includes/footer.php"; ?>
