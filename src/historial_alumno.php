<?php
include_once "includes/header.php";
require_once '../conexion.php'; // Incluye tu archivo de conexión
$id_user = $_SESSION['idUser'];
//buscar sede segun usuario
$rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
    $sede = $row['nombre'];
}
?>

<body>
    <header class="container mt-4">
        <h1 class="mb-4">Historial de Cursos del Estudiante</h1>
    </header>

    <main class="container">

        <!-- Formulario de datos del alumno -->
        <form class="mt-3">
            <!-- Sede -->
            <div class="row mb-4" style="display: none;">
                <div class="col">
                    <label for="sede"><i class="fa fa-university" aria-hidden="true"></i> SEDE</label>
                    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede; ?>" id="sede" readonly="readonly" class="form-control">
                </div>
            </div>
            <div class="col-6" style="display: none;">
                <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                <input
                    style="font-size: 16px; text-transform: uppercase; color: red;"
                    value="<?php echo $_SESSION['nombre']; ?>"
                    id="usuario"
                    readonly="readonly"
                    class="form-control">
            </div>

            <div class="container">
                <!-- Contenedor con margen para el botón y el label -->
                <div class="mb-3">
                    <!-- Botón para abrir el modal de búsqueda de alumnos -->
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#miModal">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i> Buscar Estudiante
                        </button>
                    </div>
                </div>

                <!-- Contenedor con margen para el label y el input -->
                <div class="mb-3">
                    <label class="font-weight-bold"><i class="fa fa-graduation-cap"></i> Estudiante</label>
                    <div class="row">
                        <div class="col" style="max-width: 150px;">
                            <input type="text" class="form-control" placeholder="CÉDULA" id="dni" onkeypress="buscar_datos_alumnos();">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text"
                                class="form-control"
                                id="nombre"
                                placeholder="Nombre"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text"
                                class="form-control"
                                id="apellido"
                                placeholder="Apellido"
                                readonly>
                        </div>
                    </div>
                </div>

                <!-- Contenedor desplegable -->
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Mostrar más detalles
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <!-- Datos adicionales -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion">Dirección</label>
                                            <input type="text"
                                                class="form-control"
                                                id="direccion"
                                                name="direccion"
                                                placeholder="Dirección"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="celular">Celular</label>
                                            <input type="tel"
                                                class="form-control"
                                                id="celular"
                                                name="celular"
                                                placeholder="Número de celular"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email"
                                                class="form-control"
                                                id="email"
                                                name="email"
                                                placeholder="Correo electrónico"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tutor">Tutor</label>
                                            <input type="text"
                                                class="form-control"
                                                id="tutor"
                                                name="tutor"
                                                placeholder="Nombre del tutor"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contacto">Celular Contacto</label>
                                            <input type="tel"
                                                class="form-control"
                                                id="contacto"
                                                name="contacto"
                                                placeholder="Número de contacto"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group" hidden>
                                            <label for="sedeAlumno">Sede</label>
                                            <input type="sedeAlumno"
                                                class="form-control"
                                                id="sedeAlumno"
                                                name="sedeAlumno"
                                                placeholder="Sede"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Campos ocultos -->
            <input type="hidden" id="alumno_id" value="">
            <input type="hidden" id="sede_id" value="">
        </form>

        <!-- Tabla de cursos -->
        <div class="table-responsive mt-4">
            <table id="tablaInscripcion" class="table table-hover">
                <thead>
                    <tr bgcolor="#F5DEB3">
                        <th scope="col" style="display: none;">Id</th>
                        <th scope="col">#</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Fecha Inscripción</th>
                        <th scope="col">Fecha de Inicio</th>
                        <th scope="col">Fecha de Termino</th>
                        <th scope="col">Pagos a Deber</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán dinámicamente los datos -->
                    <tr>
                        <td colspan="6" class="text-center">Seleccione un estudiante para ver sus cursos.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div id="miModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Contenido del modal -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Lista de Estudiantes</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <input type="text" placeholder="Buscar Estudiante o Enter para ver lista" id="cuadro_buscar" class="form-control" onkeypress="mi_busqueda();">
                            <div id="mostrar_mensaje"></div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function mi_busqueda() {
            buscar = document.getElementById("cuadro_buscar").value;
            var parametros = {
                usuario: $("#usuario").val(),
                sede: $("#sede").val(),
                mi_busqueda: buscar,
                accion: "5",
            };

            $.ajax({
                data: parametros,
                url: "tablas.php",
                type: "POST",

                beforesend: function() {
                    $("#mostrar_mensaje").html("Mensaje antes de Enviar");
                },

                success: function(mensaje) {
                    $("#mostrar_mensaje").html(mensaje);
                },
            });
        }

        function cargarTablaCursos(alumno_id) {
            if (!alumno_id) {
                $("#tablaInscripcion tbody").html("<tr><td colspan='6' class='text-center'>Seleccione un estudiante.</td></tr>");
                return;
            }
            var parametros = {
                accion: "5", // Acción específica para buscar cursos por alumno
                alumno_id: alumno_id
            };

            $.ajax({
                data: parametros,
                url: "tablas.php",
                type: "POST",
                beforeSend: function() {
                    $("#tablaInscripcion tbody").html("<tr><td colspan='6' class='text-center'>Cargando...</td></tr>");
                },
                success: function(mensaje) {
                    console.log("Cursos cargados para el estudiante:", mensaje);
                    $("#tablaInscripcion tbody").html(mensaje);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar cursos:", error);
                    $("#tablaInscripcion tbody").html("<tr><td colspan='6' class='text-center'>Error al cargar cursos.</td></tr>");
                }
            });
        }
    </script>
    <script src="js/busqueda_anterior.js"></script>
    <!-- jQuery, Popper.js y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>