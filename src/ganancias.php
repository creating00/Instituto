<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Ganancias";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
//echo $feha_actual;
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);

$rs = mysqli_query($conexion, "SELECT SUM(importe)'total' FROM cuotas");
while ($row = mysqli_fetch_array($rs)) {
  $total = $row['total'];
}
$rs = mysqli_query($conexion, "SELECT usuario.idsede, sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sedeUser = $row['nombre'];
  $idsede = $row['idsede'];
}

?>
<div class="d-flex justify-content-center align-items-center">
  <i class="fa fa-university fa-5x mr-3" aria-hidden="true"></i>
  <h1>Ganancias</h1>
</div>

<div class="container mt-4">
  <form>
    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="gananciasTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="mensuales-tab" data-toggle="tab" href="#mensuales" role="tab">Ganancias Mensuales</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="informe-detallado-tab" data-toggle="tab" href="#informe-detallado" role="tab">Informe Detallado</a>
      </li>
    </ul>

    <div class="tab-content mt-4" id="gananciasContent">
      <!-- Sección Ganancias Mensuales (Código original) -->
      <div class="tab-pane fade show active" id="mensuales" role="tabpanel">
        <div class="form-row align-items-center">
          <div class="form-group col-md-6">
            <label for="idusuario" style="font-size: 20px">
              <i class="fas fa-user" aria-hidden="true"></i> Usuarios:
            </label>
            <select name="idusuario" class="form-control" id="idusuario" onchange="ShowSelected();">
              <?php
              include "../conexion.php";
              if ($sedeUser == "GENERAL") {
                $query = mysqli_query($conexion, "SELECT idusuario, usuario, sedes.nombre AS sede FROM usuario 
                                              INNER JOIN sedes ON usuario.idsede = sedes.idsede");
              } else {
                $query = mysqli_query($conexion, "SELECT idusuario, usuario, sedes.nombre AS sede FROM usuario 
                                              INNER JOIN sedes ON usuario.idsede = sedes.idsede 
                                              WHERE usuario.idsede = '$idsede'");
              }

              while ($row = mysqli_fetch_assoc($query)) {
                echo "<option value='{$row['idusuario']}'>{$row['usuario']} ({$row['sede']})</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="mes" style="font-size: 20px">
              <i class="fa fa-calendar" aria-hidden="true"></i> Mes:
            </label>
            <select name="mes" class="form-control" id="mes" onchange="ShowSelected();">
              <option value="Enero">Enero</option>
              <option value="Febrero">Febrero</option>
              <option value="Marzo" selected>Marzo</option>
              <option value="Abril">Abril</option>
              <option value="Mayo">Mayo</option>
              <option value="Junio">Junio</option>
              <option value="Julio">Julio</option>
              <option value="Agosto">Agosto</option>
              <option value="Septiembre">Septiembre</option>
              <option value="Octubre">Octubre</option>
              <option value="Noviembre">Noviembre</option>
              <option value="Diciembre">Diciembre</option>
            </select>
            <small class="text-muted">Selecciona un mes para filtrar</small>
          </div>

          <div class="form-group col-md-4">
            <label for="anio" style="font-size: 20px">
              <i class="fa fa-circle" aria-hidden="true"></i> Año:
            </label>
            <input type="number" class="form-control" value="<?php echo $anio; ?>" id="anio" name="anio" placeholder="Ingrese un Año">
            <small class="text-muted">Selecciona o ingresa un año para filtrar</small>
          </div>
        </div>

        <hr style="border-top: 2px solid #ccc; margin: 10px 0;">

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="fechaFiltro" style="font-size: 20px">Filtrar por Fecha:</label>
            <input type="date" id="fechaFiltro" name="fechaFiltro" class="form-control">
            <small class="text-muted">Elige una fecha específica para filtrar</small>
          </div>
        </div>

        <!-- Añadir un texto indicativo -->
        <p class="text-center text-primary">Elige uno de los filtros: Mes/Año o Fecha</p>

        <div class="table-responsive mt-3">
          <div id="mostrar_cuotas"></div>
        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-primary" onclick="total_ganancias(); total_ganancias_In(); totalExamenn();" data-toggle="modal" data-target="#ganancias">
            <i class="fa fa-money" aria-hidden="true"></i> Buscar
          </button>
          <input type="hidden" id="mes1" name="mes1" value="Marzo">
        </div>
      </div>

      <!-- Nueva Pestaña: Informe Detallado -->
      <div class="tab-pane fade" id="informe-detallado" role="tabpanel">
        <div class="form-row mt-3">
          <div class="form-group col-md-4">
            <label for="cursoSeleccionado">Seleccionar Curso:</label>
            <select name="cursoSeleccionado" class="form-control" id="cursoSeleccionado">
              <option value="">Seleccione un curso</option>
            </select>
            <small class="text-muted">Selecciona un curso para filtrar</small>
          </div>


          <div class="form-group col-md-4">
            <label for="mesInicio">Mes de Inicio:</label>
            <select name="mesInicio" class="form-control" id="mesInicio">
              <option value="Enero">Enero</option>
              <option value="Febrero">Febrero</option>
              <option value="Marzo">Marzo</option>
              <option value="Abril">Abril</option>
              <option value="Mayo">Mayo</option>
              <option value="Junio">Junio</option>
              <option value="Julio">Julio</option>
              <option value="Agosto">Agosto</option>
              <option value="Septiembre">Septiembre</option>
              <option value="Octubre">Octubre</option>
              <option value="Noviembre">Noviembre</option>
              <option value="Diciembre">Diciembre</option>
            </select>
            <small class="text-muted">Selecciona el mes de inicio</small>
          </div>
          <div class="form-group col-md-4">
            <label for="anioInicio">Año de Inicio:</label>
            <input type="number" class="form-control" value="<?php echo $anio; ?>" id="anioInicio" name="anioInicio" placeholder="Ingrese el Año de inicio">
            <small class="text-muted">Selecciona el año de inicio</small>
          </div>
        </div>

        <hr style="border-top: 2px solid #ccc; margin: 10px 0;">

        <div class="form-row mt-3">
          <div class="form-group col-md-4">
            <label for="fechaInicio">Fecha de Inicio Específica:</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control">
            <small class="text-muted">O selecciona una fecha de inicio</small>
          </div>
          <div class="form-group col-md-4">
            <label for="fechaFin">Fecha Fin Específica:</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control">
            <small class="text-muted">O selecciona una fecha de fin</small>
          </div>
        </div>

        <hr style="border-top: 2px solid #ccc; margin: 10px 0;">

        <p class="text-center text-primary">Puedes filtrar por mes/año o por fechas específicas</p>

        <div class="table-responsive mt-3">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Alumno</th>
                <th>Concepto de Cuota</th>
                <th>Monto Original</th>
                <th>Mora</th>
                <th>Total Pagado</th>
                <th>Fecha de Pago</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody id="detalleGanancias">
              <!-- Aquí se cargarán los datos -->
            </tbody>
          </table>
        </div>

        <div id="resumenInforme" class="mt-4"></div>

        <div class="text-center mt-3">
          <button type="button" class="btn btn-primary" onclick="generarInformeDetallado();">
            <i class="fa fa-search"></i> Buscar
          </button>
          <button type="button" class="btn btn-success" onclick="exportarPDF();" hidden>
            <i class="fa fa-file-pdf"></i> Exportar PDF
          </button>
        </div>
      </div>
    </div>
  </form>
</div>


<div class="modal fade" id="ganancias" tabindex="-1" role="dialog" aria-labelledby="gananciasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ganancias</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label style="font-size: 24px">Ganancia Total Pagos:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="number" id="totall" value="0" name="total" class="form-control text-center">
          </div>
        </div>
        <div class="form-group">
          <label style="font-size: 24px">Ganancia Total Examen:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="number" id="total4" name="total" class="form-control text-center">
          </div>
        </div>
        <div class="form-group">
          <label style="font-size: 24px">Ganancia Total Inscripción:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="number" id="total2" value="0" name="total2" class="form-control text-center">
          </div>
        </div>
        <div class="form-group">
          <label style="font-size: 24px"><i class="fa fa-shopping-bag"></i> Total:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="number" id="total5" name="total3" class="form-control text-center">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php include_once "includes/footer.php"; ?>

<script type="text/javascript">
  function ShowSelected() {
    /* Para obtener el valor 
    var cod = document.getElementById("sedes").value;
    alert(cod);*/

    /* Para obtener el texto */
    var combo = document.getElementById("mes");
    var selected = combo.options[combo.selectedIndex].text;
    $('#mes1').val(selected);
    //alert(selected);
    total_ganancias();
  }

  function calcular_totall() {
    var importe1 = 0;
    var importe2 = 0;
    var importe3 = 0;

    importe1 = parseFloat($('#totall').val());
    if (importe1 > 0) {


    } else {

      importe1 = 0;
    }

    importe2 = parseFloat($('#total2').val());
    if (importe2 > 0) {


    } else {

      importe2 = 0;
    }

    importe3 = parseFloat($('#total4').val());
    if (importe3 > 0) {


    } else {

      importe3 = 0;
    }

    var total5 = importe1 + importe2 + importe3;

    $.ajax({

      beforesend: function() {
        alert("Error");
      },

      success: function() {

        $("#total5").val(total5);
      }
    });
  }

  function totalExamenn() {

    idusuario = $("#idusuario").val();
    mes1 = $("#mes1").val();
    anio = $("#anio").val();
    fecha = $("#fechaFiltro").val();
    var parametros = {
      "totalExamenn": "1",
      "idusuario": idusuario,
      "mes1": mes1,
      "anio": anio,
      "fecha": fecha


    };
    $.ajax({
      data: parametros,
      dataType: 'json',
      url: 'datosInscripcion.php',
      type: 'POST',

      error: function() {
        alert("Error");
        console.log(parametros);
      },

      success: function(valores2) {
        $("#total4").val(valores2.totalE);
        calcular_totall();

      }
    })
  }


  function total_ganancias() {
    anio = $("#anio").val();
    idusuario = $("#idusuario").val();
    mes1 = $("#mes1").val();
    fecha = $("#fechaFiltro").val();
    var parametros = {
      "mostrarT": "1",
      "anio": anio,
      "idusuario": idusuario,
      "mes1": mes1,
      "fecha": fecha

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
        $("#totall").val(valores.totalT);
      }
    })
  }

  function total_ganancias_In() {
    anio = $("#anio").val();
    idusuario = $("#idusuario").val();
    mes1 = $("#mes1").val();
    fecha = $("#fechaFiltro").val();
    var parametros = {
      "totalInscripcion": "1",
      "anio": anio,
      "idusuario": idusuario,
      "mes1": mes1,
      "fecha": fecha
    };
    $.ajax({
      data: parametros,
      dataType: 'json',
      url: 'datosInscripcion.php',
      type: 'POST',

      error: function() {
        alert("Error");
        console.log(parametros);
      },

      success: function(valores3) {
        $("#total2").val(valores3.totalI);
      }
    })
  }
</script>

<script type="text/javascript">
  function generarInformeDetallado() {
    const filters = obtenerFiltros();

    fetch('Ganancias_Alumno.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(filters)
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
        } else {
          procesarDatosInforme(data, filters.fechaInicio, filters.fechaFin);
        }
      })
      .catch(error => {
        console.log('Error en la solicitud:', error.message);
      });
  }

  function obtenerFiltros() {
    let mesInicio = document.getElementById('mesInicio').value;
    let anioInicio = document.getElementById('anioInicio').value;
    let fechaInicio = document.getElementById('fechaInicio').value;
    let fechaFin = document.getElementById('fechaFin').value;
    let idCurso = document.getElementById('cursoSeleccionado').value; // Agregado

    // Si ambas fechas están completas, anioInicio se vacía
    if (fechaInicio && fechaFin) {
      anioInicio = '';
    }
    // Si solo una de las fechas tiene valor, ambas fechas se vacían
    else if (fechaInicio || fechaFin) {
      fechaInicio = '';
      fechaFin = '';
    }

    return {
      mesInicio,
      anioInicio,
      fechaInicio,
      fechaFin,
      idCurso // Agregado
    };
  }

  function procesarDatosInforme(data, fechaInicio, fechaFin) {
    const filteredData = (fechaInicio && fechaFin) ? filtrarPorRango(data, fechaInicio, fechaFin) : data;
    let totales = calcularTotales(filteredData);
    llenarTabla(filteredData);
    inyectarResumen(totales);
  }

  function calcularTotales(data) {
    let totalSinMora = 0,
      totalMora = 0,
      totalGeneral = 0;
    let totalPagados = 0,
      montoPagados = 0;
    let totalPendientes = 0,
      montoPendientes = 0;
    let totalHoy = 0,
      montoHoy = 0;
    const hoy = new Date().toISOString().split('T')[0];

    data.forEach(item => {
      let importe = parseFloat(item.importe) || 0;
      let mora = parseFloat(item.interes) || 0;
      let total = importe + mora;
      let estado = item.condicion || 'No Disponible';
      let fechaPago = item.fecha_cuota !== "0000-00-00" ? item.fecha_cuota : 'No Pagado';

      totalSinMora += importe;
      totalMora += mora;
      totalGeneral += total;

      if (estado === "PAGADO") {
        totalPagados++;
        montoPagados += total;
      } else {
        totalPendientes++;
        montoPendientes += total;
      }

      if (fechaPago === hoy) {
        totalHoy++;
        montoHoy += total;
      }
    });

    return {
      totalSinMora,
      totalMora,
      totalGeneral,
      totalPagados,
      montoPagados,
      totalPendientes,
      montoPendientes,
      totalHoy,
      montoHoy
    };
  }

  function llenarTabla(data) {
    let html = '';
    data.forEach(item => {
      html += `
          <tr>
              <td>${item.nombre || 'N/A'} ${item.apellido || ''}</td>
              <td>${item.cuota || 'N/A'} - ${item.mes || 'N/A'}</td>
              <td>${formatCurrency(item.importe || 0)}</td>
              <td>${formatCurrency(item.interes || 0)}</td>
              <td>${formatCurrency(item.total || 0)}</td>
              <td>${item.fecha_cuota !== "0000-00-00" ? item.fecha_cuota : 'No Pagado'}</td>
              <td>
                  <span class="badge ${item.condicion === 'PAGADO' ? 'bg-success text-white' : 'bg-danger text-white'}" style="padding: 0.5em 1em; border-radius: 1em;">
                     ${item.condicion === 'PAGADO' ? 'PAGADO' : 'PENDIENTE'}
                  </span>
              </td>
          </tr>
        `;
    });
    document.getElementById('detalleGanancias').innerHTML = html;
  }

  function inyectarResumen(totales) {
    document.getElementById('resumenInforme').innerHTML = `
    <div class="row mt-3">
        <div class="col-md-4">
            <label>Total sin mora:</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.totalSinMora)}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <label>Total mora:</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.totalMora)}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <label>Total general:</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.totalGeneral)}" disabled>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <label>Total alumnos pagaron:</label>
            <div class="input-group">
                <input type="text" class="form-control" value="${totales.totalPagados}" disabled>
                <span class="input-group-text">Alumnos</span>
            </div>
            <div class="input-group mt-2">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.montoPagados)}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <label>Total alumnos pendientes:</label>
            <div class="input-group">
                <input type="text" class="form-control" value="${totales.totalPendientes}" disabled>
                <span class="input-group-text">Alumnos</span>
            </div>
            <div class="input-group mt-2">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.montoPendientes)}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <label>Total pagos hoy:</label>
            <div class="input-group">
                <input type="text" class="form-control" value="${totales.totalHoy}" disabled>
                <span class="input-group-text">Alumnos</span>
            </div>
            <div class="input-group mt-2">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" value="${formatCurrencyWithoutSymbol(totales.montoHoy)}" disabled>
            </div>
        </div>
    </div>
`;
  }

  function formatCurrencyWithoutSymbol(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '0.00'; // Retorna 0.00 si el valor no es válido
    return number.toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  function filtrarPorRango(data, fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);

    // Obtener los nombres de los meses en el rango
    const mesesEnRango = new Set();
    for (let d = new Date(inicio); d <= fin; d.setMonth(d.getMonth() + 1)) {
      const nombreMes = d.toLocaleString('es-ES', {
        month: 'long'
      }); // Obtener nombre en español
      mesesEnRango.add(nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1)); // Capitalizar
    }

    console.log("Meses en el rango:", [...mesesEnRango]); // Verificar en consola

    return data.filter(item => {
      const itemFecha = new Date(item.fecha_cuota);

      // Si tiene una fecha válida, verificar el rango
      if (item.fecha_cuota !== "0000-00-00") {
        return itemFecha >= inicio && itemFecha <= fin;
      } else {
        // Comparar directamente por nombre de mes
        const itemMes = item.mes.trim(); // Asegurar que no haya espacios extras
        return mesesEnRango.has(itemMes);
      }
    });
  }

  function formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '$0.00';
    return number.toLocaleString("en-US", {
      style: "currency",
      currency: "USD"
    });
  }

  function exportarPDF() {

  }

  function cargarCursos() {
    $.ajax({
      url: "obtener_cursos.php", // Archivo PHP que consulta la base de datos
      type: "GET",
      dataType: "json",
      success: function(response) {
        let select = $("#cursoSeleccionado");
        select.empty(); // Limpiar opciones previas

        // Opción predeterminada "Todos"
        select.append(`<option value="0" selected>Todos</option>`);

        if (response.length > 0) {
          response.forEach(function(curso) {
            select.append(`<option value="${curso.idcurso}">${curso.nombre}</option>`);
          });
        } else {
          select.append(`<option value="">No hay cursos disponibles</option>`);
        }
      },
      error: function() {
        $("#cursoSeleccionado").append(`<option value="">Error al cargar cursos</option>`);
      }
    });
  }
  $(document).ready(function() {
    cargarCursos();
  });
</Script>
<?php include_once "includes/footer.php"; ?>