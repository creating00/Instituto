<?php include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "cobrar";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
$rs = mysqli_query($conexion,"SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while($row = mysqli_fetch_array($rs))
{
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sede = $row['nombre'];
  
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y ");
//echo $feha_actual;
?>
<h3><center>Cobrar Cuotas Alumnos</center></h3><br>
<div class="container">
  <div class="row">
    <div class="col"> 
    <label><i class="fas fa-user"></i>Usuario</label>
    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $_SESSION['nombre']; ?>" id="usuario" readonly="readonly">
    </div>
    <div class="col order-5">
    <label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>    
    <input type="text"  id="fechainicio" value="<?php echo $feha_actual; ?>" name="comienzo"  class="sm-form-control" readonly="readonly">
    </div>
    <div class="col order-1">
    <label><i class="fa fa-university" aria-hidden="true"></i>SEDE</label><br>
    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede;?>" id="sede" readonly="readonly">
    </div>
    <div class="col order-5">
    <input id="idusuario" value="<?php echo $id_user; ?>" style="visibility:hidden"> 
  </div>
  </div>
</div>



<br><button type="button" onclick="limpiar2(); mi_busqueda_inscripcion();" data-toggle="modal" data-target="#miModal"><i class="fa fa-graduation-cap" aria-hidden="true" ></i>Buscar Alumno</button>
<!-- Modal Alumno -->
<div id="miModal" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg" > 
    <!-- Contenido del modal -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="my-modal-title">Lista de Alumno</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
      <div class="table-responsive">
      <input type="text" placeholder="Buscar Alumno" id="cuadro_buscar" class="form-control" onkeypress="mi_busqueda();">
      <div id="mostrar_mensaje"></div>

      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<form>
<br><div class="row">
<div class="col" style="width:5%;">
    <input type="text" class="form-control" style="width:49%;" placeholder="DNI" aria-label="First name" id="dni" onmouseover="mi_busqueda_inscripcion();listaExamenes();">
  </div>
  </div><br>
  <div class="row">
  <div class="col">
    <input type="text" class="form-control"  placeholder="Nombre" aria-label="First name" id="nombre" readonly="readonly">
  </div>
  <div class="col">
    <input type="text" class="form-control" placeholder="Apellido" aria-label="Last name" id="apellido" readonly="readonly">
  </div>
</div>
</form>
<br
>
<div class="table-responsive">
<div id="mostrar_inscripcion"></div>
</div>

<label for="textInput"> <i class="fa fa-book" aria-hidden="true"></i> Curso:</label>
<input id="texcurso" class="custom" size="32" onkeypress="mi_busqueda_cuotas();">
<input id="idinscripcion"  style="visibility:hidden">
<label for="textInput"> <i class="fa fa-book" aria-hidden="true"></i> CUOTA:</label>
<input id="cuota" >
<button type="button" class="btn btn-info"  data-toggle="modal" data-target="#cuotaModal" onclick="limpiar();"><i class="fa fa-usd" aria-hidden="true"></i>Cobrar Cuota</button>
<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#Modalexamen" onclick="limpiar();"><i class="fa fa-usd" aria-hidden="true"></i>Cobrar Examen Final</button>
<input id="idcuotas" style="visibility:hidden">
<div class="table-responsive"></div>
<div id="mostrar_cuotas"></div>
</div>
<!-- Modal Cuotas -->
<div id="cuotaModal" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg" > 
    <!-- Contenido del modal -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="my-modal-title">Cobrar Cuota</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <div class="table-responsive">
      <div id="cobrar"></div>
      <div class="col order-5">
      <label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>    
      <input type="text"  id="fechacuota" value="<?php echo $feha_actual; ?>" name="comienzo"  class="sm-form-control" readonly="readonly">
      </div>
      <div class="col order-5">
      <label for="textInput"> <i class="fa fa-square-o" aria-hidden="true"></i>Cuota:</label>
      <input id="cuota1" class="custom" size="32" readonly="readonly">
      </div>
      <div class="col order-5">
      <label for="textInput"><i class="fa fa-calendar" aria-hidden="true"></i> Mes:</label>
      <input id="mes" class="custom" size="32" readonly="readonly">
      </div>
      <div class="col order-5">
      <label for="textInput"> <i class="fa fa-book" aria-hidden="true"></i> Curso:</label>
      <input id="texcurso1" class="custom" size="32" readonly="readonly">
      </div>
      <div class="col order-5">
      <div id="cobrar"></div>
      <label for="textInput"> Importe:</label>
      <input id="importe" class="custom" size="32" readonly="readonly">
      <label for="textInput"> Interes:</label>
      <input type="number" id="interes" class="custom" size="32">
      </div>
      <div class="input-group mb-3"><br>
        <span class="input-group-text">Total</span>
        <span class="input-group-text">$</span>
        <input type="" id="total" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
      </div>
      <div class="col order-5">
      <label for="medioPago">Medio de Pago</label>
          <select name="mediodePago" class="form-control" id="mediodePago">
              <option value="Efectivo">Efectivo</option>
              <option value="Transferencia">Transferencia</option>
              <option value="PagoFacil">Pago Facil</option>
          </select>
      </div><br>
      <input id="idcuotas1" style="visibility:hidden">
      <button type="button" class="btn btn-outline-success" onclick="calcular_total(); cobrar_cuota();">Cobrar</button>
      <button type="button" class="btn btn-outline-danger"onclick="calcular_total();">Calcular</button>

      </div>
      </div>
      <div class="modal-footer">
        <div class="resultados"></div>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--Modal Examen final-->
<div id="Modalexamen" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cobrar Examen Final.</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <div class="col">  
       <div class="col order-5">
      <label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>    
      <input type="text"  id="fechaExamen" value="<?php echo $feha_actual; ?>" name="comienzo"  class="sm-form-control" readonly="readonly">
      </div>
      <div class="col order-5">
      <label for="textInput"> <i class="fa fa-book" aria-hidden="true"></i> Curso:</label>
      <input id="texcurso2" class="custom" size="32" readonly="readonly">
      </div>
      <div class="col order-5">
      <div id="cobrar"></div>
      <label for="textInput"> Cuota:</label>
      <input id="cuotaE" class="custom" size="32" readonly="readonly">
      <input id="idcuotaE" class="custom" size="32" readonly="readonly" style="visibility:hidden"><br>
      <label for="textInput"> Importe:</label>
      <input type="number"  id="importeF" value="0" class="custom" size="32">
      <label for="textInput"> Interes:</label>
      <input type="number" value="0" id="interesF" class="custom" size="32">
      </div>
      <div class="input-group mb-3"><br>
        <span class="input-group-text">Total</span>
        <span class="input-group-text">$</span>
        <input type="" id="totalF" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
      </div>
      <div class="col order-5">
      <label for="medioPago">Medio de Pago</label>
          <select name="mediodePago" class="form-control" id="mediodePagoF">
              <option value="Efectivo">Efectivo</option>
              <option value="Transferencia">Transferencia</option>
              <option value="PagoFacil">Pago Facil</option>
          </select>
      </div><br>
 
      <button type="button" class="btn btn-outline-success" onclick=" calcular_total_examen(); cobrarExamenFinal();">Cobrar</button>
      <button type="button" class="btn btn-outline-danger"onclick="calcular_total_examen();">Calcular</button>
      <button type="button" class="btn btn-outline-danger"onclick="listaExamenes();">ver</button>
      <div class="resultadosE"></div>
      <div class="lista_examenes"></div>
      </div>
      </div>
     



<script type="text/javascript">
  
function listaExamenes()
  {
    
   
    dni = $("#dni").val();
    sede = $("#sede").val();
    
    var parametros = 
    {
      "listaExamen": "1",
       "dni" : dni,
       "sede" : sede  
    };
    $.ajax(
    {
      data:  parametros,
      url:   'tablas.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('.lista_examenes').html(mensaje); 
       
        
    
      }
      
    }) 
    
  }

function cobrarExamenFinal()
  {
    
    idinscripcion = $("#idinscripcion").val();
    usuario = $("#usuario").val();
    sede = $("#sede").val();
    idcuotaE = $("#idcuotaE").val();
    mes = $("#mes").val();
    var parametros = 
    {
      "examen": "1",
      "idinscripcion" : idinscripcion,
       "usuario" : usuario,
       "sede" : sede,
       "idcuotaE" : idcuotaE,
       "mes" : mes,
       "mediodePagoF" : $("#mediodePagoF").val(),
      "interesF" : $("#interesF").val(),
      "totalF" : $("#totalF").val()
     
       
    };
    $.ajax(
    {
      data:  parametros,
      url:   'datosCuota.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('.resultadosE').html(mensaje); 
        url = 'pdf/reporteExamen.php?idinscripcion=' + idinscripcion + '&sede=' + sede;
        window.open(url, '_blank')
        correo_examen();
        listaExamenes();
        mi_busqueda_cuotas();
    
      }
      
    }) 
    
  }
  function correo_examen(){
idinscripcion = $("#idinscripcion").val();
dni = $("#dni").val();
texcurso1 = $("#texcurso1").val();

var parametros = 
{
  "guardar": "1",
  "idinscripcion" : idinscripcion,
   "dni" : dni,
   "texcurso1" : texcurso1,
   "precio" : $("#precio").val(),
  "dniP" : $("#dniP").val(),
  //"curso" : $("#curso").val(),
  "salas" : $("#salas").val(),
  "total" : $("#total").val(),
  "usuario" : $("#usuario").val(),
  "sede" : $("#sede").val(),
  "fechaComienzo" : $("#fechaExamen").val(),
  "medioPago" : $("#medioPago").val()
   
};
$.ajax(
{
  data:  parametros,
  url:   'correoExamenSMTP.php',
  type:  'post',
 
  error: function()
  {alert("Error");},
  
  success:  function (mensaje) 
  {
    $('.resultadosE').html(mensaje);
    
  }
  
}) 

}

function cobrar_cuota()
  {
    dni = $("#dni").val();
    curso = $("#texcurso1").val();
    mes = $("#mes").val();
    sede = $("#sede").val();
    var parametros = 
    {
      "cobrar_cuota": "1",
      "dni" : dni,
      "mes" : mes,
       "curso" : curso,
       "mediodePago" : $("#mediodePago").val(),
       "idcuotas1" : $("#idcuotas").val(),
      "idusuario" : $("#idusuario").val(),
      "interes" : $("#interes").val(),
      "total" : $("#total").val()
     
       
    };
    $.ajax(
    {
      data:  parametros,
      url:   'datosCuota.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('#mostrar_cuotas').html(mensaje); 
        
        url = 'pdf/reporteCuotas.php?dni=' + dni + '&curso=' + curso + '&mes=' + mes + '&sede=' + sede;
        window.open(url, '_blank')
        //enviar_correo();
        mi_busqueda_cuotas();
        limpiar();
      }
      
    }) 
    
  }
function enviar_correo(){

    dni = $("#dni").val();
    curso = $("#curso").val();
   
    var parametros = 
    {
      "guardar": "1",
       "dni" : dni,
       "curso" : curso,
       "precio" : $("#precio").val(),
      "dniP" : $("#dniP").val(),
      //"curso" : $("#curso").val(),
      "salas" : $("#salas").val(),
      "total" : $("#total").val(),
      "usuario" : $("#usuario").val(),
      "sede" : $("#sede").val(),
      "fechaComienzo" : $("#fechaComienzo").val(),
      "medioPago" : $("#medioPago").val()
       
    };
    $.ajax(
    {
      data:  parametros,
      url:   'correoSMTP.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('.resultados').html(mensaje);
        
      }
      
    }) 

  }
function enviar_correo(){

dni = $("#dni").val();
curso = $("#texcurso1").val();
mes = $("#mes").val();
cuota1 = $("#cuota1").val();
var parametros = 
{
  "guardar": "1",
   "dni" : dni,
   "curso" : curso,
   "mes" : mes,
   "cuota1" : cuota1,
   "precio" : $("#precio").val(),
  "dniP" : $("#dniP").val(),
  "salas" : $("#salas").val(),
  "total" : $("#total").val(),
  "usuario" : $("#usuario").val(),
  "sede" : $("#sede").val(),
  "fechaComienzo" : $("#fechaComienzo").val(),
  "medioPago" : $("#medioPago").val()
   
};
$.ajax(
{
  data:  parametros,
  url:   'correoCuotaSMTP.php',
  type:  'post',
 
  error: function()
  {alert("Error");},
  
  success:  function (mensaje) 
  {
    $('.resultados').html(mensaje);
    
  }
  
}) 

}
function calcular_total()
    { 
      var importe = parseFloat($('#importe').val());
      var interes = parseFloat($('#interes').val());
      var subtotal= importe*interes/100;
      var total = importe + subtotal;
      

      $.ajax({
       
        beforesend: function()
        {
          alert("Error");
        },

        success: function()
        {
          
          $("#total").val(total);
        }
      });
    }
    function calcular_total_examen()
    { 
      var importe = parseFloat($('#importeF').val());
      var interes = parseFloat($('#interesF').val());
      var subtotal= importe*interes/100;
      var total = importe + subtotal;
      

      $.ajax({
       
        beforesend: function()
        {
          alert("Error");
        },

        success: function()
        {
          
          $("#totalF").val(total);
        }
      });
    }
function cobrar2()
  {
    dni = $("#dni").val();
    curso = $("#texcurso1").val();
    mes = $("#mes").val();
    var parametros = 
    {
      "cobrar": "1",
      "dni" : dni,
      "mes" : mes,
       "curso" : curso,
       "mediodePago" : $("#mediodePago").val(),
       "idcuotas1" : $("#idcuotas").val(),
      "idusuario" : $("#idusuario").val(),
      "interes" : $("#interes").val(),
      "total" : $("#total").val()
     
       
    };
    $.ajax(
    {
      data:  parametros,
      url:   'datosCuota.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('#mostrar_cuotas').html(mensaje); 
        alert("Se Combro la Factura Correctamente");
        url = 'pdf/reporteCuotas.php?dni=' + dni + '&curso=' + curso + '&mes=' + mes;
        window.open(url, '_blank')
       
      }
      
    }) 
    
  }
function mi_busqueda()
    { 
      
    	buscar = document.getElementById('cuadro_buscar').value;
      var parametros = 
      {
        "usuario" : $("#usuario").val(),
        "sede" : $("#sede").val(),
        "mi_busqueda" : buscar,
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'tablas.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_mensaje').html("Mensaje antes de Enviar");
        },

        success: function(mensaje)
        {
          $('#mostrar_mensaje').html(mensaje);
        }
      });
    }


function mi_busqueda_inscripcion()
    { 
      
    	buscar = document.getElementById('dni').value;
      var parametros = 
      {
        "usuario" : $("#usuario").val(),
        "sede" : $("#sede").val(),
        "dni" :  $("#dni").val(),
        "mi_busqueda_inscripcion" : buscar,
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'datosCuota.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_inscripcion').html("Mensaje antes de Enviar");
        },

        success: function(mensaje,valores)
        {
          $('#mostrar_inscripcion').html(mensaje);
          buscar_datos_alumnos();
          $("#texcurso").val("");
          $("#cuota").val("");
        }
      });
    }

    function mi_busqueda_cuotas()
    { 
      
    	buscar = document.getElementById('dni').value;
      var parametros = 
      {
        "texcurso" : $("#texcurso").val(),
        "idinscripcion" : $("#idinscripcion").val(),
        "mi_busqueda_cuotas" : buscar,
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'datosCuota.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_cuotas').html("Mensaje antes de Enviar");
        },

        success: function(mensaje,valores)
        {
          $('#mostrar_cuotas').html(mensaje);
        
        }
      });
    }


    function buscar_datos_alumnos() 
  {
    dni = $("#dni").val();
    sede = $("#sede").val();
    
    var parametros = 
    {
      "buscar": "1",
      "dni" : dni,
      "sede" : sede
      
    };
    $.ajax(
    {
      data:  parametros,
      dataType: 'json',
      url:   'datosInscripcion.php',
      type:  'POST',
     
      error: function()
      {alert("Error");},
    
      success:  function (valores) 
      {
        
        $("#nombre").val(valores.nombre);
        $("#apellido").val(valores.apellido);

      }
    }) 
  } 
  function limpiar()
  {
    $("#interes").val("0");
    $("#interesF").val("0");
    $("#totalF").val("0");
    $("#total").val("0");
    
  }
  function limpiar2()
  {
    $("#dni").val("");
    $("#nombre").val("");
    $("#apellido").val("");
  }

                      
</script>

<?php include_once "includes/footer.php"; ?>