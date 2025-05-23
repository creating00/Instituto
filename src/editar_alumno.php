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
    $alert = "";
    if (empty($_POST['dni']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['tutor'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idalumno = $_POST['id'];
        $dni = $_POST['dni'];
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $ape = $_POST['apellido'];
        $apellido = strtoupper($ape);
        $direccion = $_POST['direccion'];
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        $tutor = $_POST['tutor'];
        $contacto = $_POST['contacto'];
        $sede = $_POST['sede'];
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
        while ($row = mysqli_fetch_array($rs)) {
            $idsede = $row['idsede'];
        }

        $sql_update = mysqli_query($conexion, "UPDATE alumno SET dni='$dni', nombre='$nombre', apellido='$apellido', direccion='$direccion', celular='$celular', email='$email', tutor='$tutor', contacto='$contacto', idsede='$idsede' WHERE idalumno='$idalumno'");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Estudiante Actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Estudiante</div>';
        }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: alumno.php");
}
$idalumno = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno = $idalumno");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: alumno.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        $dni = $data['dni'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $direccion = $data['direccion'];
        $celular = $data['celular'];
        $email = $data['email'];
        $tutor = $data['tutor'];
        $contacto = $data['contacto'];
        //$sede = $sede['sede'];

    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Estudiante
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>

                        <input type="hidden" name="id" value="<?php echo $idalumno; ?>">
                        <div class="form-group">
                            <label for="cantidad">CÉDULA</label>
                            <input type="number" placeholder="Ingrese CÉDULA" class="form-control" name="dni" id="dni" class="form-control" value="<?php echo $data['dni']; ?>">
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
                            <input type="number" placeholder="Ingrese cantidad" class="form-control celular-input" name="celular" id="celular" class="form-control" value="<?php echo $data['celular']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email" class="form-control" value="<?php echo $data['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="tutor">Tutor</label>
                            <input type="text" class="form-control" placeholder="Ingrese Nombre" name="tutor" id="tutor" class="form-control" value="<?php echo $data['tutor']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="contacto">Celular Contacto</label>
                            <input type="numer" placeholder="Ingrese Contacto" class="form-control celular-input" name="contacto" id="contacto" class="form-control" value="<?php echo $data['contacto']; ?>">
                        </div>
                        <div class="form-group" hidden>
                            <label for="sede">Sede</label>
                            <select name="sede" class="form-control">
                                <?php
                                //traer sedes
                                include "../conexion.php";
                                $query = mysqli_query($conexion, "SELECT nombre FROM sedes");
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
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Estudiante</button>
                        <a href="alumno.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
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
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>