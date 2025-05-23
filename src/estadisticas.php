<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "estadisticas";
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

$rs = mysqli_query($conexion, "SELECT usuario.idsede, sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sedeUser = $row['nombre'];
  $idsede = $row['idsede'];
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
//echo $feha_actual;
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);

?>
<div class="container mt-3">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label><i class="fas fa-user"></i> Usuario</label>
        <input type="text" class="form-control text-uppercase text-danger font-weight-bold"
          value="<?php echo $_SESSION['nombre']; ?>" id="usuario" readonly>
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <label><i class="fa fa-calendar"></i> Fecha</label>
        <input type="text" id="fechainicio" name="comienzo" class="form-control"
          value="<?php echo $feha_actual; ?>" readonly>
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <label><i class="fa fa-university"></i> Sede</label>
        <input type="text" class="form-control text-uppercase text-danger font-weight-bold"
          value="<?php echo $sede; ?>" id="sede" readonly>
      </div>
    </div>
  </div>

  <input type="hidden" id="idusuario" value="<?php echo $id_user; ?>">
</div>
<br>

<div class="container mt-4">
  <h1 class="text-center font-weight-bold">PLATAFORMA DE CONTROL</h1>
  <hr>

  <div class="row justify-content-center text-center">
    <div class="col-md-4 mb-3">
      <input type="button" class="btn btn-info btn-lg w-100" data-toggle="modal" data-target="#curso" value="Total de Alumnos por Curso">
    </div>

    <div class="col-md-4 mb-3" hidden>
      <?php if ($sede == "GENERAL") { ?>
        <input type="button" class="btn btn-warning btn-lg w-100" data-toggle="modal" data-target="#sedes20" value="Total de Alumnos por Sedes">
      <?php } else { ?>
        <input type="button" class="btn btn-warning btn-lg w-100" disabled style="visibility:hidden">
      <?php } ?>
    </div>
  </div>

  <div class="text-center mt-4">
    <label class="font-weight-bold" style="font-size: 40px;">TOTAL</label>
    <input type="text" id="total" class="form-control mx-auto text-center font-weight-bold" style="width: 30%; font-size: 40px;" readonly>
  </div>

  <hr>

  <h2 class="text-center font-weight-bold mt-4">LISTAS DETALLADAS</h2>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div id="mostrar_mensaje"></div>
    </div>
  </div>
</div>

<!--Modal Curso -->
<div class="modal" tabindex="-1" id="curso">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar por Cursos</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>
      <div class="modal-body">
        <p>Seleccionar un Curso.</p>
        <div class="form-group">
          <label for="curso">Curso</label>
          <select name="curso" class="form-control" id="cursos" onchange="ShowSelected();">
            <?php
            //traer sedes
            include "../conexion.php";
            if ($sede == "GENERAL") {

              $query = mysqli_query($conexion, "SELECT idcurso, curso.nombre, sedes.nombre'sede' FROM curso
                                                    INNER JOIN sedes on curso.idsede=sedes.idsede");
              $result = mysqli_num_rows($query);
            } else {

              $query = mysqli_query($conexion, "SELECT idcurso, curso.nombre, sedes.nombre'sede' FROM curso
                                                    INNER JOIN sedes on curso.idsede=sedes.idsede WHERE curso.idsede='$idsede'");
              $result = mysqli_num_rows($query);
            }


            while ($row = mysqli_fetch_assoc($query)) {
              $idcurso = $row['idcurso'];
              $curso = $row['nombre'];
              $sede = $row['sede'];

            ?>

              <option value="<?php echo $idcurso; ?>"> <?php echo "#" . $idcurso . "-";
                                                        echo $curso;
                                                        echo "-";
                                                        echo $sede;
                                                        $curso_nombre = substr($curso, -30, -19);  ?> </option>

            <?php



            }

            ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="mi_busqueda();">Buscar</button>
      </div>
    </div>
  </div>
</div>


<!--Modal Curso -->
<div class="modal" tabindex="-1" id="sedes20">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" hidden>
        <h5 class="modal-title">Buscar por Sedes</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>
      <div class="modal-body" hidden>
        <p>Seleccionar una Sede.</p>
        <div class="form-group">
          <label for="sede">Sedes</label>
          <select name="sede" class="form-control" id="sedess" onchange="ShowSelected();">
            <?php
            //traer sedes
            include "../conexion.php";
            $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
            $result = mysqli_num_rows($query);

            while ($row = mysqli_fetch_assoc($query)) {
              $idsede = $row['idsede'];
              $sede = $row['nombre'];
            ?>
              <option value="<?php echo $idsede; ?>"> <?php echo $sede; ?> </option>

            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="mi_busqueda2();">Buscar</button>
      </div>
    </div>
  </div>
</div>

<!--Modal Sedes -->
<div class="modal" tabindex="-1" id="sede">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar por Sedes</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>
      <div class="modal-body">
        <p>Seleccionar una Sede.</p>
        <div class="form-group">
          <label for="sede">Sedes</label>
          <select name="sede" class="form-control" id="sedess" onchange="ShowSelected();">
            <?php
            //traer sedes
            include "../conexion.php";
            $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
            $result = mysqli_num_rows($query);

            while ($row = mysqli_fetch_assoc($query)) {
              $idsede = $row['idsede'];
              $sede = $row['nombre'];


            ?>

              <option value="<?php echo $idsede; ?>"> <?php echo $sede; ?> </option>

            <?php



            }

            ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="mi_busqueda2();">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal para eliminar inscripción -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar Inscripción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas remover esta inscripción?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para reinscribir inscripción -->
<div class="modal fade" id="modalReinscribir" tabindex="-1" role="dialog" aria-labelledby="modalReinscribirLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalReinscribirLabel">Reinscribir Estudiante</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Deseas reinscribir a este estudiante?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmReinscribir">Reinscribir</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para mostrar alertas -->
<div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog" aria-labelledby="modalAlertaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAlertaLabel">Alerta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="mensajeAlerta">
        <!-- Aquí irá el mensaje de alerta -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<!-- ----------------- EMPIEZA CODIGO AJAX --------------------------------->
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip()
  })

  // Función para mostrar un mensaje de alerta en el modal
  function mostrarAlerta(mensaje, modalOrigen) {
    // Cierra el modal de origen antes de mostrar la alerta
    $(modalOrigen).modal('hide');

    // Coloca el mensaje dentro del modal de alerta
    $('#mensajeAlerta').text(mensaje);

    // Muestra el modal de alerta
    $('#modalAlerta').modal('show');

    // Hacer que el modal permanezca visible durante 3 segundos (puedes ajustar el tiempo)
    setTimeout(function() {
      // Cerrar el modal de alerta después de 3 segundos
      $('#modalAlerta').modal('hide');

      // Recargar la página después de cerrar la alerta
      location.reload();
    }, 3000); // 3000 milisegundos = 3 segundos
  }

  // Función para eliminar inscripción
  function eliminar(idInscripcion) {
    // Mostrar el modal de eliminación
    $('#modalEliminar').modal('show');

    // Confirmar eliminación
    $('#confirmEliminar').off('click').on('click', function() {
      $.ajax({
        url: 'eliminar_alumno_cuotas.php',
        type: 'POST',
        data: {
          idInscripcion: idInscripcion
        },
        success: function(response) {
          // Llamamos a mostrarAlerta y le pasamos el modal de origen
          mostrarAlerta(response, '#modalEliminar');
        },
        error: function() {
          // Llamamos a mostrarAlerta en caso de error
          mostrarAlerta("Error al intentar eliminar la inscripción.", '#modalEliminar');
        }
      });
    });
  }

  // Función para reinscribir
  function reinscribir(idInscripcion) {
    // Mostrar el modal de reinscripción
    $('#modalReinscribir').modal('show');

    // Confirmar reinscripción
    $('#confirmReinscribir').off('click').on('click', function() {
      $.ajax({
        url: 'reinscribir_alumno.php',
        type: 'POST',
        data: {
          idInscripcion: idInscripcion
        },
        success: function(response) {
          // Llamamos a mostrarAlerta y le pasamos el modal de origen
          mostrarAlerta(response, '#modalReinscribir');
        },
        error: function() {
          // Llamamos a mostrarAlerta en caso de error
          mostrarAlerta("Error al intentar reinscribir al estudiante.", '#modalReinscribir');
        }
      });
    });
  }

  function total_alumnos() {

    buscar = document.getElementById('cursos').value;

    var parametros = {
      "total_alumnos": buscar,
      "accion": "4"

    };
    $.ajax({
      data: parametros,
      dataType: 'json',
      url: 'datos_estadisticas.php',
      type: 'POST',

      error: function() {
        alert("Error");
      },

      success: function(valores) {
        $("#total").val(valores.total);
      }
    })
  }

  function mi_busqueda() {
    var buscar = document.getElementById('cursos').value;

    var parametros = {
      "mi_busqueda": buscar,
      "sede": $("#sede").val(),
      "accion": "4"
    };

    $.ajax({
      data: parametros,
      url: 'datos_estadisticas.php',
      type: 'POST',

      beforeSend: function() {
        $('#mostrar_mensaje').html("Cargando datos...");
      },

      success: function(mensaje, valores) {
        $('#mostrar_mensaje').html(mensaje); // Actualizamos el mensaje
        total_alumnos(); // Llamamos a la función total_alumnos

        // Inicializamos los tooltips después de que la tabla haya sido cargada
        setTimeout(function() {
          $('[data-toggle="tooltip"]').tooltip(); // Activamos los tooltips
        }, 100); // Retraso para asegurar que la tabla esté completamente cargada
      },

      error: function(jqXHR, textStatus, errorThrown) {
        $('#mostrar_mensaje').html("Hubo un error en la solicitud: " + textStatus);
      }
    });
  }

  function mi_busqueda2() {

    buscar = document.getElementById('sedess').value;
    var parametros = {

      "mi_busqueda2": buscar,
      "accion": "4"
    };

    $.ajax({
      data: parametros,
      url: 'datos_estadisticas.php',
      type: 'POST',

      beforesend: function() {
        $('#mostrar_alumnos').html("Mensaje antes de Enviar");

      },

      success: function(mensaje) {
        $('#mostrar_mensaje').html(mensaje);
        total_alumnos_sede();
      }
    });
  }

  function total_alumnos_sede() {
    buscar = document.getElementById('sedess').value;
    var parametros = {
      "total_alumnos_sede": buscar,
      "accion": "4"

    };
    $.ajax({
      data: parametros,
      dataType: 'json',
      url: 'datos_estadisticas.php',
      type: 'POST',

      error: function() {
        alert("Error");
      },

      success: function(valores) {
        $("#total").val(valores.total);


      }
    })
  }
</script>
<?php include_once "includes/footer.php"; ?>