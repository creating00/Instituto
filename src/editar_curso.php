<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "curso";

if ($permiso === "curso") {
    include_once 'modal_profesor.php';
}

$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['duracion']) || empty($_POST['precio'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcurso = $_POST['id'];
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);
        $precio = $_POST['precio'];
        $duracion = $_POST['duracion'];
        $dias = isset($_POST['dias']) && $_POST['dias'] !== '' ? intval($_POST['dias']) : null;
        $inscripcion = isset($_POST['inscripcion']) && $_POST['inscripcion'] !== '' ? floatval($_POST['inscripcion']) : 0; // Manejo del costo de inscripción
        $sede1 = $_POST['sedes'];
        $idprofesor = $_POST['idProfesor'];
        $monto = $_POST['montoAPagar'];

        $fechaComienzo = isset($_POST['comienzo']) && $_POST['comienzo'] !== '' ? date('Y-m-d', strtotime($_POST['comienzo'])) : null;  // Fecha de Inicio
        $fechaTermino = isset($_POST['termino']) && $_POST['termino'] !== '' ? date('Y-m-d', strtotime($_POST['termino'])) : null;    // Fecha de Término

        $mora = isset($_POST['mora']) && is_numeric($_POST['mora']) ? $_POST['mora'] : null;
        $diasRecordatorio = isset($_POST['diasRecordatorio']) && $_POST['diasRecordatorio'] !== '' ? intval($_POST['diasRecordatorio']) : null;  // Día de Recordatorio

        $horarioDesde = $_POST['horarioDesde'];
        $horarioHasta = $_POST['horarioHasta'];

        // Convertir fechas a formato correcto
        $fechaComienzo = date('Y-m-d', strtotime($fechaComienzo));
        $fechaTermino = date('Y-m-d', strtotime($fechaTermino));

        // Obtener el ID de la sede
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
        while ($row = mysqli_fetch_array($rs)) {
            $idsede = $row['idsede'];
        }

        // Generar el texto descriptivo de la duración
        $tipo = ($duracion == 1) ? "1 mes" : "$duracion meses";

        // Actualizar el curso
        $sql_update = mysqli_query($conexion, "UPDATE curso 
        SET 
        nombre = '$nombre', 
        precio = '$precio', 
        duracion = '$duracion', 
        idsede = '$sede1', 
        dias = " . ($dias !== null ? $dias : "NULL") . ", 
        inscripcion = " . ($inscripcion !== null ? "'$inscripcion'" : "NULL") . ",  
        idprofesor = " . ($idprofesor !== null ? "'$idprofesor'" : "NULL") . ", 
        monto = " . ($monto !== null ? "'$monto'" : "NULL") . ", 
        fechaComienzo = '$fechaComienzo', 
        fechaTermino = '$fechaTermino', 
        mora = " . ($mora !== null ? "'$mora'" : "NULL") . ", 
        diasRecordatorio = " . ($diasRecordatorio !== null ? $diasRecordatorio : "NULL") . ", 
        horarioDesde = '$horarioDesde', 
        horarioHasta = '$horarioHasta' 
        WHERE idcurso = '$idcurso'");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Curso actualizado exitosamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el curso: ' . mysqli_error($conexion) . '</div>';
        }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: curso.php");
}
$idcurso = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM curso WHERE idcurso = $idcurso");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: curso.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        //$idalumno = $data['idalumno'];
        $nombre = $data['nombre'];
        $precio = $data['precio'];
        $duracion = $data['duracion'];
        $dias = $data['dias'];
        $inscripcion = $data['inscripcion'];
        $idprofesor = $data['idprofesor'];
        $monto = $data['monto'];
        $sede = $data['idsede'];
    }
}
// Consulta para obtener el profesor relacionado con el curso
// Verificar si $idprofesor es un valor válido y numérico
if (isset($idprofesor) && is_numeric($idprofesor) && $idprofesor > 0) {
    $query_profesor = mysqli_query($conexion, "
        SELECT idprofesor, nombre, apellido, dni  
        FROM profesor 
        WHERE idprofesor = $idprofesor
    ");

    if (mysqli_num_rows($query_profesor) > 0) {
        $profesor = mysqli_fetch_assoc($query_profesor);

        // Validamos y asignamos los valores
        $nombreProfesor = !empty($profesor['nombre']) ? $profesor['nombre'] : 'Nombre no disponible';
        $apellido = !empty($profesor['apellido']) ? $profesor['apellido'] : 'Apellido no disponible';
        $dni = !empty($profesor['dni']) ? $profesor['dni'] : 'CÉDULA no disponible';
        $idProfesor = !empty($profesor['idprofesor']) ? $profesor['idprofesor'] : 0;  // Asegúrate de que idProfesor sea un valor válido
    } else {
        // Si no se encuentra el profesor, asigna valores predeterminados o muestra un mensaje de error
        $nombreProfesor = '';
        $apellido = '';
        $dni = '';
        $idProfesor = '';
    }
} else {
    // Si el ID no es válido, puedes asignar valores predeterminados o mostrar un error
    $nombreProfesor = '';
    $apellido = '';
    $dni = '';
    $idProfesor = '';
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Curso
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <!-- Alertas -->
                        <?php echo isset($alert) ? $alert : ''; ?>

                        <!-- ID del curso -->
                        <input type="hidden" name="id" value="<?php echo $idcurso; ?>">

                        <div class="row">
                            <!-- Columna 1 -->
                            <div class="col-md-6">
                                <!-- Nombre del curso -->
                                <div class="form-group">
                                    <label for="nombre">Curso</label>
                                    <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre" value="<?php echo isset($data['nombre']) ? $data['nombre'] : ''; ?>">
                                </div>

                                <!-- Precio -->
                                <div class="form-group">
                                    <label for="precio">Precio $</label>
                                    <input type="number" class="form-control" placeholder="Ingrese Precio" name="precio" id="precio" value="<?php echo isset($data['precio']) ? $data['precio'] : ''; ?>">
                                </div>
                                <!-- Fecha de Inicio -->
                                <div class="form-group">
                                    <label for="fechaComienzo"><i aria-hidden="true"></i> Fecha de Inicio</label>
                                    <input type="date" id="fechaComienzo" name="comienzo" class="form-control"
                                        value="<?php echo isset($data['fechaComienzo']) ? $data['fechaComienzo'] : ''; ?>"
                                        onchange="calcularFechaTermino()">
                                </div>

                                <!-- Duración -->
                                <div class="form-group">
                                    <label for="duracion">Duración</label>
                                    <select name="duracion" class="form-control" id="duracion" onchange="calcularFechaTermino()">
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php echo isset($data['duracion']) && $data['duracion'] == $i ? 'selected' : ''; ?>>
                                                <?php echo "$i Mes" . ($i > 1 ? "es" : ""); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <!-- Fecha de Termino -->
                                <div class="form-group">
                                    <label for="fechaTermino"><i aria-hidden="true"></i> Fecha de Termino</label>
                                    <input type="date" id="fechaTermino" name="termino" class="form-control"
                                        value="<?php echo isset($data['fechaTermino']) ? $data['fechaTermino'] : ''; ?>">
                                </div>

                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6">
                                <!-- Días de Pago -->
                                <div class="form-group">
                                    <label for="dias">Día de Pago (opcional)</label>
                                    <input type="number" class="form-control" placeholder="Ingrese los días" name="dias" id="dias" value="<?php echo isset($data['dias']) ? $data['dias'] : ''; ?>">
                                </div>

                                <!-- Días de Recordatorio -->
                                <div class="form-group">
                                    <label for="diasRecordatorio">Día de Recordatorio (opcional)</label>
                                    <input type="number" class="form-control" placeholder="Ingrese los días de recordatorio" name="diasRecordatorio" id="diasRecordatorio" value="<?php echo isset($data['diasRecordatorio']) ? $data['diasRecordatorio'] : ''; ?>">
                                </div>

                                <!-- Sede -->
                                <div class="form-group" hidden>
                                    <label for="sedes">Sede</label>
                                    <select class="form-control" name="sedes">
                                        <?php
                                        include "../conexion.php";
                                        $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
                                        while ($row = mysqli_fetch_assoc($query)): ?>
                                            <option value="<?php echo $row['idsede']; ?>" <?php echo isset($data['sedes']) && $data['sedes'] == $row['idsede'] ? 'selected' : ''; ?>>
                                                <?php echo $row['nombre']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Inscripción -->
                                <div class="form-group">
                                    <label for="inscripcion">Costo de Inscripción $</label>
                                    <input type="number" class="form-control" placeholder="Ingrese el costo de inscripción" name="inscripcion" id="inscripcion" value="<?php echo isset($data['inscripcion']) ? $data['inscripcion'] : ''; ?>">
                                </div>

                                <!-- Mora (Demora) -->
                                <div class="form-group">
                                    <label for="mora"><i aria-hidden="true"></i> Mora $</label>
                                    <input type="number" class="form-control" placeholder="Ingrese los días de mora" name="mora" id="mora" value="<?php echo isset($data['mora']) ? $data['mora'] : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Horarios -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="horarioDesde">Horario (Desde)</label>
                                <input type="time" id="horarioDesde" name="horarioDesde" class="form-control" value="<?php echo isset($data['horarioDesde']) ? $data['horarioDesde'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="horarioHasta">Horario (Hasta)</label>
                                <input type="time" id="horarioHasta" name="horarioHasta" class="form-control" value="<?php echo isset($data['horarioHasta']) ? $data['horarioHasta'] : ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Botón Buscar Profesor -->
                            <div class="form-group ml-3">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalProfesor">
                                    <i class="fa fa-users" aria-hidden="true"></i> Buscar Profesor
                                </button>
                            </div>
                        </div>

                        <!-- Información del Profesor (Hidden) -->
                        <div class="row">

                            <div class="col-md-6">
                                <!-- CÉDULA -->
                                <div class="form-group">
                                    <label for="dni" class="d-none">CÉDULA</label>
                                    <input type="text" name="dni" id="dni" class="form-control d-none" value="<?php echo isset($dni) ? $dni : ''; ?>" readonly>
                                </div>

                                <!-- Nombre del Profesor -->
                                <div class="form-group">
                                    <label for="nombreProfesor" class="d-none">Nombre del Profesor</label>
                                    <input type="text" name="nombreProfesor" id="nombreProfesor" class="form-control d-none" value="<?php echo isset($nombreProfesor) ? $nombreProfesor : ''; ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Monto a Pagar -->
                                <div class="form-group">
                                    <label for="montoAPagar" class="d-none">Monto a Pagar</label>
                                    <input type="text" name="montoAPagar" id="montoAPagar" class="form-control d-none" value="<?php echo isset($monto) ? $monto : ''; ?>">
                                </div>

                                <!-- ID del Profesor -->
                                <div class="form-group">
                                    <label for="idProfesor" class="d-none">ID del Profesor</label>
                                    <input type="text" name="idProfesor" id="idProfesor" class="form-control d-none" value="<?php echo isset($idProfesor) ? $idProfesor : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Curso</button>
                            <a href="curso.php" class="btn btn-danger">Atrás</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    function ShowSelected() {
        /* Para obtener el valor */
        var cod = document.getElementById("duracionCMB").value;
        $('#duracion').val(cod);
        //alert(cod);

    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener los elementos necesarios
        const idProfesorInput = document.getElementById("idProfesor");
        const montoAPagarInput = document.getElementById("montoAPagar");
        const dniInput = document.getElementById("dni");
        const nombreProfesorInput = document.getElementById("nombreProfesor");

        const dniLabel = dniInput.previousElementSibling;
        const nombreProfesorLabel = nombreProfesorInput.previousElementSibling;

        const idProfesorOriginal = "<?php echo isset($idprofesor) ? $idprofesor : ''; ?>"; // PHP: ID original, validado para evitar valores vacíos
        const montoOriginal = "<?php echo isset($monto) ? $monto : ''; ?>"; // PHP: Monto original, validado para evitar valores vacíos

        if (idProfesorInput && idProfesorInput.value !== "") {
            // Quitar la clase d-none de los inputs y sus labels
            dniInput.classList.remove("d-none");
            nombreProfesorInput.classList.remove("d-none");
            montoAPagarInput.classList.remove("d-none");

            dniLabel.classList.remove("d-none");
            nombreProfesorLabel.classList.remove("d-none");
            montoAPagarInput.previousElementSibling.classList.remove("d-none");
        }

        idProfesorInput.addEventListener('change', function() {
            if (idProfesorInput.value === '') {
                // Ocultar los campos si el ID está vacío
                dniInput.classList.add("d-none");
                nombreProfesorInput.classList.add("d-none");
                dniLabel.classList.add("d-none");
                nombreProfesorLabel.classList.add("d-none");
            } else {
                // Mostrar los campos si hay un ID
                dniInput.classList.remove("d-none");
                nombreProfesorInput.classList.remove("d-none");
                dniLabel.classList.remove("d-none");
                nombreProfesorLabel.classList.remove("d-none");
            }

            // Manejar el valor del monto
            if (idProfesorInput.value === idProfesorOriginal && idProfesorOriginal !== '') {
                // Si el ID sigue siendo el original y no es nulo, no limpiar el monto
                montoAPagarInput.value = montoOriginal;
            } else if (idProfesorInput.value !== '') {
                // Si el ID cambia a uno diferente, limpiar el monto
                montoAPagarInput.value = "";
            } else {
                // Si el ID es vacío, limpiar el monto
                montoAPagarInput.value = "";
            }
        });
    });
</script>
<script src="js/profesor_modal.js"></script>
<script>
    function calcularFechaTermino() {
        // Obtener los valores de duración y fecha de inicio
        var duracionMeses = parseInt(document.getElementById('duracion').value);
        var fechaInicio = document.getElementById('fechaComienzo').value;

        if (fechaInicio && !isNaN(duracionMeses)) {
            // Convertir la fecha de inicio a un objeto Date
            var fechaInicioObj = new Date(fechaInicio);

            // Calcular la fecha de término sumando los meses
            fechaInicioObj.setMonth(fechaInicioObj.getMonth() + duracionMeses);

            // Formatear la fecha en formato YYYY-MM-DD para el input de fecha
            var fechaTermino = fechaInicioObj.toISOString().split('T')[0];

            // Asignar la fecha calculada al campo de fecha de término
            document.getElementById('fechaTermino').value = fechaTermino;
        }
    }
</script>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>