<?php
include_once "includes/header.php";
require_once '../conexion.php'; // Incluye tu archivo de conexión
require_once 'CuotasHandler.php';
require_once("FacturaSecuencia.php");

$id_user = $_SESSION['idUser'];

$rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
    $sede = $row['nombre'];
}

// Crear una instancia de FacturaSecuencia
$facturaSecuencia = new FacturaSecuencia($conexion);

// Formatear el número de factura
$numeroFactura = $facturaSecuencia->formatearFacturaConMes();

// Obtener el ID del alumno desde la URL
$idAlumno = $_GET['idAlumno'] ?? null;
$dni = $_GET['dni'] ?? null;

if ($idAlumno) {
    $cuotasHandler = new CuotasHandler($conexion);
    $cuotas = $cuotasHandler->obtenerCuotasPorAlumno($idAlumno);
    // Obtener los datos del alumno
    $consultaAlumno = "SELECT dni, nombre, apellido FROM alumno WHERE idalumno = '$idAlumno'";
    $resultadoAlumno = mysqli_query($conexion, $consultaAlumno);
    if ($resultadoAlumno) {
        $alumno = mysqli_fetch_array($resultadoAlumno);
        $dniAlumno = $alumno['dni'];
        $nombreAlumno = $alumno['nombre'];
        $apellidoAlumno = $alumno['apellido'];
    } else {
        // Manejar error si la consulta falla
        //echo "Error al obtener datos del alumno.";
    }
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
?>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Historial de Pagos del Estudiante</h1>
        <div>
            <div class="row">
                <div class="col" style="width:5%;">
                    <input type="text" class="form-control" style="width:49%;" placeholder="CÉDULA" aria-label="First name" id="dni" value="<?php echo $dniAlumno; ?>" readonly>
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre" aria-label="First name" id="nombre" value="<?php echo $nombreAlumno; ?>" readonly="readonly">
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Apellido" aria-label="Last name" id="apellido" value="<?php echo $apellidoAlumno; ?>" readonly="readonly">
                </div>
            </div>
            <br>
        </div>


        <?php if (!empty($cuotas)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>Pago</th>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Monto</th>
                            <th>Mora</th>
                            <th>Total</th>
                            <th>Condición</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                            <th hidden>idcurso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cuotas as $cuota): ?>
                            <tr>
                                <td><?= $cuota['idcuotas'] ?></td>
                                <td><?= $cuota['fecha'] ?></td>
                                <td><?= $cuota['nombre_curso'] ?></td>
                                <td><?= $cuota['cuota'] ?></td>
                                <td><?= $cuota['mes'] ?></td>
                                <td><?= $cuota['año'] ?></td>
                                <td><?= '$' . number_format($cuota['importe'], 2, ',', '.') ?></td>
                                <td><?= '$' . number_format($cuota['interes'], 2, ',', '.') ?></td>
                                <td><?= '$' . number_format($cuota['total'], 2, ',', '.') ?></td>
                                <td class="<?= $cuota['condicion'] == 'PENDIENTE' ? 'table-danger fw-bold' : 'table-success fw-bold' ?>">
                                    <?= $cuota['condicion'] == 'PENDIENTE' ? 'PENDIENTE' : 'Pagada' ?>
                                </td>
                                <td><?= $cuota['usuario'] ?></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-info mr-2"
                                        data-toggle="modal"
                                        data-target="#cuotaModal"
                                        onclick="obtenerDatosDeFila($(this).closest('tr')); limpiar(); actualizarMoraStatus(<?= $cuota['idcuotas'] ?>);">
                                        <i class="fa fa-usd" aria-hidden="true"></i> Cobrar Pago
                                    </button>
                                </td>
                                <td hidden><?= $cuota['id_curso'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No se encontraron cuotas para este estudiante.</p>
        <?php endif; ?>

        <div id="cuotaModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Contenido del modal -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Cobrar Pago</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="container-fluid">
                                <input type="hidden" id="idCursoHidden" name="idCursoHidden">
                                <!-- N° de Factura -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="nroFactura"><i class="fas fa-file-invoice"></i> N° de Factura</label>
                                        <input
                                            id="nroFactura"
                                            name="nroFactura"
                                            class="form-control text-uppercase text-primary font-weight"
                                            value="<?php echo htmlspecialchars($numeroFactura, ENT_QUOTES, 'UTF-8'); ?>"
                                            readonly
                                            aria-label="Número de factura">
                                    </div>
                                </div>

                                <!-- Sede -->
                                <div class="col-md-4" hidden>
                                    <label for="sede"><i class="fa fa-university" aria-hidden="true"></i> Sede</label>
                                    <input
                                        id="sede"
                                        class="form-control text-uppercase text-danger font-weight"
                                        value="<?php echo htmlspecialchars($sede, ENT_QUOTES, 'UTF-8'); ?>"
                                        readonly
                                        aria-label="Sede">
                                </div>

                                <!-- DNI -->
                                <div class="col-md-4" hidden>
                                    <label for="dni"><i class="fa fa-university" aria-hidden="true"></i> dni</label>
                                    <input
                                        id="dni"
                                        class="form-control text-uppercase text-danger font-weight"
                                        value="<?php echo htmlspecialchars($dni, ENT_QUOTES, 'UTF-8'); ?>"
                                        readonly
                                        aria-label="Sede">
                                </div>

                                <!-- USUARIO -->
                                <div class="col-md-4" hidden>
                                    <label for="idusuario"><i class="fa fa-university" aria-hidden="true"></i>usuario</label>
                                    <input
                                        id="idusuario"
                                        class="form-control text-uppercase text-danger font-weight"
                                        value="<?php echo htmlspecialchars($id_user, ENT_QUOTES, 'UTF-8'); ?>"
                                        readonly
                                        aria-label="Sede">
                                </div>

                                <br>
                                <!-- Primera fila: Fecha y Cuota -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="fechacuota"> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>
                                        <input type="text" id="fechacuota" value="<?php echo $feha_actual; ?>" name="comienzo" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cuota1"> <i class="fa fa-square-o" aria-hidden="true"></i> Pago: </label>
                                        <input id="cuota1" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Segunda fila: Mes y Curso -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="mes"> <i class="fa fa-calendar" aria-hidden="true"></i> Mes: </label>
                                        <input id="mes" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="texcurso1"> <i class="fa fa-book" aria-hidden="true"></i> Curso: </label>
                                        <input id="texcurso1" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Tercera fila: Importe, Interés y Total -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="importe"> Monto: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="importe" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="interes"> Mora: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" id="interes" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="total"> Total: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" id="total" class="form-control" aria-label="Total" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cuarta fila: Medio de Pago y Tipo de Impresión -->
                                <div class="row mb-3">
                                    <!-- Medio de Pago -->
                                    <div class="col-md-6">
                                        <label for="mediodePago">Medio de Pago:</label>
                                        <select name="mediodePago" class="form-control" id="mediodePago">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="PagoFacil">Pago Fácil</option>
                                        </select>
                                    </div>

                                    <!-- Tipo de Impresión -->
                                    <div class="col-md-6">
                                        <label for="tipo-impresion">Tipo de Impresión:</label>
                                        <select name="tipo-impresion" class="form-control" id="tipo-impresion">
                                            <option value="A4">A4</option>
                                            <option value="Ticket">Ticket</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Quinta fila: Mora -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="moraStatus">Mora está:</label>
                                        <input type="text" id="moraStatus" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Sexta  fila: Detalles -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="detalleCheckbox">
                                            <label class="form-check-label" for="detalleCheckbox">
                                                Añadir Detalle
                                            </label>
                                        </div>
                                        <textarea id="detalleTextarea" name="detalle" class="form-control mt-2" rows="4" placeholder="Escribe el detalle aquí..." style="display: none;"></textarea>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="row mb-3">
                                    <div class="col-md-12 text-right">
                                        <input id="idcuotas1" type="hidden">
                                        <button type="button" class="btn btn-outline-success mr-2" onclick="calcular_total(); cobrar_cuota();">Cobrar</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="calcular_total();">Calcular</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="resultados"></div>
                        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function actualizarMoraStatus(cuotaId) {

        // Realizamos una consulta AJAX al servidor para obtener el estado de la mora
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'obtenerMoraStatus.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Aquí recibimos el estado de la mora
                var tieneMora = xhr.responseText === 'true'; // Si el valor es 'true', tiene mora

                // Log de lo que trae la consulta (el estado de la mora)
                console.log('Respuesta de la consulta: ' + xhr.responseText);

                var moraStatus = document.getElementById('moraStatus');
                if (tieneMora) {
                    moraStatus.value = 'Aplicado';
                    console.log('Estado de la mora: Aplicado');
                } else {
                    moraStatus.value = 'No aplicado';
                    console.log('Estado de la mora: No aplicado');
                }
            }
        };

        // Enviamos el ID de la cuota al servidor
        xhr.send('cuotaId=' + cuotaId);
    }

    function limpiar() {
        $("#interes").val("0");
        $("#interesF").val("0");
        $("#totalF").val("0");
        $("#total").val("0");
    }

    function obtenerDatosDeFila(fila) {
        // Obtener el ID de la cuota (primera columna)
        var idCuota = $(fila).find('td:nth-child(1)').html();
        $('#idcuotas').val(idCuota);

        $('#idcuotas1').val(idCuota);
        $('#idcuotaE').val(idCuota);

        var pago = $(fila).find('td:nth-child(4)').html();

        $('#cuota1').val(pago);

        // Obtener el nombre del curso (tercera columna)
        var nombreCurso = $(fila).find('td:nth-child(3)').html();
        $('#texcurso1').val(nombreCurso); // Asumiendo que tienes un campo con id="curso"

        // Obtener el número de cuota (cuarta columna)
        var cuota = $(fila).find('td:nth-child(4)').html();
        $('#cuota').val(cuota);
        $('#cuotaE').val(cuota);

        // Obtener el mes (quinta columna)
        var mes = $(fila).find('td:nth-child(5)').html();
        $('#mes').val(mes);

        // Obtener el año (sexta columna)
        var año = $(fila).find('td:nth-child(6)').html();
        $('#año').val(año);

        // Obtener el monto (séptima columna) y formatearlo
        var monto = $(fila).find('td:nth-child(7)').html().replace('$', '').replace(/\./g, '').replace(',', '.');
        $('#importe').val(monto);

        // Obtener la mora (octava columna) y formatearla
        var mora = $(fila).find('td:nth-child(8)').html().replace('$', '').replace(/\./g, '').replace(',', '.');
        $('#interes').val(mora);

        // Obtener el total (novena columna) y formatearlo
        var total = $(fila).find('td:nth-child(9)').html().replace('$', '').replace(/\./g, '').replace(',', '.');
        $('#total').val(total);

        // Obtener el idcurso (duodécima columna)
        var idcurso = $(fila).find('td:nth-child(13)').html();
        $('#idCursoHidden').val(idcurso);

        // Obtener la condición (décima columna)
        var condicion = $(fila).find('td:nth-child(10)').text().trim();
        //$('#condicion').val(condicion);

        // Obtener el usuario (undécima columna)
        var usuario = $(fila).find('td:nth-child(11)').html();
        //$('#usuario').val(usuario);
    }

    function calcular_total() {
        var importe = parseFloat($('#importe').val());
        var interes = parseFloat($('#interes').val());
        //pedido cambio porcentaje por monto de dinero
        var subtotal = importe + interes;
        var total = subtotal;


        $.ajax({

            beforesend: function() {
                alert("Error");
            },

            success: function() {

                $("#total").val(total);
            }
        });
    }

    function cobrar_cuota() {
        dni = $("#dni").val();
        curso = $("#texcurso1").val();
        mes = $("#mes").val();
        sede = $("#sede").val();
        tipoImpresion = $("#tipo-impresion").val();
        idcuota = $("#idcuotas1").val();
        nroFactura = $("#nroFactura").val();
        idcurso = $("#idCursoHidden").val();

        var parametros = {
            "cobrar_cuota": "1",
            "dni": dni,
            "mes": mes,
            "curso": curso,
            "mediodePago": $("#mediodePago").val(),
            "idcuotas1": idcuota,
            "idusuario": $("#idusuario").val(),
            "interes": $("#interes").val(),
            "total": $("#total").val(),
            "isDetalle": $("#detalleCheckbox").is(":checked"),
            "detalle": $("#detalleTextarea").val(),
            "nroFactura": nroFactura
        };
        console.log(parametros);

        $.ajax({
            data: parametros,
            url: 'datosCuota.php',
            type: 'post',

            error: function() {
                alert("Error");
            },

            success: function(mensaje) {

                // Actualizar el N° de Factura desde el servidor
                $.ajax({
                    url: "actualizarFactura.php",
                    type: "post",
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.success) {
                            // Actualizar el input de N° de Factura con el nuevo número
                            $("#nroFactura").val(respuesta.nroFactura);

                            // Construir la URL según el tipo de impresión
                            var url;
                            if (tipoImpresion === "A4") {
                                console.log("Tipo de impresión seleccionado: A4");
                                url =
                                    "pdf/reporteCuotas.php?idcuotas=" +
                                    idcuota +
                                    "&dni=" +
                                    dni +
                                    "&idcurso=" +
                                    idcurso +
                                    "&sede=" +
                                    sede;
                                console.log("URL generada para A4:", url);
                            } else if (tipoImpresion === "Ticket") {
                                console.log("Tipo de impresión seleccionado: Ticket");
                                url =
                                    "pdf/comprobanteCuotas.php?idcuotas=" +
                                    idcuota +
                                    "&dni=" +
                                    dni +
                                    "&idcurso=" +
                                    idcurso +
                                    "&sede=" +
                                    sede;

                                console.log("URL generada para Ticket:", url);
                            } else {
                                console.error("Tipo de impresión no válido:", tipoImpresion);
                            }

                            window.open(url, '_blank');

                            limpiar();

                            // Recargar la página después de un pequeño retraso
                            setTimeout(function() {
                                location.reload();
                            }, 2000); // Espera 2 segundos antes de recargar
                        } else {
                            alert("Error al actualizar la factura: " + respuesta.error);
                        }
                    },
                    error: function() {
                        alert("Error al comunicarse con el servidor para actualizar la factura.");
                    }
                });
            }
        });
    }
</script>

<?php include_once "includes/footer.php"; ?>