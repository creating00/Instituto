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
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $celular = $_POST['celular'];
    $email = $_POST['email'];
    $sede1 = $_POST['sedes'];

    $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
    while ($row = mysqli_fetch_array($rs)) {
        $idsede = $row['idsede'];
    }
    //$usuario_id = $_SESSION['idUser'];
    $alert = "";
    if (empty($dni) || empty($nombre) || empty($apellido)) {
        $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM profesor WHERE dni = '$dni'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El Profesor ya existe
                    </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO profesor(dni, nombre, apellido, direccion, celular, email, idsede, estado) 
                VALUES('$dni', '$nombre', '$apellido', '$direccion', '$celular', '$email','$idsede',1)");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                Profesor Registrado
              </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el Profesor
              </div>';
            }
        }
    }
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_profesor"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <!--<th>#</th>-->
                <th>CÉDULA</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Direccion</th>
                <th>Celular</th>
                <th>Email</th>
                <th hidden>Sede</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";
            $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario 
                INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario='$id_user'");
            while ($row = mysqli_fetch_array($rs)) {
                $sede = $row['nombre'];
            }
            if ($sede == "GENERAL") {
                $query = mysqli_query($conexion, "SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
                INNER JOIN sedes on profesor.idsede=sedes.idsede");
            } else {

                $query = mysqli_query($conexion, "SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
                    INNER JOIN sedes on profesor.idsede=sedes.idsede WHERE sedes.nombre='$sede'");
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
                        <td hidden><?php echo $data['sede']; ?></td>
                        <td><?php echo $estado ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>


                                <a href="editar_profesor.php?id=<?php echo $data['idprofesor']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_profesor.php?id=<?php echo $data['idprofesor']; ?>" method="post" class="confirmar d-inline">

                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } else {

                                echo "<a href='alta_profesor.php?id=" . $data['idprofesor'] . "'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
                            } ?>
                        </td>

                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_profesor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">NuevoProfesor</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="cantidad">CÉDULA</label>
                        <input type="number" placeholder="Ingrese CÉDULA" class="form-control" name="dni" id="dni">
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
                        <input type="number" placeholder="Ingrese celular" class="form-control celular-input" name="celular" id="celular">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="sedes">Sede</label>
                        <select name="sedes" class="form-control">
                            <?php
                            //traer sedes
                            include "../conexion.php";
                            $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario 
                                                        INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario='$id_user'");
                            while ($row = mysqli_fetch_array($rs)) {
                                $sede = $row['nombre'];
                            }
                            if ($sede == "GENERAL") {

                                $query = mysqli_query($conexion, "SELECT nombre FROM sedes");
                            } else {

                                $query = mysqli_query($conexion, "SELECT nombre FROM sedes where sedes.nombre='$sede'");
                            }


                            $result = mysqli_num_rows($query);

                            while ($row = mysqli_fetch_assoc($query)) {
                                //$idrol = $row['idrol'];
                                $prov = $row['nombre'];

                            ?>

                                <option values="<?php echo $prov; ?>"><?php echo $prov; ?></option>

                            <?php
                            }

                            ?>
                        </select>
                    </div>
                    <input type="submit" value="Guardar Profesor" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validarCelular(input) {
        // Asegura que solo se ingresen números
        input.value = input.value.replace(/[^0-9]/g, '');

        // Limita la entrada a 10 dígitos
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10);
        }
    }

    // Aplicar la validación a todos los inputs con la clase "celular-input"
    document.querySelectorAll('.celular-input').forEach(function(input) {
        input.addEventListener('input', function() {
            validarCelular(this);
        });
    });
</script>

<?php include_once "includes/footer.php"; ?>