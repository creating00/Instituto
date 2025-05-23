<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $sede = $_POST['sedes'];
        $rol = $_POST['roles'];
        $query = mysqli_query($conexion, "SELECT * FROM usuario where correo = '$email'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El correo ya existe
                    </div>';
        } else {
            //traer id de sede
            $result2 = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
            $result = mysqli_num_rows($result2);

            while ($row2 = mysqli_fetch_array($result2)) {
                $idsedes = $row2['idsede'];
            }

            $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave,idsede,idrol) values ('$nombre', '$email', '$user', '$clave', '$idsedes', '$rol')");
            if ($query_insert) {
                $alert = '<div class="alert alert-primary" role="alert">
                            Usuario registrado
                        </div>';
                header("Location: usuarios.php");
                $alert = '<div class="alert alert-primary" role="alert">
                            Usuario registrado
                        </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        Error al registrar
                    </div>';
            }
        }
    }
}
?>
<div class="container-fluid">
    <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="modal" data-target="#nuevo_usuario">Nuevo Usuario <i class="fas fa-plus"></i></button>
</div>
<!--<a class="nav-link" href="gestionCobros.php">
<div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true"></i> Gestion Cobranzas Usuarios</div>
</a>-->

<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Usuario</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" id="usuario">
                    </div>
                    <div class="form-group">
                        <label for="clave">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Ingrese Contraseña" name="clave" id="clave">
                    </div>
                    <div class="form-group" hidden>
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

                    <div class="form-group">
                        <label for="roles">Rol</label>
                        <select name="roles" class="form-control">
                            <?php
                            // Incluir la conexión a la base de datos
                            include "../conexion.php";

                            // Consultar los roles disponibles
                            $query = mysqli_query($conexion, "SELECT idrol, nombrerol FROM roles");

                            // Verificar si hay resultados
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                // Recorrer los resultados y generar las opciones del select
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $idrol = $row['idrol'];
                                    $nombrerol = strtoupper($row['nombrerol']);
                            ?>
                                    <option value="<?php echo $idrol; ?>"><?php echo $nombrerol; ?></option>
                                <?php
                                }
                            } else {
                                // Si no hay roles, mostrar un mensaje vacío o predeterminado
                                ?>
                                <option value="">No hay roles disponibles</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" value="Registrar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="table-table-table-table-responsive-lg">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>

                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th hidden>Sede</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php ?>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT idusuario, usuario.nombre, correo, usuario, sedes.nombre'sede', usuario.estado FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede ORDER BY estado DESC");
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

                        <?php $data['idusuario'] ?>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['correo']; ?></td>
                        <td><?php echo $data['usuario']; ?></td>
                        <td hidden><?php echo $data['sede']; ?></td>
                        <td><?php echo $estado; ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="rol.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-warning"><i class='fas fa-key'></i></a>
                                <a href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } else {
                                echo "<a href='alta_usuario.php?id=" . $data['idusuario'] . "'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
                            } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>