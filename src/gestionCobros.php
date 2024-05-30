<?php include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "CobranzasUsuarios";
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
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);

?>
<h3>Gestion Cobros Usuarios</h3>
<div class="container">
  <div class="row">
    <div class="col order-5col">
    <label><i class="fas fa-user" aria-hidden="true"></i>Usuario</label>
    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $_SESSION['nombre']; ?>" id="usuario" readonly="readonly">
    </div>
    <div class="col order-5">
    <label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>    
    <input type="text"  id="fechainicio" value="<?php echo $feha_actual; ?>" name="comienzo"  class="sm-form-control" readonly="readonly">
    </div>
    <div class="col order-5">
    <label><i class="fa fa-university" aria-hidden="true"></i>SEDE</label><br>
    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede;?>" id="sede" readonly="readonly">
    </div>
    <div class="col order-5">
    <input id="idusuario" value="<?php echo $id_user; ?>" style="visibility:hidden"> 
  </div>
  </div>
</div>



<br><button type="button"  data-toggle="modal" data-target="#ModalUsuario"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Buscar Usuario</button>
<!-- Modal -->
<div id="ModalUsuario" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg" > 
    <!-- Contenido del modal -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="my-modal-title">Usuarios</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
      <div class="table-responsive">
      <input type="text" placeholder="Buscar Usuario" id="cuadro_buscar" class="form-control" onkeypress="mi_busqueda_usuario();">
      <div id="mostrar_usuario"></div>

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
    <input type="text" class="form-control" placeholder="Usuario" aria-label="First name" id="usuario1" readonly="readonly">
  </div>
  </div><br>
  <div class="row">
  <div class="col">
    <input type="text" class="form-control" placeholder="Nombre" aria-label="First name" id="nombre" readonly="readonly">
  </div>
  <div class="col">
    <input type="text" class="form-control" placeholder="Correo" aria-label="Last name" id="correo" readonly="readonly">
  </div>
</div>
</form>
<br>

<div class="container">
<div class="row">   
<div class="col order-3">    
<label for="textInput"> <i class="fa fa-calendar-o" aria-hidden="true"></i>Mes:</label>
<select name="mes" class="form-control" id="mes" style="width:20%;">
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
</div> 
</div>
<div class="row">
<div class="col order-2 ">  
<label for="textInput"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Año:</label>  <br>
<input type="Number" value="<?php echo $anio; ?>" id="año" placeholder="Ingrese un Año" style="width:20%;">
<button type="button" class="btn btn-primary" onclick="mostrar_cobros();">Buscar</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#lista" onclick="mostrar_inscripcion();">Ver Inscripciones</button>
</div> 
</div><br>
<!-- Mostrar tabla de Cobros-->
<div class="table-responsive">
<label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha </label>    
<input type="date" placeholder="" id="fecha" class="sm-form-control" onclick="mostrar_cobros_fecha()"><br> <br>

<div id="mostrar_cuotas"></div>
</div>
</div><br>

<div class="modal" tabindex="-1" id="lista">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lista de Inscripciones</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <div id="mostrar_inscripciones"></div>
      </div>
      <div class="modal-footer">
      
      </div>
      
    </div>
  </div>
</div>


      <script type="text/javascript">

function mostrar_inscripcion()
    { 
     
    	
      var parametros = 
      {
        "usuario1" : $("#usuario1").val(),
        "mes" : $("#mes").val(),
        "año" : $("#año").val(),
        "mostrar_inscripciones" : buscar,
        "fecha" : $("#fecha").val(),
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'datos_gestion.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_inscripciones').html("Mensaje antes de Enviar");
        },

        success: function(mensaje,valores)
        {
          $('#mostrar_inscripciones').html(mensaje);
        
        }
      });
    }


function mostrar_cobros()
    { 
      
    	
      var parametros = 
      {
        "usuario1" : $("#usuario1").val(),
        "mes" : $("#mes").val(),
        "año" : $("#año").val(),
        "mostrar_cobros" : buscar,
        "fecha" : $("#fecha").val(),
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

      function mi_busqueda_usuario()
    { 
      
    	buscar = document.getElementById('cuadro_buscar').value;
      var parametros = 
      {
        "mi_busqueda_usuario" : buscar,
        "sede" : $("#sede").val(),
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'tablas.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_usuario').html("Mensaje antes de Enviar");
        },

        success: function(mensaje)
        {
          $('#mostrar_usuario').html(mensaje);
        }
      });
    }

    function mostrar_cobros_fecha()
    { 
      
    	buscar = document.getElementById('fecha').value;
      var parametros = 
      {
        "usuario1" : $("#usuario1").val(),
        "mes" : $("#mes").val(),
        "año" : $("#año").val(),
        "mostrar_cobros_fecha" : buscar,
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
    </script> 
      <?php include_once "includes/footer.php"; ?>