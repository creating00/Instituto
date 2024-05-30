
<?php include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "inscripcion";
$dni='0';
$dniP='0';
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if(isset($_GET['id']))
{
  $ida =$_GET['id'];
  $resultados = mysqli_query($conexion,"SELECT * FROM alumno WHERE idalumno = '$ida'");
    while($consulta = mysqli_fetch_array($resultados))
    {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
      
      $dni= $consulta['dni'];
      //echo $dni;
      
    }
    

}
if(isset($_GET['idP']))
{
  $idP =$_GET['idP'];
  $resultados = mysqli_query($conexion,"SELECT * FROM profesor WHERE idprofesor = '$idP'");
    while($consulta = mysqli_fetch_array($resultados))
    {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
      
      $dniP= $consulta['dni'];
      //echo $dni;
           
    }
    

}
//buscar sede segun usuario
$rs = mysqli_query($conexion,"SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while($row = mysqli_fetch_array($rs))
{
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sede = $row['nombre'];
  
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y ");
?>
<!-- Enlace para abrir el modal -->

<h3><center>Inscribir Alumnos</center></h3><br>
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
</div><br>
<button type="button"  data-toggle="modal" data-target="#miModal"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Buscar Alumno</button>
<!-- Modal -->
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
<br><label><i class="fa fa-graduation-cap" aria-hidden="true"></i> Alumno</label>
<br><div class="row">
<div class="col" style="width:5%;">
    <input type="text" class="form-control" placeholder="DNI" aria-label="First name" id="dni" onkeypress="buscar_datos_alumnos();">
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


<!--<button type="button"  data-toggle="modal" data-target="#miModalP"><i class="fa fa-users" aria-hidden="true"></i>Buscar Profesor</button>-->
<!-- Modal -->
<div id="miModalP" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg" > 
    <!-- Contenido del modal -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="my-modal-title">Lista de Profesores</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
      <div class="row">
<div class="col" style="width:5%;">
<div class="table-responsive">
      <input type="text" placeholder="Buscar Profesor" id="cuadro_buscarP" class="form-control" onkeypress="mi_busquedaP();">
      <div id="mostrar_mensajeP"></div>

      </div>
  </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<form>

<br>
<br>
<button type="button"  data-toggle="modal" data-target="#miModalP"><i class="fa fa-users" aria-hidden="true"></i> Buscar Profesor</button>
<br><br>
<label><i class="fa fa-users" aria-hidden="true"></i> Profesor</label>
  <div class="row">
  <div class="col">
  <input type="text" class="form-control" placeholder="DNI" aria-label="First name" id="dniP" onkeypress="buscar_datos_profesor();">
  </div>
  <div class="col">
    <input type="text" class="form-control" placeholder="Nombre" aria-label="First name" id="nombreP" readonly="readonly" >
  </div>
  <div class="col">
    <input type="text" class="form-control" placeholder="Apellido" aria-label="Last name" id="apellidoP" readonly="readonly">
  </div>
</div><br>

</form>
<br>
<div class="card">
            <div class="card-header bg-primary text-white text-center">
                Datos Inscripcion
            </div><br>
  <div class="container">
  <div class="row">

  <div class="col order-5">
 
    </div>

  <div class="col ">
    <label> <i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Inicio </label>    
    <input type="date" id="fechaComienzo"  name="comienzo"  class="sm-form-control">
    </div>

    
    
    <div class="col order-1">
    <label><i class="fa fa-university" aria-hidden="true"></i>SEDE</label>
    <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede;?>" id="sede" readonly="readonly" >
    </div>
  </div>
</div><br>
 
<div class="container">
<button type="button"  data-toggle="modal" data-target="#modalCurso"><i class="fa fa-search-minus" aria-hidden="true"></i>Buscar Curso</button>
  <div class="row">
  <div class="col">
  <br> <input type="text" class="form-control" placeholder="Nombre del Curso"  id="curso" onkeypress="buscar_datos_cursos();">
  </div><br>
  <div class="col">
        <label>Duracion</label>
    <input type="text" class="form-control" placeholder="Duracion"  id="duracion" readonly="readonly" >
  </div>
  <div class="col">
        <label>Precio</label>
    <input type="text" class="form-control" placeholder="Precio"  id="precio" readonly="readonly">
  </div>
  </div>
</div> 
<div id="modalCurso" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg" > 
    <!-- Contenido del modal -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="my-modal-title">Lista de Cursos</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
      <div class="table-responsive">
      <input type="text" placeholder="Buscar Curso" id="cuadro_buscar_curso" class="form-control" onkeypress="mi_busqueda_curso();">
      <div id="mostrar_curso"></div>
      
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<br>

<div class="container">
  <div class="row">
   
   <div class="col ">
      <label for="sala">Salas Disponibles</label>
          <select name="sala" class="form-control" id="salas" >
                           <?php
                     //traer salas
                            
                            include "../conexion.php";
                            $query = mysqli_query($conexion, "SELECT sala FROM sala");
                            $result = mysqli_num_rows($query);
                                                    
                            while($row2 = mysqli_fetch_assoc($query))
                            {
	                          //$idrol = $row['idrol'];
                            $sala = $row2['sala'];

													?>
													
                           <option values="<?php echo $sala; ?>"><?php echo $sala; ?></option>  

                           <?php
                            }
                                                    
                            ?>
					</select>
                          
    </div>
                              
    <div class="col order-5">
                           
      <label for="medioPago">Medio de Pago</label>
          <select name="medioPago" class="form-control" id="medioPago">
              <option value="efectivo">Efectivo</option>
              <option value="transferencia">Tranferencia</option>
              <option value="pagoFacil">Pago Facil</option>
          </select>
   </div> 

  </div>
</div> 
<div class="col">  
        <label for="textInput" style="font-size: 25px">Total Inscripcion $:</label>  <br>
        <input type="Number" id="total" name="total" placeholder="$" style="width:20%; font-size: 30px; text-align:center;">

        </div>
<br><div class="container"> 
<input type="button" value="Inscribir Alumno" class="btn btn-primary" name="btn_inscribir" onclick="guardar();">
<input type="button" value="Limpiar" class="btn btn-danger" name="btn_inscribir" onclick="limpiar();">               
<div class="resultados"></div>
</div>            
</form>

                          
                           <br>
                        
                           <div class="table-responsive">
                             <h5><center>Lista De Inscripciones</center></h5>
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
               
                <th>DNI</th>
                <th>Nombre</th>
                <th>Curso</th>
                <th>Profesor</th>
                <th>Fecha y Hora de Inscripcion</th>
                <th>Fecha de Inicio</th>
                <th>Sede</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php ?>
            <?php
            include "../conexion.php";
            if($sede=="GENERAL"){

            $query = mysqli_query($conexion, "SELECT idinscripcion, alumno.dni, alumno.nombre'alumno', curso.nombre'curso', profesor.nombre'profesor', fecha, fechacomienzo, sedes.nombre'sede', inscripcion.estado FROM inscripcion
            INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
            INNER JOIN curso on inscripcion.idcurso=curso.idcurso
            INNER JOIN profesor on inscripcion.idprofesor=profesor.idprofesor
            INNER JOIN sedes on inscripcion.idsede=sedes.idsede
             ORDER BY idinscripcion DESC");

            }else{

              $query = mysqli_query($conexion, "SELECT idinscripcion, alumno.dni, alumno.nombre'alumno', curso.nombre'curso', profesor.nombre'profesor', fecha, fechacomienzo, sedes.nombre'sede', inscripcion.estado FROM inscripcion
              INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
              INNER JOIN curso on inscripcion.idcurso=curso.idcurso
              INNER JOIN profesor on inscripcion.idprofesor=profesor.idprofesor
              INNER JOIN sedes on inscripcion.idsede=sedes.idsede
              WHERE sedes.nombre='$sede' ORDER BY idinscripcion DESC");
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

                        <?php $data['idinscripcion'] ?>
                        <td><?php echo $data['dni']; ?></td>
                        <td><?php echo $data['alumno']; ?></td>
                        <td><?php echo $data['curso']; ?></td>
                        <td><?php echo $data['profesor']; ?></td>
                        <td><?php echo $data['fecha']; ?></td>
                        <td><?php echo $data['fechacomienzo']; ?></td>
                        <td><?php echo $data['sede']; ?></td>
                        <td>
                        <a href="pdf/generar.php?id=<?php echo $data['idinscripcion']; ?> "target="_blank" class="btn btn-warning"><i class="fa fa-file" aria-hidden="true"></i> </a>
                        <form action="eliminar_inscripcion.php?id=<?php echo $data['idinscripcion']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php if ($data['estado'] == 1) { ?>
                                
                              
                                
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">


function ShowSelected()
{
/* Para obtener el valor 
var cod = document.getElementById("sedes").value;
alert(cod);*/
 
/* Para obtener el texto */
var combo = document.getElementById("sedes");
var selected = combo.options[combo.selectedIndex].text;
$('#sede').val(selected);
//alert(selected);
}
//$('#tbl').click(function () {
    //alert('hiciste click en el input');
//});

//$('#tbl tr').on('click', function(){
  //var dato = $(this).find('td:first').html();
  //$('#curso').val(dato);
//});

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

    function mi_busqueda_curso()
    { 
      
    	buscar = document.getElementById('cuadro_buscar_curso').value;
      var parametros = 
      {
        "mi_busqueda_curso" : buscar,
        "sede" : $("#sede").val(),
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'tablas.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_curso').html("Mensaje antes de Enviar");
        },

        success: function(mensaje)
        {
          $('#mostrar_curso').html(mensaje);
        }
      });
    }

    function mi_busquedaP()
    { 
      
    	buscar = document.getElementById('cuadro_buscarP').value;
      var parametros = 
      {
        "usuario" : $("#usuario").val(),
        "sede" : $("#sede").val(),
        "mi_busquedaP" : buscar,
        "accion" : "4"
        
        
      };

      $.ajax({
        data: parametros,
        url: 'tablas.php',
        type: 'POST',
        
        beforesend: function()
        {
          $('#mostrar_mensajeP').html("Mensaje antes de Enviar");
        },

        success: function(mensaje)
        {
          $('#mostrar_mensajeP').html(mensaje);
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

</script> 
<!-- Funcion ajax jason Buscar Profesor-->
<script type="text/javascript">
function buscar_datos_profesor() 
  {
    dniP = $("#dniP").val();
    sedeP = $("#sede").val();
    
    var parametros = 
    {
      "buscar_profesor": "1",
      "dniP" : dniP,
      "sedeP" : sedeP
      
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
        $("#nombreP").val(valores.nombreP);
          $("#apellidoP").val(valores.apellidoP);

      }
    }) 
  }
  
</script>
<!-- Funcion ajax jason Buscar Curso-->
<script type="text/javascript">

function buscar_datos_cursos() 
  {
    curso = $("#curso").val();
    
    
    var parametros = 
    {
      "buscar_curso": "1",
      "curso" : curso,
      
    };
    $.ajax(
    {
      data:  parametros,
      dataType: 'json',
      url:   'datosInscripcion.php',
      type:  'POST',
     
      error: function()
      {alert("Error");},
    
      success:  function (valoresCu) 
      {
        $("#duracion").val(valoresCu.duracion);
          $("#precio").val(valoresCu.precio);

      }
    }) 
  }
  
</script> 
</script>
<!-- Funcion ajax jason Inscribir-->
<script type="text/javascript">
function guardar()
  {
    dni = $("#dni").val();
    curso = $("#curso").val();
    sede = $("#sede").val();
    var parametros = 
    {
      "guardar": "1",
       "dni" : dni,
       "curso" : curso,
       "sede" : sede,
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
      url:   'datosInscripcion.php',
      type:  'post',
     
      error: function()
      {alert("Error");},
      
      success:  function (mensaje) 
      {
        $('.resultados').html(mensaje);
        url = 'pdf/reporte.php?dni=' + dni + '&curso=' + curso + '&sede=' + sede;
        window.open(url, '_blank')
        //enviar_correo();
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
        window.location.href = "inscripcion.php";
      }
      
    }) 

  }

  function limpiar()
  {
    $("#dni").val("");
    $("#dniP").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#nombreP").val("");
    $("#apellidoP").val("");
    $("#curso").val("");
    $("#duracion").val("");
    $("#precio").val("");
    $("#total").val("");
  }
  
  
  
  



  
</script>   
<?php include_once "includes/footer.php"; ?>
