<?php include_once "includes/header.php";
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idusuario = $_GET['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $sede = $_POST['sede'];
        $roles = $_POST['roles'];

        $stmt_sede = $conexion->prepare("SELECT idsede FROM sedes WHERE nombre = ?");
        $stmt_sede->bind_param("s", $sede);
        $stmt_sede->execute();
        $resultado_sede = $stmt_sede->get_result();

        if ($row = $resultado_sede->fetch_assoc()) {
            $idsede = $row['idsede'];

            $stmt_update = $conexion->prepare("UPDATE usuario SET nombre = ?, correo = ?, usuario = ?, idsede = ?, idrol = ? WHERE idusuario = ?");
            $stmt_update->bind_param("sssiii", $nombre, $correo, $usuario, $idsede, $roles, $idusuario);
            $stmt_update->execute();

            $alert = '<div class="alert alert-success" role="alert">Usuario Actualizado</div>';
        }
    }
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: usuarios.php");
}
$idusuario = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idusuario");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: usuarios.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['idusuario'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $usuario = $data['usuario'];
        $id_rol = $data['idrol'];
    }
}
?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Modificar Usuario
            </div>
            <div class="card-body">
                <form class="" action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <input type="hidden" name="id" value="<?php echo $idusuario; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" placeholder="Ingrese nombre" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">

                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="text" placeholder="Ingrese correo" class="form-control" name="correo" id="correo" value="<?php echo $correo; ?>">

                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" placeholder="Ingrese usuario" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario; ?>">

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

                    <div class="form-group">
                        <label for="roles">Rol</label>
                        <select name="roles" class="form-control" id="roles">
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT idrol, nombrerol FROM roles");
                            $result = mysqli_num_rows($query);

                            if ($result > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $idrol = $row['idrol'];
                                    $nombrerol = strtoupper($row['nombrerol']);
                                    $selected = ($idrol == $id_rol) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo $idrol; ?>" <?php echo $selected; ?>>
                                        <?php echo $nombrerol; ?>
                                    </option>
                                <?php
                                }
                            } else {
                                ?>
                                <option value="">No hay roles disponibles</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Modificar</button>
                    <a href="usuarios.php" class="btn btn-danger">Atras</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>