<?php include_once "includes/header.php";
include "../conexion.php";
include_once "CursoBusqueda.php";
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'registrarCurso') {
        $nom = $_POST['nombre'];
        $nombre = strtoupper($nom);  // Convertir a mayúsculas
        $precio = $_POST['precio'];
        $duracion = $_POST['duracion'];
        $dias = isset($_POST['dias']) && $_POST['dias'] !== '' ? intval($_POST['dias']) : null;
        $sede1 = $_POST['sedes'];
        $inscripcion = isset($_POST['inscripcion']) && $_POST['inscripcion'] !== '' ? floatval($_POST['inscripcion']) : 0;  // Asegurar que inscripcion es un número, por si está vacío
        $idprofesor = $_POST['idProfesor'];
        $monto = isset($_POST['montoAPagar']) && $_POST['montoAPagar'] !== '' ? floatval($_POST['montoAPagar']) : null;

        $fechaComienzo = $_POST['comienzo'];  // Fecha de Inicio
        $fechaTermino = $_POST['termino'];    // Fecha de Término
        $mora = isset($_POST['mora']) && is_numeric($_POST['mora']) ? $_POST['mora'] : null;
        $diasRecordatorio = isset($_POST['diasRecordatorio']) && $_POST['diasRecordatorio'] !== '' ? intval($_POST['diasRecordatorio']) : null;  // Día de Recordatorio

        $horarioDesde = $_POST['horarioDesde'];
        $horarioHasta = $_POST['horarioHasta'];

        // Convertir las fechas a formato adecuado (YYYY-MM-DD)
        $fechaComienzo = date('Y-m-d', strtotime($fechaComienzo));
        $fechaTermino = date('Y-m-d', strtotime($fechaTermino));

        // Obtener ID de la sede
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede1'");
        $idsede = null;
        while ($row = mysqli_fetch_array($rs)) {
            $idsede = $row['idsede'];
        }

        $alert = "";
        if (empty($nombre) || empty($precio) || empty($duracion)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';
        } else {
            // Verificar si el curso ya existe
            $query = mysqli_query($conexion, "SELECT * FROM curso WHERE nombre = '$nombre' AND idsede = '$idsede'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El Curso ya existe
                    </div>';
            } else {
                // Asignar el tipo según la duración
                if ($duracion >= 1 && $duracion <= 12) {
                    $tipo = "$duracion meses";
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Duración no válida
              </div>';
                    exit;
                }

                // Crear instancia de la clase y registrar el curso
                $cursoBusqueda = new CursoBusqueda($conexion);
                $resultado = $cursoBusqueda->registrarCurso(
                    $nombre,
                    $precio,
                    $duracion,
                    1,
                    $tipo,
                    $idsede,
                    $dias,
                    $inscripcion,
                    $idprofesor,
                    $monto,
                    $fechaComienzo,
                    $fechaTermino,
                    $mora,
                    $diasRecordatorio,
                    $horarioDesde,
                    $horarioHasta
                );

                if ($resultado === "Curso registrado exitosamente.") {
                    $alert = '<div class="alert alert-success" role="alert">Curso Registrado</div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">' . $resultado . '</div>';
                }
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

                $query = mysqli_query($conexion, "SELECT idcurso, curso.nombre, precio, duracion, tipo, sedes.nombre'sede', curso.estado FROM curso 
                     INNER JOIN sedes on curso.idsede=sedes.idsede");
            } else {

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
                        <td><?php echo '$' . number_format($data['precio'], 2, '.', ','); ?></td>
                        <td><?php echo $data['tipo']; ?></td>
                        <td hidden><?php echo $data['sede']; ?></td>
                        <td><?php echo $estado ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <!-- Botón Editar -->
                                <a href="editar_curso.php?id=<?php echo $data['idcurso']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <!-- Botón para invocar el modal de fecha -->
                                <button type="button" class="btn btn-calendario" onclick="abrirModalFecha('<?php echo $data['idcurso']; ?>')">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </button>
                                <!-- Botón Eliminar -->
                                <form action="eliminar_curso.php?id=<?php echo $data['idcurso']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } else {

                                echo "<a href='alta_curso.php?id=" . $data['idcurso'] . "'class='btn btn-warning'><i class='fa fa-user-plus' aria-hidden='true'></i></a>";
                            } ?>
                        </td>

                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
    <style>
        .btn-calendario {
            background-color: #5C6BC0;
            border-color: #5C6BC0;
            color: white;
        }

        .btn-calendario:hover {
            background-color: #3949AB;
            /* Un tono más oscuro */
            border-color: #3949AB;
            color: white;
        }
    </style>
</div>
<div id="nuevo_curso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Registrar Nuevo Curso</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body">
                <form action="curso.php" method="POST" autocomplete="off">
                    <input type="hidden" name="action" value="registrarCurso">
                    <!-- Alertas -->
                    <?php echo isset($alert) ? $alert : ''; ?>

                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="nombre">Curso</label>
                                <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                            </div>

                            <!-- Precio -->
                            <div class="form-group">
                                <label for="precio">Precio $</label>
                                <input type="number" class="form-control" placeholder="Ingrese el Precio" name="precio" id="precio">
                            </div>

                            <!-- Fecha de Inicio -->
                            <div class="form-group">
                                <label for="fechaComienzo"><i aria-hidden="true"></i> Fecha de Inicio</label>
                                <input type="date" id="fechaComienzo" name="comienzo" class="form-control" onchange="calcularFechaTermino()">
                            </div>

                            <!-- Duración -->
                            <div class="form-group">
                                <label for="duracion">Duración</label>
                                <select name="duracion" class="form-control" id="duracion" onchange="calcularFechaTermino()">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo "$i Mes" . ($i > 1 ? "es" : ""); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Fecha de Termino -->
                            <div class="form-group">
                                <label for="fechaTermino"><i aria-hidden="true"></i> Fecha de Termino</label>
                                <input type="date" id="fechaTermino" name="termino" class="form-control">
                            </div>

                            <!-- Sedes -->
                            <div class="form-group" hidden>
                                <label for="sedes">Sede</label>
                                <select name="sedes" class="form-control">
                                    <?php
                                    include "../conexion.php";
                                    $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario 
                                INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario='$id_user'");
                                    $sede = mysqli_fetch_array($rs)['nombre'];

                                    $query = $sede == "GENERAL"
                                        ? mysqli_query($conexion, "SELECT nombre FROM sedes")
                                        : mysqli_query($conexion, "SELECT nombre FROM sedes WHERE sedes.nombre='$sede'");

                                    while ($row = mysqli_fetch_assoc($query)): ?>
                                        <option value="<?php echo $row['nombre']; ?>">
                                            <?php echo $row['nombre']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <!-- Días de Pago -->
                            <div class="form-group">
                                <label for="dias">Día de Pago (opcional)</label>
                                <input type="number" class="form-control" placeholder="Ingrese los días" name="dias" id="dias">
                            </div>

                            <!-- Días de Recordatorio -->
                            <div class="form-group">
                                <label for="diasRecordatorio">Día de Recordatorio (opcional)</label>
                                <input type="number" class="form-control" placeholder="Ingrese los días de recordatorio" name="diasRecordatorio" id="diasRecordatorio">
                            </div>

                            <!-- Mora (Demora) -->
                            <div class="form-group">
                                <label for="mora"><i aria-hidden="true"></i> Mora $</label>
                                <input type="number" class="form-control" placeholder="Ingrese los días de mora" name="mora" id="mora">
                            </div>

                            <!-- Horario -->
                            <div class="form-group">
                                <label for="horarioDesde">Horario (Desde)</label>
                                <input type="time" id="horarioDesde" name="horarioDesde" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="horarioHasta">Horario (Hasta)</label>
                                <input type="time" id="horarioHasta" name="horarioHasta" class="form-control">
                            </div>

                            <!-- Inscripción -->
                            <div class="form-group">
                                <label for="inscripcion">Costo de Inscripción $</label>
                                <input type="number" class="form-control" placeholder="Ingrese el costo de inscripción" name="inscripcion" id="inscripcion">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Botón Buscar Profesor -->
                        <div class="form-group ml-4">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalProfesor">
                                <i class="fa fa-users" aria-hidden="true"></i> Buscar Profesor
                            </button>
                        </div>

                        <!-- CÉDULA, Nombre y Monto (Hidden) -->
                        <div class="row ml-3">
                            <div class="col-md-6">
                                <!-- CÉDULA Input y Label -->
                                <div class="form-group">
                                    <label for="dni" class="d-none">CÉDULA</label>
                                    <input type="text" name="dni" id="dni" class="form-control d-none" readonly>
                                </div>

                                <!-- Nombre del Profesor Input y Label -->
                                <div class="form-group">
                                    <label for="nombreProfesor" class="d-none">Nombre del Profesor</label>
                                    <input type="text" name="nombreProfesor" id="nombreProfesor" class="form-control d-none" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Monto a Pagar Input y Label -->
                                <div class="form-group">
                                    <label for="montoAPagar" class="d-none">Monto a Pagar</label>
                                    <input type="text" name="montoAPagar" id="montoAPagar" class="form-control d-none">
                                </div>

                                <!-- ID del Profesor Input y Label -->
                                <div class="form-group">
                                    <label for="idProfesor" class="d-none">ID del Profesor</label>
                                    <input type="text" name="idProfesor" id="idProfesor" class="form-control d-none"> <!-- Campo oculto para el ID -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón fuera del scroll -->
                    <div class="modal-footer">
                        <input type="submit" value="Guardar Curso" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .modal-body {
            max-height: 400px;
            /* Ajusta este valor según sea necesario */
            overflow-y: auto;
            /* Añade scroll cuando el contenido sobrepase la altura máxima */
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: #fff;
            /* Para evitar que el fondo del modal se vea debajo del botón */
        }
    </style>
</div>

<div id="fechaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Seleccionar Fecha de Término</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <!-- Fecha de Término Actual -->
                            <div class="form-group">
                                <label for="fechaTerminoActual"><i aria-hidden="true"></i> Fecha de Término Actual</label>
                                <input type="date" id="fechaTerminoActual" name="fechaTerminoActual" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" id="idCurso" name="idCurso">
                        <input type="hidden" id="fechaComienzoHidden" name="fechaComienzoHidden">

                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <!-- Fecha de Término Extendida -->
                            <div class="form-group">
                                <label for="fechaTerminoExtendida"><i aria-hidden="true"></i> Fecha de Término Extendida</label>
                                <input type="date" id="fechaTerminoExtendida" name="fechaTerminoExtendida" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Guardar -->
                    <div class="modal-footer">
                        <input type="submit" value="Guardar Fecha" class="btn btn-primary" id="guardarFecha">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/profesor_modal.js"></script>
<script>
    // scripts.js

    $(document).ready(function() {
        $('#nuevo_curso').on('shown.bs.modal', function() {
            $('.modal-backdrop').remove();
        });

        $('#modalProfesor').on('show.bs.modal', function() {
            $('#modalProfesor').css('z-index', '1050');
            $('#nuevo_curso').css('z-index', '1040');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade show"></div>').appendTo('body');
        });

        $('#nuevo_curso').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
        });

        $('#modalProfesor').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
        });
    });
</script>
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

    function abrirModalFecha(idcurso) {
        // Hacemos la consulta AJAX al servidor para obtener la fecha de término y fecha de comienzo
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'obtener_fecha_termino.php?idcurso=' + idcurso, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // La respuesta contiene la fecha de término y fecha de comienzo
                var response = JSON.parse(xhr.responseText);
                console.log(response);
                console.log("fechaComienzo: ", response.fechaComienzo);

                // Establecemos la fecha de término en el campo correspondiente en el modal
                document.getElementById('fechaTerminoActual').value = response.fechaTermino;
                document.getElementById('fechaComienzoHidden').value = response.fechaComienzo;
                document.getElementById('idCurso').value = response.idcurso;
            }
        };

        // Enviamos la solicitud al servidor
        xhr.send();

        // Abrimos el modal
        $('#fechaModal').modal('show');
    }

    // Guardar en la tabla Curso
    $('#guardarFecha').on('click', function() {
        event.preventDefault();
        var idCurso = $('#idCurso').val();
        var fechaTermino = $('#fechaTerminoExtendida').val();
        var fechaComienzo = $('#fechaComienzoHidden').val(); // Asegúrate de tener este valor
        var fechaTerminoActual = $('#fechaTerminoActual').val(); // Aquí obtenemos la fecha actual (que ya debe estar en el modal)

        console.log('idCurso:', idCurso);
        console.log('fechaTermino:', fechaTermino);
        console.log('fechaComienzo:', fechaComienzo);
        console.log('fechaTerminoActual:', fechaTerminoActual);

        if (fechaTermino && fechaComienzo) {
            // Comprobamos si fechaTerminoExtendida es mayor o igual a fechaTerminoActual
            if (new Date(fechaTermino) < new Date(fechaTerminoActual)) {
                alert('La fecha de término extendida no puede ser menor que la fecha de término actual');
                return; // Detenemos la ejecución si la condición no se cumple
            }

            $.ajax({
                url: 'update_fecha.php', // Página PHP que manejará ambos UPDATE
                method: 'POST',
                data: {
                    idCurso: idCurso,
                    fechaTermino: fechaTermino,
                    fechaComienzo: fechaComienzo
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response); // Procesa la respuesta JSON
                        console.log(data);

                        if (data.success) {
                            //alert('Actualización exitosa:\n' + data.messages.join('\n'));
                            $('#fechaModal').modal('hide');
                        } else {
                            //alert('Hubo errores:\n' + data.messages.join('\n'));
                            console.error('Error:', data.error);
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr, status, error);
                    //alert('No se pudo completar la solicitud. Intenta nuevamente.');
                }
            });
        } else {
            alert('Por favor, ingrese ambas fechas');
        }
    });
</script>
<?php include_once "includes/footer.php"; ?>