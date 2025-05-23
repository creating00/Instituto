<?php include_once "includes/header.php";
require("../conexion.php");
require("FacturaSecuencia.php");
// Crear una instancia de FacturaSecuencia
$facturaSecuencia = new FacturaSecuencia($conexion);

// Formatear el número de factura
$numeroFactura = $facturaSecuencia->formatearFacturaConMes();

$alertEliminar = "";

$id_user = $_SESSION['idUser'];
$permiso = "cobrar";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
$rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sede = $row['nombre'];
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
//echo $feha_actual;
?>

<h3>
  <center>Cobrar Pagos Estudiantes</center>
</h3><br>
<div class="container">
  <div class="row">
    <!-- Usuario -->
    <div class="col-md-4">
      <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
      <input
        id="usuario"
        class="form-control text-uppercase text-danger font-weight"
        value="<?php echo htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
        readonly
        aria-label="Usuario">
    </div>
    <!-- Fecha -->
    <div class="col-md-4">
      <label for="fechainicio"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha</label>
      <input
        type="text"
        id="fechainicio"
        name="comienzo"
        class="form-control"
        value="<?php echo htmlspecialchars($feha_actual, ENT_QUOTES, 'UTF-8'); ?>"
        readonly
        aria-label="Fecha actual">
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
  </div>

  <!-- ID Usuario oculto -->
  <input
    id="idusuario"
    type="hidden"
    value="<?php echo htmlspecialchars($id_user, ENT_QUOTES, 'UTF-8'); ?>">

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

  <!-- Botón Buscar Alumno -->
  <div class="text-left mt-3">
    <button
      type="button"
      class="btn btn-primary"
      onclick="limpiar2(); mi_busqueda_inscripcion();"
      data-toggle="modal"
      data-target="#miModal"
      aria-label="Buscar Estudiante">
      <i class="fa fa-graduation-cap" aria-hidden="true"></i> Buscar Estudiante
    </button>
  </div>
  <form>
    <br>
    <div class="row">
      <div class="col" style="width:5%;">
        <input type="text" class="form-control" style="width:49%;" placeholder="CÉDULA" aria-label="First name" id="dni" onmouseover="mi_busqueda_inscripcion();listaExamenes();">
      </div>
    </div><br>
    <div class="row">
      <div class="col">
        <input type="text" class="form-control" placeholder="Nombre" aria-label="First name" id="nombre" readonly="readonly">
      </div>
      <div class="col">
        <input type="text" class="form-control" placeholder="Apellido" aria-label="Last name" id="apellido" readonly="readonly">
      </div>
    </div>
  </form>
</div>


<!-- Modal Alumno -->
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
          <input type="text" placeholder="Buscar Estudiante" id="cuadro_buscar" class="form-control" onkeypress="mi_busqueda();">
          <div id="mostrar_mensaje"></div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<br>
<div class="table-responsive">
  <div id="mostrar_inscripcion"></div>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="texcurso">
          <i class="fa fa-book" aria-hidden="true"></i> Curso:
          <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Presionar ENTER para buscar las cuotas" style="cursor: pointer;"></i>
        </label>
        <input
          id="texcurso"
          class="form-control custom"
          onkeypress="mi_busqueda_cuotas();">
      </div>
    </div>
    <input id="idinscripcion" type="hidden">
    <div class="col-md-6">
      <div class="form-group" hidden>
        <label for="cuota">
          <i class="fa fa-book" aria-hidden="true"></i> PAGO:
        </label>
        <input
          id="cuota"
          class="form-control">
      </div>
    </div>
  </div>

  <!-- Nueva fila para los botones centrados -->
  <div class="row mt-3">
    <div class="col-12 text-center">
      <button
        type="button"
        class="btn btn-info mr-2"
        data-toggle="modal"
        onclick="prepararCobro();">
        <i class="fa fa-usd" aria-hidden="true"></i> Cobrar Pago
      </button>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#Modalexamen"
        onclick="limpiar();">
        <i class="fa fa-usd" aria-hidden="true"></i> Cobrar Examen Final
      </button>
    </div>
  </div>

  <input id="idcuotas" type="hidden">

  <div class="row mt-3">
    <div class="col-12">
      <div class="table-responsive">
        <div id="mostrar_cuotas"></div>
      </div>
    </div>
  </div>

  <!-- Modal de confirmación de eliminación para cuotas -->
  <div class="modal fade" id="confirmarEliminacionCuotaModal" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminacionCuotaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmarEliminacionCuotaModalLabel">Confirmar Eliminación de Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar o actualizar esta cuota?</p>
          <input type="hidden" id="idcuota">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmarEliminacionBtn">Eliminar/Actualizar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para usuarios con confirmación de administrador para cuotas -->
  <div class="modal fade" id="adminConfirmacionCuotaModal" tabindex="-1" role="dialog" aria-labelledby="adminConfirmacionCuotaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminConfirmacionCuotaModalLabel">Confirmación de Administrador para Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idcuotaAdmin" name="idcuotaAdmin">
          <div class="form-group">
            <label for="password">Ingrese la contraseña del administrador:</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmarAdminEliminacionBtn">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Modal Impresión -->
<div class="modal fade" id="impresionModal" tabindex="-1" role="dialog" aria-labelledby="impresionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="impresionModalLabel">Seleccionar Tipo de Impresión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          <input type="hidden" id="idCuotaModal">
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center mt-3">
          <div class="col-auto">
            <label for="tipo-impresion-modal" style="font-size: 20px;">Tipo de Impresión:</label>
            <select id="tipo-impresion-modal" name="tipo-impresion" class="form-control" style="width: 200px; font-size: 18px; text-align: center;">
              <option value="A4">A4</option>
              <option value="Ticket">Ticket</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmarImpresion">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Éxito -->
<div id="modalExito" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">¡Éxito!</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p id="mensajeExito">Se realizó la acción correctamente.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Error -->
<div id="modalError" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">¡Error!</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p id="mensajeError">Hubo un problema al realizar la acción.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cuotas -->
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
            <input id="idcursoHidden" type="hidden">
            <!-- Primera fila: Fecha y Cuota -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="fechacuota"> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>
                <input type="text" id="fechacuota" value="<?php echo $feha_actual; ?>" name="comienzo" class="form-control" readonly>
              </div>
              <div class="col-md-6">
                <label for="cuota1">
                  <i class="fa fa-square-o" aria-hidden="true"></i> Pago:
                </label>
                <div class="input-group">
                  <select id="cuota1" class="form-control">
                    <option value="">Seleccione una cuota</option>
                    <!-- Opciones dinámicas aquí -->
                  </select>
                  <div class="input-group-append">
                    <button type="button" class="btn btn-success" id="btnAgregarCuota">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
              </div>

            </div>

            <!-- Segunda fila: Mes y Curso -->
            <div class="row mb-3">
              <!-- Mes -->
              <div class="col-md-6">
                <label for="mes">
                  <i class="fa fa-calendar" aria-hidden="true"></i> Mes:
                  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Para eliminar elemento dar doble clic" style="cursor: pointer;"></i>
                </label>
                <select id="mes" class="form-control custom-multiple-select" multiple>
                  <!-- Opciones agregadas dinámicamente aquí -->
                </select>
              </div>

              <!-- Curso -->
              <div class="col-md-6">
                <label for="texcurso1">
                  <i class="fa fa-book" aria-hidden="true"></i> Curso:
                </label>
                <input id="texcurso1" class="form-control" readonly>
              </div>
              <style>
                .custom-multiple-select {
                  height: 100px;
                  /* Ajusta la altura para que se vean bien varias opciones */
                  border-radius: 5px;
                  /* Bordes redondeados para que se vea más elegante */
                  padding: 5px;
                  /* Espacio interno para que el texto no esté pegado */
                  background-color: #f8f9fa;
                  /* Color de fondo suave */
                }
              </style>
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
                  <input type="number" id="interes" class="form-control" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <label for="total"> Total: </label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="text" id="total" class="form-control" aria-label="Total">
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
            <div class="row mb-3" hidden>
              <div class="col-md-12">
                <label for="moraStatus">Mora está:</label>
                <input type="text" id="moraStatus" class="form-control" readonly>
              </div>
            </div>

            <!-- Sexta  fila: Detalles -->
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="form-check d-flex align-items-center">
                  <input class="form-check-input" type="checkbox" id="detalleCheckbox">
                  <label class="form-check-label align-middle ms-2" for="detalleCheckbox">
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

<!--Modal Examen final-->
<div id="Modalexamen" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cobrar Examen Final</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container">
          <!-- Fila 1: Fecha y Curso -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label><i class="fa fa-calendar" aria-hidden="true"></i> Fecha:</label>
              <input type="text" id="fechaExamen" value="<?php echo $feha_actual; ?>" name="comienzo" class="form-control" readonly>
            </div>
            <div class="col-md-6">
              <label><i class="fa fa-book" aria-hidden="true"></i> Curso:</label>
              <input id="texcurso2" class="form-control" readonly>
            </div>
          </div>

          <!-- Fila 2: Cuota, Importe e Interés -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label>Pago:</label>
              <input id="cuotaE" class="form-control" readonly>
              <input id="idcuotaE" class="form-control" readonly style="visibility:hidden">
            </div>
            <div class="col-md-4">
              <label>Monto:</label>
              <input type="number" id="importeF" value="0" class="form-control">
            </div>
            <div class="col-md-4">
              <label>Mora:</label>
              <input type="number" value="0" id="interesF" class="form-control" readonly>
            </div>
          </div>

          <!-- Fila 3: Total -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Total:</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" id="totalF" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
              </div>
            </div>
            <div class="col-md-6">
              <label for="medioPago">Medio de Pago:</label>
              <select name="mediodePago" class="form-control" id="mediodePagoF">
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="PagoFacil">Pago Fácil</option>
              </select>
            </div>
          </div>

          <!-- Fila 4: Botones -->
          <div class="row mt-3">
            <div class="col text-center">
              <button type="button" class="btn btn-outline-success" onclick="calcular_total_examen(); cobrarExamenFinal();">Cobrar</button>
              <button type="button" class="btn btn-outline-danger" onclick="calcular_total_examen();">Calcular</button>
              <button type="button" class="btn btn-outline-info" onclick="listaExamenes();">Ver</button>
            </div>
          </div>

          <!-- Resultados -->
          <div class="row mt-3">
            <div class="col">
              <div class="resultadosE"></div>
              <div class="lista_examenes"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Advertencia -->
<div class="modal fade" id="modalAdvertencia" tabindex="-1" role="dialog" aria-labelledby="modalAdvertenciaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document"> <!-- 💡 Agregamos modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="modalAdvertenciaLabel">¡Atención!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ⚠️ Por favor, seleccione una cuota antes de continuar.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning text-white" data-dismiss="modal">Entendido</button>
      </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>

<script type="text/javascript">
  function listaExamenes() {


    dni = $("#dni").val();
    sede = $("#sede").val();

    var parametros = {
      "listaExamen": "1",
      "dni": dni,
      "sede": sede
    };
    $.ajax({
      data: parametros,
      url: 'tablas.php',
      type: 'post',

      error: function() {
        alert("Error");
      },

      success: function(mensaje) {
        $('.lista_examenes').html(mensaje);
      }

    })

  }

  function cobrarExamenFinal() {

    idinscripcion = $("#idinscripcion").val();
    usuario = $("#usuario").val();
    sede = $("#sede").val();
    idcuotaE = $("#idcuotaE").val();
    mes = $("#mes").val();
    var parametros = {
      "examen": "1",
      "idinscripcion": idinscripcion,
      "usuario": usuario,
      "sede": sede,
      "idcuotaE": idcuotaE,
      "mes": mes,
      "mediodePagoF": $("#mediodePagoF").val(),
      "interesF": $("#interesF").val(),
      "totalF": $("#totalF").val()


    };
    $.ajax({
      data: parametros,
      url: 'datosCuota.php',
      type: 'post',

      error: function() {
        alert("Error");
      },

      success: function(mensaje) {
        $('.resultadosE').html(mensaje);
        url = 'pdf/reporteExamen.php?idinscripcion=' + idinscripcion + '&sede=' + sede;
        window.open(url, '_blank')
        correo_examen();
        listaExamenes();
        mi_busqueda_cuotas();

      }

    })

  }

  function correo_examen() {
    idinscripcion = $("#idinscripcion").val();
    dni = $("#dni").val();
    texcurso1 = $("#texcurso1").val();

    var parametros = {
      "guardar": "1",
      "idinscripcion": idinscripcion,
      "dni": dni,
      "texcurso1": texcurso1,
      "precio": $("#precio").val(),
      "dniP": $("#dniP").val(),
      //"curso" : $("#curso").val(),
      "salas": $("#salas").val(),
      "total": $("#total").val(),
      "usuario": $("#usuario").val(),
      "sede": $("#sede").val(),
      "fechaComienzo": $("#fechaExamen").val(),
      "medioPago": $("#medioPago").val()

    };
    $.ajax({
      data: parametros,
      url: 'correoExamenSMTP.php',
      type: 'post',

      error: function() {
        alert("Error");
      },

      success: function(mensaje) {
        $('.resultadosE').html(mensaje);

      }

    })

  }

  function mostrarModalExito(mensaje) {
    // Cerrar cualquier modal previamente abierto
    $(".modal").modal("hide");

    // Cambiar el mensaje del modal de éxito
    $("#mensajeExito").text(mensaje);

    // Mostrar el modal de éxito
    $("#modalExito").modal("show");
  }

  function mostrarModalError(mensaje) {
    // Cerrar cualquier modal previamente abierto
    $(".modal").modal("hide");

    // Cambiar el mensaje del modal de error
    $("#mensajeError").text(mensaje);

    // Mostrar el modal de error
    $("#modalError").modal("show");
  }

  function cobrar_cuota() {

    $("#mes option").prop("selected", true);


    // Obtener los valores seleccionados y convertirlos en un array de números
    var idcuota = $("#mes").val().map(Number) || [];

    // Verificar si se han seleccionado opciones
    if (idcuota.length === 0) {
      mostrarModalError("No hay cuotas seleccionadas.");
      return;
    }

    dni = $("#dni").val();
    curso = $("#texcurso1").val();
    mes = $("#mes").val();
    sede = $("#sede").val();
    tipoImpresion = $("#tipo-impresion").val();
    //idcuota = ($("#mes").val() || []).map(Number);
    nroFactura = $("#nroFactura").val();
    idcurso = $("#idcursoHidden").val();

    var parametros = {
      "cobrar_cuota": "1",
      "dni": dni,
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
    //console.log(parametros);

    $.ajax({
      data: parametros,
      url: 'datosCuota.php',
      type: 'post',

      error: function() {
        mostrarModalError("Error");
      },

      success: function(mensaje) {
        $('#mostrar_cuotas').html(mensaje);

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
                //console.log("Tipo de impresión seleccionado: A4");
                url =
                  "pdf/reporteCuotasMultiple.php?dni=" +
                  dni +
                  "&idcurso=" +
                  idcurso +
                  "&mes=" +
                  mes +
                  "&sede=" +
                  sede +
                  "&idcuotas=" +
                  idcuota.join(",");

                //console.log("URL generada para A4:", url);
              } else if (tipoImpresion === "Ticket") {
                //console.log("Tipo de impresión seleccionado: Ticket");
                url =
                  "pdf/comprobanteCuotasMultiple.php?idcuotas=" +
                  idcuota.join(",") +
                  "&dni=" +
                  dni +
                  "&idcurso=" +
                  idcurso +
                  "&mes=" +
                  mes +
                  "&sede=" +
                  sede;
                //console.log("URL generada para Ticket:", url);
              } else {
                //console.error("Tipo de impresión no válido:", tipoImpresion);
              }

              window.open(url, '_blank');
              mi_busqueda_cuotas();
              limpiar();
              $("#mes").empty();
              // Mostrar el modal de éxito
              mostrarModalExito("¡Pago realizado correctamente!");
            } else {
              mostrarModalError("Error al actualizar la factura: " + respuesta.error);
            }
          },
          error: function() {
            mostrarModalError("Error al comunicarse con el servidor para actualizar la factura.");
          }
        });
      }
    });
  }

  function enviar_correo() {

    dni = $("#dni").val();
    curso = $("#curso").val();

    var parametros = {
      "guardar": "1",
      "dni": dni,
      "curso": curso,
      "precio": $("#precio").val(),
      "dniP": $("#dniP").val(),
      //"curso" : $("#curso").val(),
      "salas": $("#salas").val(),
      "total": $("#total").val(),
      "usuario": $("#usuario").val(),
      "sede": $("#sede").val(),
      "fechaComienzo": $("#fechaComienzo").val(),
      "medioPago": $("#medioPago").val()

    };
    $.ajax({
      data: parametros,
      url: 'correoSMTP.php',
      type: 'post',

      error: function() {
        mostrarModalError("Error");
      },

      success: function(mensaje) {
        $('.resultados').html(mensaje);

      }

    })

  }

  function enviar_correo() {

    dni = $("#dni").val();
    curso = $("#texcurso1").val();
    mes = $("#mes").val();
    cuota1 = $("#cuota1").val();
    var parametros = {
      "guardar": "1",
      "dni": dni,
      "curso": curso,
      "mes": mes,
      "cuota1": cuota1,
      "precio": $("#precio").val(),
      "dniP": $("#dniP").val(),
      "salas": $("#salas").val(),
      "total": $("#total").val(),
      "usuario": $("#usuario").val(),
      "sede": $("#sede").val(),
      "fechaComienzo": $("#fechaComienzo").val(),
      "medioPago": $("#medioPago").val()

    };
    $.ajax({
      data: parametros,
      url: 'correoCuotaSMTP.php',
      type: 'post',

      error: function() {
        mostrarModalError("Error");
      },

      success: function(mensaje) {
        $('.resultados').html(mensaje);

      }

    })

  }

  function calcular_total() {
    var importe = parseFloat($('#importe').val());
    var interes = parseFloat($('#interes').val());
    //pedido cambio porcentaje por monto de dinero
    var subtotal = importe + interes;
    var total = subtotal;


    $.ajax({

      beforesend: function() {
        mostrarModalError("Error");
      },

      success: function() {

        $("#total").val(total);
      }
    });
  }

  function calcular_total_examen() {
    var importe = parseFloat($('#importeF').val());
    var interes = parseFloat($('#interesF').val());
    var subtotal = importe * interes / 100;
    var total = importe + subtotal;


    $.ajax({

      beforesend: function() {
        mostrarModalError("Error");
      },

      success: function() {

        $("#totalF").val(total);
      }
    });
  }

  function cobrar2() {
    dni = $("#dni").val();
    curso = $("#texcurso1").val();
    mes = $("#mes").val();
    var parametros = {
      "cobrar": "1",
      "dni": dni,
      "mes": mes,
      "curso": curso,
      "mediodePago": $("#mediodePago").val(),
      "idcuotas1": $("#idcuotas").val(),
      "idusuario": $("#idusuario").val(),
      "interes": $("#interes").val(),
      "total": $("#total").val()

    };
    $.ajax({
      data: parametros,
      url: 'datosCuota.php',
      type: 'post',

      error: function() {
        mostrarModalError("Error");
      },

      success: function(mensaje) {
        $('#mostrar_cuotas').html(mensaje);
        alert("Se Combro la Factura Correctamente");
        url = 'pdf/reporteCuotas.php?dni=' + dni + '&curso=' + curso + '&mes=' + mes;
        window.open(url, '_blank')

      }

    })

  }

  function mi_busqueda() {

    buscar = document.getElementById('cuadro_buscar').value;
    var parametros = {
      "usuario": $("#usuario").val(),
      "sede": $("#sede").val(),
      "mi_busqueda": buscar,
      "accion": "4"


    };

    $.ajax({
      data: parametros,
      url: 'tablas.php',
      type: 'POST',

      beforesend: function() {
        $('#mostrar_mensaje').html("Mensaje antes de Enviar");
      },

      success: function(mensaje) {
        $('#mostrar_mensaje').html(mensaje);
      }
    });
  }


  function mi_busqueda_inscripcion() {

    buscar = document.getElementById('dni').value;
    var parametros = {
      "usuario": $("#usuario").val(),
      "sede": $("#sede").val(),
      "dni": $("#dni").val(),
      "mi_busqueda_inscripcion": buscar,
      "accion": "4"
    };

    $.ajax({
      data: parametros,
      url: 'datosCuota.php',
      type: 'POST',
      beforesend: function() {
        $('#mostrar_inscripcion').html("Mensaje antes de Enviar");
      },

      success: function(mensaje, valores) {
        $('#mostrar_inscripcion').html(mensaje);
        buscar_datos_alumnos();
        $("#texcurso").val("");
        $("#cuota").val("");
      }
    });
  }

  function mi_busqueda_cuotas() {

    buscar = document.getElementById('dni').value;
    var parametros = {
      "texcurso": $("#texcurso").val(),
      "idinscripcion": $("#idinscripcion").val(),
      "mi_busqueda_cuotas": buscar,
      "accion": "4"
    };

    $.ajax({
      data: parametros,
      url: 'datosCuota.php',
      type: 'POST',

      beforesend: function() {
        $('#mostrar_cuotas').html("Mensaje antes de Enviar");
      },

      success: function(mensaje, valores) {
        $('#mostrar_cuotas').html(mensaje);

      }
    });
  }


  function buscar_datos_alumnos() {
    dni = $("#dni").val();
    sede = $("#sede").val();

    var parametros = {
      "buscar": "1",
      "dni": dni,
      "sede": sede

    };
    $.ajax({
      data: parametros,
      dataType: 'json',
      url: 'datosInscripcion.php',
      type: 'POST',

      error: function() {
        alert("Error");
      },

      success: function(valores) {

        $("#nombre").val(valores.nombre);
        $("#apellido").val(valores.apellido);

      }
    })
  }

  function limpiar() {
    $("#interes").val("0");
    $("#interesF").val("0");
    $("#totalF").val("0");
    $("#total").val("0");
  }

  function limpiar2() {
    $("#dni").val("");
    $("#nombre").val("");
    $("#apellido").val("");
  }
</script>

<script>
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('openAdminModal')) {
      e.preventDefault();
      const idCuota = e.target.getAttribute('data-id');

      const adminModal = document.getElementById('confirmarEliminacionCuotaModal');
      if (adminModal) {
        document.getElementById('idcuota').value = idCuota;
        const modalInstance = new bootstrap.Modal(adminModal);
        modalInstance.show();
      } else {
        console.error("No se encontró el modal #confirmarEliminacionCuotaModal");
      }
    }

    if (e.target.classList.contains('openUserModal')) {
      e.preventDefault();
      const idCuota = e.target.getAttribute('data-id');

      const userModal = document.getElementById('adminConfirmacionCuotaModal');
      if (userModal) {
        document.getElementById('idcuotaAdmin').value = idCuota;
        const modalInstance = new bootstrap.Modal(userModal);
        modalInstance.show();
      } else {
        console.error("No se encontró el modal #adminConfirmacionCuotaModal");
      }
    }

    // AJAX para confirmar eliminación de cuota desde el primer modal
    if (e.target.id === 'confirmarEliminacionBtn') {
      const idCuota = document.getElementById('idcuota').value;

      $.ajax({
        type: "POST",
        url: "eliminar_cuota_post.php",
        data: {
          idcuota: idCuota
        },
        success: function(response) {
          //console.log("Respuesta recibida:", response); // Ver la respuesta completa

          try {
            //const result = JSON.parse(response); // Intentar parsear el JSON
            if (response.status === 'success') {

              $('#confirmarEliminacionCuotaModal').removeClass('show').hide();
              $('.modal-backdrop').remove(); // Eliminar el fondo oscuro
              $('body').removeClass('modal-open'); // Asegurar que no quede bloqueado el scroll

              // Mostrar el modal de éxito con un pequeño retraso
              setTimeout(function() {
                mostrarModalExito(response.message);
              }, 200); // 300ms de retraso, puedes ajustarlo si es necesario

              mi_busqueda_cuotas();
              //window.location.href = "cuotas.php"; // Redirige a cuotas.php
            } else {
              mostrarModalError(response.message);
            }
          } catch (e) {
            console.error("Error al parsear JSON:", e);
            mostrarModalError("Hubo un error al procesar la respuesta del servidor.");
          }
        },
        error: function() {
          mostrarModalError("Hubo un error al procesar la solicitud.");
        }
      });
    }

    // Confirmación de administrador (opcional, si es necesario)
    if (e.target.id === 'confirmarAdminEliminacionBtn') {
      const idCuota = document.getElementById('idcuotaAdmin').value;
      const password = document.getElementById('password').value;

      $.ajax({
        type: "POST",
        url: "eliminar_cuota_post_permiso.php", // Cambiado a la nueva URL
        data: {
          idcuota: idCuota,
          password: password
        },
        success: function(response) {
          //console.log("Respuesta recibida:", response); // Ver la respuesta completa

          try {

            if (response.status === 'success') {

              $('#adminConfirmacionCuotaModal').removeClass('show').hide(); // Cerrar el modal
              $('.modal-backdrop').remove(); // Eliminar el fondo oscuro
              $('body').removeClass('modal-open'); // Asegurar que no quede bloqueado el scroll

              setTimeout(function() {
                mostrarModalExito(response.message);
              }, 200);

              mi_busqueda_cuotas(); // Recargar la tabla de cuotas
            } else {
              mostrarModalErrorlert(response.message);
            }
          } catch (e) {
            console.error("Error al parsear JSON:", e);
            mostrarModalError("Hubo un error al procesar la respuesta del servidor.");
          }
        },
        error: function(xhr, status, error) {
          console.error("Error al procesar la solicitud:", error);
          console.log("Respuesta del servidor:", xhr.responseText); // Muestra la respuesta completa
          mostrarModalError("Hubo un error al procesar la solicitud.");
        }
      });
    }
  });
</script>

<script>
  function actualizarMoraStatus(cuotaId) {
    //console.log('ID de la cuota: ' + cuotaId); // Log del ID de la cuota

    // Realizamos una consulta AJAX al servidor para obtener el estado de la mora
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'obtenerMoraStatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Aquí recibimos el estado de la mora
        var tieneMora = xhr.responseText === 'true'; // Si el valor es 'true', tiene mora

        // Log de lo que trae la consulta (el estado de la mora)
        //console.log('Respuesta de la consulta: ' + xhr.responseText);

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
</script>

<script>
  let datosCuota = {}; // Variable global para almacenar los datos de la cuota seleccionada

  // Delegamos el evento click a los <tr> dentro de #tablaCuotas
  $(document).on('click', '#tablaCuotas tr', function() {
    datosCuota.idCuota = $(this).find('td:first').text().trim();
    datosCuota.numeroCuota = $(this).find('td:nth-child(3)').text().trim();
    datosCuota.cuotaRef = $(this).find('td:nth-child(1)').text().trim();
    datosCuota.mesCuota = $(this).find('td:nth-child(4)').text().trim();
    datosCuota.importe = $(this).find('td:nth-child(6)').text().trim().replace('$ ', '').replace('.', '').replace(',', '.');
    datosCuota.interes = $(this).find('td:nth-child(7)').text().trim().replace('$ ', '').replace('.', '').replace(',', '.');


    //console.log('Fila seleccionada:', datosCuota);
  });

  function prepararCobro() {
    limpiar();

    let filasTabla = $('#tablaCuotas tr').length;

    if (filasTabla <= 1) { // Solo el encabezado está presente
      $('#modalAdvertencia').modal('show');
      return;
    }

    //$('#idcuotas, #idcuotas1, #idcuotaE').val(datosCuota.idCuota);
    //$('#cuota, #cuotaE').val(datosCuota.numeroCuota);
    $('#cuota1').val(datosCuota.cuotaRef);
    //$('#mes').val(datosCuota.mesCuota);
    //$('#importe').val(datosCuota.importe);
    //$('#interes').val(datosCuota.interes);

    actualizarMoraStatus(datosCuota.cuotaRef);
    llenarSelectCuotas();

    $('#cuotaModal').modal('show');
  }

  // Añadir cuota a "Mes"
  $('#btnAgregarCuota').on('click', function() {
    let $selectCuota = $('#cuota1');
    let $selectMes = $('#mes');

    let cuotaSeleccionada = $selectCuota.val();
    let textoCuota = $selectCuota.find(':selected').text();

    if (!cuotaSeleccionada) {
      alert('Por favor, seleccione una cuota antes de añadir.');
      return;
    }

    // Agregar la cuota al select "Mes"
    $selectMes.append(new Option(textoCuota, cuotaSeleccionada));

    // Ocultar la opción en "Pago"
    $selectCuota.find(`option[value="${cuotaSeleccionada}"]`).hide();

    // Resetear la selección
    $selectCuota.val('');

    // Sumar los valores de Monto y Mora de las cuotas seleccionadas
    sumarMontoMora();
  });

  // Eliminar cuota de "Mes" y restaurarla en "Pago"
  /*$('#mes').on('change', function() {
    let $selectCuota = $('#cuota1');
    let $selectMes = $('#mes');

    let cuotasSeleccionadas = $(this).val(); // Obtener todas las cuotas seleccionadas en "Mes"

    // Mostrar las opciones de "Pago" que no están en "Mes"
    $selectCuota.find('option').each(function() {
      let cuotaID = $(this).val();
      if (cuotasSeleccionadas && cuotasSeleccionadas.includes(cuotaID)) {
        $(this).hide(); // Ocultar las cuotas que están en "Mes"
      } else {
        $(this).show(); // Mostrar las cuotas no seleccionadas
      }
    });
  });*/

  // Añadir funcionalidad para eliminar cuotas seleccionadas de "Mes"
  $(document).on('dblclick', '#mes option', function() {
    let $selectCuota = $('#cuota1');
    let cuotaID = $(this).val();

    // Eliminar la opción seleccionada del select "Mes"
    $(this).remove();

    // Recuperar solo la opción eliminada en "Pago" si no está ya en "Pago"
    if ($selectCuota.find(`option[value="${cuotaID}"]`).length === 0) {
      let textoCuota = $(this).text();
      $selectCuota.append(new Option(textoCuota, cuotaID));
    }

    // Mostrar la opción en "Pago" (asegurarse de que esté visible)
    $selectCuota.find(`option[value="${cuotaID}"]`).show();
  });

  function llenarSelectCuotas() {
    let $select = $('#cuota1');
    $select.empty().append('<option value="">Seleccione una cuota</option>'); // Limpiamos y añadimos opción por defecto

    $('#tablaCuotas tr').each(function(index) {
      if (index === 0) return; // Saltamos la primera fila (encabezado)

      let idCuota = $(this).find('td:first').text().trim();
      let pago = $(this).find('td:nth-child(3)').text().trim();
      let mes = $(this).find('td:nth-child(4)').text().trim();
      let anio = $(this).find('td:nth-child(5)').text().trim();
      let condicion = $(this).find('td:nth-child(9)').text().trim(); // Columna de condición

      if (idCuota && condicion === "PENDIENTE") {
        let textoOpcion = `${pago} - ${mes} - ${anio}`;
        $select.append(new Option(textoOpcion, idCuota));
      }
    });
  }

  function sumarMontoMora() {
    let cuotasSeleccionadas = [];
    let totalMonto = 0;
    let totalMora = 0;

    // Recorremos todos los options dentro de #mes para obtener los valores
    $('#mes option').each(function() {
      cuotasSeleccionadas.push($(this).val());
    });

    if (cuotasSeleccionadas.length > 0) {
      cuotasSeleccionadas.forEach(function(cuotaID) {
        $('#tablaCuotas tr').each(function() {
          let idCuota = $(this).find('td:first').text().trim();

          if (idCuota === cuotaID) {
            // Obtener valores y formatearlos correctamente
            let monto = parseUSD($(this).find('td:nth-child(6)').text());
            let mora = parseUSD($(this).find('td:nth-child(7)').text());

            totalMonto += monto;
            totalMora += mora;
          }
        });
      });
    }

    // Asignar valores formateados a los campos
    $('#importe').val(totalMonto.toFixed(2));
    $('#interes').val(totalMora.toFixed(2));
  }

  function parseUSD(value) {
    return parseFloat(value.replace(/,/g, '').replace('$', '').trim()) || 0;
  }

  $(document).ready(function() {
    // Cuando se hace clic en el botón de impresión
    $(".imprimir-btn").click(function() {
      let idCuota = $(this).data("id"); // Obtiene el ID de la cuota
      $("#idCuotaModal").val(idCuota); // Lo guarda en el input hidden
    });

    // Función para generar la impresión
    function generarImpresion() {
      let dni = $("#dni").val();
      let curso = $("#texcurso1").val();
      let mes = $("#mes").val();
      let sede = $("#sede").val();
      let nroFactura = $("#nroFactura").val();
      let idcurso = $("#idcursoHidden").val();
      let idCuotaSeleccionado = $("#idCuotaModal").val(); // Obtener el ID desde el input hidden
      let tipoImpresion = $("#tipo-impresion-modal").val();

      let url;

      if (tipoImpresion === "A4") {
        url = "pdf/reporteCuotasMultiple.php?dni=" + dni +
          "&idcurso=" + idcurso +
          "&mes=" + mes +
          "&sede=" + sede +
          "&idcuotas=" + idCuotaSeleccionado;
      } else if (tipoImpresion === "Ticket") {
        url = "pdf/comprobanteCuotasMultiple.php?idcuotas=" + idCuotaSeleccionado +
          "&dni=" + dni +
          "&idcurso=" + idcurso +
          "&mes=" + mes +
          "&sede=" + sede;
      } else {
        console.error("Tipo de impresión no válido:", tipoImpresion);
        return;
      }

      console.log("URL generada:", url);
      window.open(url, "_blank"); // Abrir la URL generada en una nueva pestaña
      $("#impresionModal").modal("hide"); // Cerrar el modal
    }

    // Asignar la función al botón de Aceptar
    $("#confirmarImpresion").click(generarImpresion);
  });

  function guardarIdCuota(idCuota) {
    $("#idCuotaModal").val(idCuota); // Guardar el ID en el input hidden
    console.log("ID de cuota almacenado en el modal:", idCuota);
  }
</script>

<script>
  $(document).ready(function() {
    // Habilitar tooltips
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>


<script src="js/curso_modal_scripts.js"></script>
<?php if ($permiso === "cobrar") : ?>
  <script>
    // Mostrar/ocultar el textarea según el estado del checkbox
    document.getElementById('detalleCheckbox').addEventListener('change', function() {
      const textarea = document.getElementById('detalleTextarea');
      textarea.style.display = this.checked ? 'block' : 'none';
    });
  </script>
<?php endif; ?>
<?php include_once "includes/footer.php"; ?>