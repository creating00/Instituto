<?php include_once "includes/header.php";
include "../conexion.php";
require("FacturaSecuencia.php");

// Crear una instancia de FacturaSecuencia
$facturaSecuencia = new FacturaSecuencia($conexion);

// Formatear el número de factura
$numeroFactura = $facturaSecuencia->formatearFacturaConMes();

// Obtener la fecha actual
$fecha_actual = date("Y-m-d");

// Obtener el nombre del usuario desde la sesión
$usuario = $_SESSION['nombre'];

$id_user = $_SESSION['idUser'];
$permiso = "inscripcion";

$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

//buscar sede segun usuario
$rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
    $sede = $row['nombre'];
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");



// Verificar si se pasó el ID del uniforme por GET
/*if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='text-danger'>ID de uniforme no especificado.</p>");
}

$id_uniforme = $_GET['id'];

// Obtener datos del uniforme
$sql = mysqli_query($conexion, "SELECT * FROM uniformes WHERE id_uniforme = '$id_uniforme'");
$uniforme = mysqli_fetch_assoc($sql);

if (!$uniforme) {
    die("<p class='text-danger'>Uniforme no encontrado.</p>");
}*/
?>


<div class="container mt-4">
    <div class="row">
        <div class="col-6">
            <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
            <input
                style="font-size: 16px; text-transform: uppercase; color: red;"
                value="<?php echo $usuario; ?>"
                id="usuario"
                readonly="readonly"
                class="form-control">
        </div>
        <div class="col-6">
            <label for="fechainicio"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha:</label>
            <input
                type="text"
                id="fechainicio"
                value="<?php echo $fecha_actual; ?>"
                name="comienzo"
                class="form-control"
                readonly="readonly">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="nroFactura"><i class="fas fa-file-invoice"></i> N° de Factura</label>
            <input
                style="font-size: 16px; text-transform: uppercase; color: blue;"
                value="<?php echo $numeroFactura; ?>"
                id="nroFactura"
                name="nroFactura"
                readonly="readonly"
                class="form-control">
        </div>
    </div>

    <!-- Sede -->
    <div class="row mb-4">
        <div class="col" hidden>
            <label for="sede"><i class="fa fa-university" aria-hidden="true"></i> SEDE</label>
            <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede; ?>" id="sede" readonly="readonly" class="form-control">
        </div>
    </div>

    <h2 class="mb-4">Registrar Venta de Uniforme</h2>

    <form action="procesar_venta.php" method="post">
        <!-- Buscar Alumno -->
        <div class="mb-3">
            <label class="form-label">Cédula:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="dni" placeholder="Ingrese cédula del estudiante">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#miModal" id="btnBuscarAlumno">Buscar</button>
            </div>
        </div>

        <!-- Datos del Alumno (Nombre y Apellido) -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" disabled>
            </div>
        </div>

        <input type="hidden" name="alumno_id" id="alumno_id">

        <div class="row">
            <!-- Nombre del Uniforme -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Uniforme:</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?php echo isset($uniforme) ? $uniforme['nombre'] : ''; ?>" id="uniformeSeleccionado" placeholder="Seleccione un uniforme..." disabled>
                    <button type="button" class="btn btn-primary" id="btnBuscarUniforme" data-toggle="modal" data-target="#modalUniformes">Buscar</button>
                </div>
                <input type="hidden" name="id_uniforme" id="id_uniforme" value="<?php echo isset($uniforme) ? $uniforme['id_uniforme'] : ''; ?>">
            </div>

            <!-- Precio -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Precio:</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" value="<?php echo isset($uniforme) ? number_format($uniforme['precio'], 2) : ''; ?>" id="precioUniforme" disabled>
                </div>
            </div>

            <!-- Stock Disponible -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Stock Disponible:</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?php echo isset($uniforme) ? $uniforme['stock_disponible'] : ''; ?>" id="stockDisponible" disabled>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cantidad -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Cantidad:</label>
                <input type="number" name="cantidad" class="form-control" min="1" required>
            </div>

            <!-- Medio de Pago -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Medio de Pago:</label>
                <select name="medio_pago" class="form-control" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>
        </div>

        <!-- Selector de tipo de impresión -->
        <div class="row justify-content-center mt-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between">
                    <label for="tipo-impresion" class="form-label h5 mb-0">Tipo de Impresión:</label>
                    <select id="tipo-impresion" name="tipo-impresion" id class="form-control w-auto">
                        <option value="Ticket">Ticket</option>
                        <option value="A4">A4</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Total a Pagar -->
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <label class="form-label h5 text-center d-block">Total a Pagar:</label>
                <div class="input-group shadow-sm rounded">
                    <span class="input-group-text bg-primary text-white fw-bold">$</span>
                    <input type="text" class="form-control text-end fw-bold fs-4" id="totalVenta" value="0.00" disabled>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end mt-4">
            <a href="uniformes.php" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-success">Registrar Venta</button>
        </div>
    </form>

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

<!-- Modal de Uniformes -->
<div id="modalUniformes" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Contenido del modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de Uniformes</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <input type="text" placeholder="Buscar Uniforme o Enter para ver lista" id="cuadro_buscar_uniforme" class="form-control" onkeydown="buscarUniforme(event);">
                    <div id="mostrar_uniformes"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<script src="js/busqueda_anterior.js"></script>

<script src="js/uniforme.js">
</script>

<?php include_once "includes/footer.php";
