<?php
include_once "includes/header.php";
require "../conexion.php";
require_once "crear_recordatorios.php";
require_once 'CuotasHandler.php';
require_once "FacturaSecuencia.php";
require_once "RolesHandler.php";
//$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
/*$totalU= mysqli_num_rows($usuarios);
$alumno = mysqli_query($conexion, "SELECT * FROM alumno");
$totalA = mysqli_num_rows($alumno);
$curso = mysqli_query($conexion, "SELECT * FROM curso");
$totalC = mysqli_num_rows($curso);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas");
$totalV = mysqli_num_rows($ventas);
$profesor = mysqli_query($conexion, "SELECT * FROM profesor");
$totalP = mysqli_num_rows($profesor);
$sala = mysqli_query($conexion, "SELECT * FROM sala");
$totalSa = mysqli_num_rows($sala);
$sedes = mysqli_query($conexion, "SELECT * FROM sedes");
$totalSe = mysqli_num_rows($sedes);*/

// Definir la ruta de la carpeta raíz para diplomas
$rootDir = "../diplomas/cursos";

// Verificar si la carpeta raíz existe; si no, crearla
if (!file_exists($rootDir)) {
    if (mkdir($rootDir, 0777, true)) {
        //echo "Carpeta raíz 'diplomas/cursos' creada con éxito.<br>";
    } else {
        die("Error al crear la carpeta raíz 'diplomas/cursos'.<br>");
    }
}

// Consultar todos los cursos para verificar y crear carpetas según sus IDs
$cursos = mysqli_query($conexion, "SELECT idcurso FROM curso");
if ($cursos) {
    while ($curso = mysqli_fetch_assoc($cursos)) {
        $idCurso = $curso['idcurso'];
        $cursoDir = $rootDir . "/" . $idCurso;

        // Verificar si la carpeta del curso existe; si no, crearla
        if (!file_exists($cursoDir)) {
            if (mkdir($cursoDir, 0777, true)) {
                //echo "Carpeta creada para el curso ID: $idCurso.<br>";
            } else {
                //echo "Error al crear la carpeta para el curso ID: $idCurso.<br>";
            }
        }
    }
} else {
    echo "Error al consultar los cursos: " . mysqli_error($conexion) . "<br>";
}

// Ahora verificamos y creamos los recordatorios para los cursos y alumnos
if (isset($dato['idusuario'])) {
    $usuarioId = $dato['idusuario'];
    //echo "Procesando usuario ID: $usuarioId<br>";
} else {
    //echo "ID de usuario no definido.<br>";
}
//echo "Procesando usuario ID: HOLa<br>";

// Obtener todos los usuarios (puedes eliminar esta parte si no es necesario)
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
if (!$usuarios) {
    die('Error en la consulta de usuarios: ' . mysqli_error($conexion));
}

// Ahora verificamos y creamos los recordatorios para los cursos y alumnos
crearRecordatorios($conexion);
procesarRecordatorios($conexion);
// Crear instancia del CuotasHandler
$cuotasHandler = new CuotasHandler($conexion);

// Ejecutar la aplicación de mora automáticamente
//$cuotasHandler->aplicarMoraAutomaticamente();
$cuotasHandler->aplicarMoraComoInteres();

$facturaSecuencia = new FacturaSecuencia($conexion);

// Inicializar la secuencia del año actual (solo si no existe)
$facturaSecuencia->inicializarSecuencia();

// Instanciar la clase con la conexión
$rolesHandler = new RolesHandler($conexion);

// Inicializar los roles "admin" y "secretaria"
$rolesHandler->inicializarRoles();
?>

<header>

    <head>
        <Script Language="JavaScript">
            function DameLaFechaHora() {
                var hora = new Date()
                var hrs = hora.getHours();
                var min = hora.getMinutes();
                var hoy = new Date();
                var m = new Array();
                var d = new Array()
                var an = hoy.getYear();
                m[0] = "Enero";
                m[1] = "Febrero";
                m[2] = "Marzo";
                m[3] = "Abril";
                m[4] = "Mayo";
                m[5] = "Junio";
                m[6] = "Julio";
                m[7] = "Agosto";
                m[8] = "Septiembre";
                m[9] = "Octubre";
                m[10] = "Noviembre";
                m[11] = "Diciembre";
                document.write("Hora " + hrs + ":" + min + " fecha de hoy ");
                document.write(hoy.getDate());
                document.write(" de ");
                document.write(m[hoy.getMonth()]);
                document.write(" del ");

            }
        </Script>
    </head>
</header>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray">Panel Principal</h1>
</div>

<!-- Content Row -->
<div class="row">
    <a class="col-xl-3 col-md-6 mb-4" href="usuarios.php">
        <div class="card border-left-warning bg-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalU; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300 fa-5x" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Earnings (Monthly) Card Example -->
    <a class="col-xl-3 col-md-6 mb-4" href="alumno.php">
        <div class="card border-left-success shadow h-100 py-2 bg-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Estudiantes</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalA; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-graduation-cap fa-5x" aria-hidden="true" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Earnings (Monthly) Card Example -->
    <a class="col-xl-3 col-md-6 mb-4" href="curso.php">
        <div class="card border-left-info shadow h-100 py-2 bg-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Cursos</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-white"><?php echo $totalC; ?></div>
                            </div>
                            <!--<div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>-->
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-tasks fa-5x" aria-hidden="true" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Pending Requests Card Example -->
    <a class="col-xl-3 col-md-6 mb-4" href="profesor.php">
        <div class="card border-left-warning bg-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Profesores</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalP; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-users fa-5x" aria-hidden="true" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <a class="col-xl-3 col-md-6 mb-4" href="sala.php">
        <div class="card border-left-info shadow h-100 py-2 bg-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Salas</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalSa; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-window-restore fa-5x" aria-hidden="true" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <a class="col-xl-3 col-md-6 mb-4" href="ganancias.php">
        <div class="card border-left-info shadow h-100 py-2 bg-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Ganancias</div>
                        <div class="h5 mb-0 font-weight-bold text-white" style="color:#FFFFFF;">$ $ $ $ $</div>
                    </div>
                    <div class="col-auto">

                    </div>
                </div>
            </div>
        </div>
    </a>

    <a class="col-xl-3 col-md-6 mb-4" href="uniformes.php">
        <div class="card border-left-warning shadow h-100 py-2" style="background-color: #1E3A8A;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Uniformes</div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tshirt fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <a class="col-xl-3 col-md-6 mb-4" href="sedes.php" hidden>
        <div class="card border-left-primary shadow h-100 py-2 bg-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Sedes</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalSe; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-university fa-5x" aria-hidden="true" style="color:#FFFFFF;"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
<script>
    DameLaFechaHora();
</script><?php echo date("Y"); ?>

<?php include_once "includes/footer.php"; ?>