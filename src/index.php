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
<div class="row justify-content-center">
    <a class="col-xl-3 col-md-6 mb-4 text-decoration-none" href="usuarios.php">
        <div class="d-flex align-items-center justify-content-center"
             style="
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background-color: #dc3545;
                box-shadow: 0 0 10px rgba(0,0,0,0.2);
                color: white;
                text-align: center;
                flex-direction: column;
                transition: transform 0.2s;
        "
        onmouseover="this.style.transform='scale(1.05)'"
        onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-user fa-3x mb-2"></i>
            <div class="text-uppercase font-weight-bold">Usuarios</div>
            <div class="h4 font-weight-bold"><?php echo $totalU; ?></div>
        </div>
    </a>
</div>
<div class="row justify-content-center">
    <?php
    $items = [
        ["link" => "alumno.php", "color" => "#198754", "icon" => "fa-graduation-cap", "label" => "Estudiantes", "value" => $totalA],
        ["link" => "curso.php", "color" => "#0d6efd", "icon" => "fa-tasks", "label" => "Cursos", "value" => $totalC],
        ["link" => "profesor.php", "color" => "#dc3545", "icon" => "fa-users", "label" => "Profesores", "value" => $totalP],
        ["link" => "sala.php", "color" => "#0d6efd", "icon" => "fa-window-restore", "label" => "Salas", "value" => $totalSa],
        ["link" => "ganancias.php", "color" => "#0d6efd", "icon" => "fa-dollar-sign", "label" => "Ganancias", "value" => "$ $ $ $ $"],
        ["link" => "uniformes.php", "color" => "#1E3A8A", "icon" => "fa-tshirt", "label" => "Uniformes", "value" => ""]
    ];

    foreach ($items as $item): ?>
        <a href="<?= $item['link']; ?>" class="text-decoration-none text-center m-3">
            <div style="
                width: 180px;
                height: 180px;
                border-radius: 50%;
                background-color: <?= $item['color']; ?>;
                color: white;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                transition: transform 0.2s;
            " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa <?= $item['icon']; ?> fa-2x mb-2"></i>
                <strong class="text-uppercase"><?= $item['label']; ?></strong>
                <div class="h5"><?= $item['value']; ?></div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
    DameLaFechaHora();
</script><?php echo date("Y"); ?>

<?php include_once "includes/footer.php"; ?>