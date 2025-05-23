<?php include_once "includes/header.php";

require("../conexion.php");
require("FacturaSecuencia.php");
require_once("RolesHandler.php");

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

if ($permiso === "inscripcion") {
  include_once 'modal_curso.php';
  include_once 'modal_cuotas.php';
}

$dni = '0';
$dniP = '0';
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}

if (isset($_GET['alert'])) {
  echo $_GET['alert'];
}

if (isset($_GET['id'])) {
  $ida = $_GET['id'];
  $resultados = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno = '$ida'");
  while ($consulta = mysqli_fetch_array($resultados)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $dni = $consulta['dni'];
    //echo $dni;

  }
}
if (isset($_GET['idP'])) {
  $idP = $_GET['idP'];
  $resultados = mysqli_query($conexion, "SELECT * FROM profesor WHERE idprofesor = '$idP'");
  while ($consulta = mysqli_fetch_array($resultados)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $dniP = $consulta['dni'];
    //echo $dni;

  }
}
//buscar sede segun usuario
$rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
while ($row = mysqli_fetch_array($rs)) {
  //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
  $sede = $row['nombre'];
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");

// Lógica de inserción del alumno
if (isset($_POST['dni']) && isset($_POST['nombre']) && isset($_POST['apellido'])) {
  $dni = $_POST['dni'];
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $direccion = $_POST['direccion'];
  $celular = $_POST['celular'];
  $email = $_POST['email'];
  $tutor = $_POST['tutor'];
  $contacto = $_POST['contacto'];
  $idsede = $_POST['sedes'];

  $alert = "";
  $alertEliminar = "";
  if (empty($dni) || empty($nombre) || empty($apellido) || empty($tutor) || empty($contacto)) {
    $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';
  } else {
    // Verificar si el alumno ya existe
    $query = mysqli_query($conexion, "SELECT * FROM alumno WHERE dni = '$dni'");
    $result = mysqli_fetch_array($query);
    if ($result > 0) {
      $alert = '<div class="alert alert-warning" role="alert">
                        El Estudiante ya existe
                    </div>';
    } else {
      // Insertar nuevo alumno
      $query_insert = mysqli_query($conexion, "INSERT INTO alumno(dni, nombre, apellido, direccion, celular, email, tutor, contacto, idsede, estado) 
                VALUES('$dni', '$nombre', '$apellido', '$direccion', '$celular', '$email', '$tutor', '$contacto', '$idsede',1)");

      if ($query_insert) {
        // Obtener el ID del último alumno insertado
        $last_inserted_id = mysqli_insert_id($conexion);

        // Depurar: Mostrar el ID del último registro insertado
        //echo "Último ID insertado: " . $last_inserted_id;

        // Obtener los datos del último alumno insertado
        $query_last = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno = '$last_inserted_id'");
        $last_alumno = mysqli_fetch_assoc($query_last);

        // Depurar: Mostrar los datos del último alumno insertado
        //echo "Datos del último alumno insertado: " . print_r($last_alumno, true);

        // Mostrar mensaje de éxito
        $alert = '<div class="alert alert-success" role="alert">
            Alumno Registrado
          </div>';

        // Cargar los datos del último alumno insertado en los campos
        echo "<script>
          window.onload = function() {
            document.getElementById('dni').value = '{$last_alumno['dni']}';
            document.getElementById('nombre').value = '{$last_alumno['nombre']}';
            document.getElementById('apellido').value = '{$last_alumno['apellido']}';
            document.getElementById('alumno_id').value = '{$last_alumno['idalumno']}';
          }
        </script>";
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
            Error al registrar el Estudiante
          </div>';
      }
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'eliminar_inscripcion') {
  // Recoger los datos del formulario
  $clave = trim($_POST['password'] ?? '');

  $idinscripcion = intval($_POST['idinscripcion'] ?? 0);

  // Validación de los datos
  if (empty($clave) || $idinscripcion <= 0) {
    $alertEliminar = '<div class="alert alert-danger" role="alert">
                    Datos inválidos. Por favor, intente de nuevo.
                  </div>';
  } else {
    // Recoger la contraseña ingresada
    $clave = trim($_POST['password'] ?? '');
    $idinscripcion = intval($_POST['idinscripcion'] ?? 0);

    // Validación de datos
    if (empty($clave) || $idinscripcion <= 0) {
      $error_message = "Datos inválidos. Por favor, intente de nuevo.";
      echo $error_message;
      exit;
    }

    // Buscar usuario por contraseña (md5) y verificar si tiene idrol = 1 (administrador)
    $sqlPassword = mysqli_query($conexion, "SELECT clave, idrol FROM usuario WHERE clave = '" . md5($clave) . "'");

    // Verificar si la consulta fue exitosa
    if (!$sqlPassword) {
      echo "Error de consulta a la base de datos: " . mysqli_error($conexion);
      exit;
    }

    // Obtener los datos del usuario
    $user = mysqli_fetch_assoc($sqlPassword);

    // Depuración
    // echo "Clave de la base de datos: " . $user['clave'];
    // echo "<br>"; // Depuración
    // echo "Clave ingresada: " . $clave;
    // echo "<br>";

    // Verificar si la contraseña es correcta y si el rol es admin (idrol = 1)
    if ($user && $user['idrol'] == 1) {
      $query_delete = mysqli_query($conexion, "DELETE FROM inscripcion WHERE idinscripcion = '$idinscripcion'");

      if ($query_delete) {
        $alertEliminar = '<div class="alert alert-success" role="alert">
                      Inscripción eliminada correctamente.
                    </div>';
      } else {
        $alertEliminar = '<div class="alert alert-danger" role="alert">
                      Error al eliminar la inscripción. Intente más tarde.
                    </div>';
      }
    } else {
      $alertEliminar = '<div class="alert alert-danger" role="alert">
                  Clave incorrecta o el usuario no tiene permisos de administrador.
                </div>';
    }
  }
}
?>
<!-- Enlace para abrir el modal -->
<?php echo isset($alertEliminar) ? $alertEliminar : ''; ?>
<h3>
  <center>Inscribir Estudiantes</center>
</h3><br>
<div class="container">

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
  <br>
  <div class="btn-group" role="group" aria-label="Botones de acción">
    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#miModal">
      <i class="fa fa-graduation-cap" aria-hidden="true"></i> Buscar Estudiante
    </button>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo_alumno">
      <i class="fa fa-user-plus" aria-hidden="true"></i> Añadir Estudiante
    </button>
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

  <form>
    <br><label><i class="fa fa-graduation-cap" aria-hidden="true"></i> Estudiante</label>
    <br>
    <div class="row">
      <div class="col" style="width:5%;">
        <input type="text" class="form-control" placeholder="CÉDULA" aria-label="First name" id="dni" onkeydown="buscar_datos_alumnos(event)">
        <small class="form-text text-muted">Presiona <b>Enter</b> para buscar.</small>
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
    <!-- Campo oculto para el ID del alumno -->
    <input type="hidden" id="alumno_id" value="">
    <!-- Campo oculto para el ID de la sede -->
    <input type="hidden" id="sede_id" value="">
  </form>


  <!--<button type="button"  data-toggle="modal" data-target="#miModalP"><i class="fa fa-users" aria-hidden="true"></i>Buscar Profesor</button>-->
  <!-- Modal -->
  <div id="miModalP" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
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
                <input type="text" placeholder="Buscar Profesor o Enter para ver lista" id="cuadro_buscarP" class="form-control" onkeypress="mi_busquedaP();">
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
    <button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#miModalP">
      <i class="fa fa-users" aria-hidden="true"></i> Buscar Profesor
    </button>
  </form>

  <div class="container mt-5">
    <div class="card w-100 mx-auto shadow">
      <div class="card-header bg-primary text-white text-center">
        Datos Inscripción
      </div>
      <div class="card-body">
        <!-- Sede -->
        <div class="row mb-4">
          <div class="col" hidden>
            <label for="sede"><i class="fa fa-university" aria-hidden="true"></i> SEDE</label>
            <input style="font-size: 16px; text-transform: uppercase; color: red;" value="<?php echo $sede; ?>" id="sede" readonly="readonly" class="form-control">
          </div>
        </div>

        <!-- Botón Buscar Curso -->
        <div class="row mb-4">
          <div class="col text-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCurso">
              <i class="fa fa-search-minus" aria-hidden="true"></i> Buscar Curso
            </button>
          </div>
        </div>

        <!-- Fechas -->
        <div class="row mb-4">
          <div class="col-md-6 text-center">
            <label for="fechaComienzo">
              <i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Inicio
            </label>
            <input type="date" id="fechaComienzo" name="comienzo" class="form-control" readonly="readonly">
          </div>
          <div class="col-md-6 text-center">
            <label for="fechaTermino">
              <i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Término
            </label>
            <input type="date" id="fechaTermino" name="termino" class="form-control" readonly="readonly">
          </div>
        </div>

        <!-- Información del Curso -->
        <div class="row mb-4">
          <div class="col">
            <input type="hidden" class="form-control" id="idCurso" readonly="readonly">
            <input type="text" class="form-control" placeholder="Nombre del Curso" id="curso" onkeypress="buscar_datos_cursos();">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Duración" id="duracion" readonly="readonly">
          </div>
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="text" class="form-control" placeholder="Precio" id="precio" readonly="readonly">
            </div>
          </div>
        </div>

        <!-- Profesor -->
        <label class="mb-2"><i class="fa fa-users" aria-hidden="true"></i> Profesor</label>
        <div class="row mb-4">
          <div class="col">
            <input type="text" class="form-control" placeholder="CÉDULA" aria-label="First name" id="dniP" readonly="readonly">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Nombre" aria-label="First name" id="nombreP" readonly="readonly">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Apellido" aria-label="Last name" id="apellidoP" readonly="readonly">
          </div>
        </div>

        <!-- Añadir Detalle -->
        <div class="row mb-4">
          <div class="col">
            <div class="form-check d-flex align-items-center">
              <input class="form-check-input" type="checkbox" id="detalleCheckbox">
              <label class="form-check-label align-middle ms-2" for="detalleCheckbox">
                Añadir Detalle
              </label>
            </div>
            <textarea id="detalleTextarea" name="detalle" class="form-control mt-2" rows="4" placeholder="Escribe el detalle aquí..." style="display: none;"></textarea>
          </div>
        </div>

        <!-- Incluir Uniforme -->
        <div class="row mb-4">
          <div class="col">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="uniformeCheckbox" checked>
              <label class="form-check-label" for="uniformeCheckbox">Uniforme:</label>
            </div>
          </div>
        </div>

        <!-- Selección de Uniforme (Oculto por defecto) -->
        <div class="row mb-4" id="uniformeContainer" style="display: none;">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" class="form-control" value="<?php echo isset($uniforme) ? $uniforme['nombre'] : ''; ?>" id="uniformeSeleccionado" placeholder="Seleccione un uniforme..." disabled>
              <button type="button" class="btn btn-primary" id="btnBuscarUniforme" data-toggle="modal" data-target="#modalUniformes">Buscar</button>
            </div>
            <input type="hidden" name="id_uniforme" id="id_uniforme" value="<?php echo isset($uniforme) ? $uniforme['id_uniforme'] : ''; ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<br>

<div class="container">
  <br>
  <div class="row">

    <div class="col ">
      <label for="sala">Salas Disponibles</label>
      <select name="sala" class="form-control" id="salas">
        <?php
        //traer salas

        include "../conexion.php";
        $query = mysqli_query($conexion, "SELECT sala FROM sala");
        $result = mysqli_num_rows($query);

        while ($row2 = mysqli_fetch_assoc($query)) {
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

<div class="container">
  <div class="row">
    <!-- Total Inscripción -->
    <div class="col text-center">
      <br>
      <label for="total" class="d-block" style="font-size: 25px;">Total Inscripción:</label>
      <div class="input-group mx-auto" style="width: 30%;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="font-size: 30px;">$</span>
        </div>
        <input type="text" class="form-control text-center" id="total" name="total" style="font-size: 30px;" readonly>
      </div>
    </div>
  </div>

  <!-- Selector de tipo de impresión -->
  <div class="row justify-content-center mt-3">
    <div class="col-auto d-flex align-items-center">
      <label for="tipo-impresion" class="form-label mb-0 mr-2 h5">Tipo de Impresión:</label>
      <select id="tipo-impresion" name="tipo-impresion" class="form-control w-auto">
        <option value="A4">A4</option>
        <option value="Ticket">Ticket</option>
      </select>
    </div>
  </div>

  <!-- Botones de acción -->
  <div class="row justify-content-center mt-3">
    <div class="col-auto">
      <input type="button" value="Inscribir Estudiante" class="btn btn-primary" name="btn_inscribir" onclick="guardar();">
    </div>
    <div class="col-auto">
      <input type="button" value="Limpiar" class="btn btn-danger" name="btn_limpiar" onclick="limpiar();">
    </div>
  </div>

  <div class="resultados"></div>
</div>
</form>
<br>

<div class="table-responsive">
  <br>
  <h2>
    <center>Lista De Inscripciones</center>
  </h2>
  <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
    <thead class="thead-dark">
      <tr>

        <th>CÉDULA</th>
        <th>N° Factura</th>
        <th>Nombre</th>
        <th>Curso</th>
        <th>Profesor</th>
        <th>Fecha y Hora de Inscripcion</th>
        <th>Fecha de Inicio</th>
        <th hidden>Sede</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php ?>
      <?php
      include "../conexion.php";
      if ($sede == "GENERAL") {
        $query = mysqli_query($conexion, "SELECT 
            idinscripcion, 
            inscripcion.nroFactura, 
            alumno.dni, 
            alumno.nombre AS alumno, 
            curso.nombre AS curso, 
            profesor.nombre AS profesor, 
            fecha, 
            inscripcion.fechacomienzo, 
            sedes.nombre AS sede, 
            inscripcion.estado 
        FROM inscripcion
        INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
        INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
        LEFT JOIN profesor ON curso.idprofesor = profesor.idprofesor  -- Cambio aquí
        INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
        ORDER BY idinscripcion DESC");
      } else {
        $query = mysqli_query($conexion, "SELECT 
            idinscripcion, 
            inscripcion.nroFactura, 
            alumno.dni, 
            alumno.nombre AS alumno, 
            curso.nombre AS curso, 
            profesor.nombre AS profesor, 
            fecha, 
            inscripcion.fechacomienzo, 
            sedes.nombre AS sede, 
            inscripcion.estado 
        FROM inscripcion
        INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
        INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
        LEFT JOIN profesor ON curso.idprofesor = profesor.idprofesor  -- Cambio aquí
        INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
        WHERE sedes.nombre = '$sede' 
        ORDER BY idinscripcion DESC");
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
            <td><?php echo $data['nroFactura']; ?></td>
            <td><?php echo $data['alumno']; ?></td>
            <td><?php echo $data['curso']; ?></td>
            <td><?php echo isset($data['profesor']) && $data['profesor'] !== null ? $data['profesor'] : 'Sin asignar'; ?></td>
            <td><?php echo $data['fecha']; ?></td>
            <td><?php echo $data['fechacomienzo']; ?></td>
            <td hidden><?php echo $data['sede']; ?></td>
            <td>
              <div class="btn-group">
                <a class="btn btn-warning" data-toggle="modal" data-target="#impresionModal" data-idinscripcion="<?php echo $data['idinscripcion']; ?>" onclick="capturarIdInscripcion(this)">
                  <i class="fa fa-file" color="white" aria-hidden="true"></i>
                </a>

                <?php
                // Suponemos que $_SESSION['idUser'] es el ID del usuario autenticado
                // Verificamos el rol del usuario actual
                $rolesHandler = new RolesHandler($conexion);
                $userRole = $rolesHandler->validarRol($_SESSION['idUser']);

                if ($userRole === 'admin') {
                  // El admin puede eliminar directamente
                  echo '
                      <button class="btn btn-danger" data-toggle="modal" data-target="#confirmarEliminacionModal" onclick="setInscripcionId(' . $data['idinscripcion'] . ')">
                        <i class="fas fa-trash-alt"></i>
                      </button>';
                } else {
                  // Usuarios no autorizados necesitan confirmación del admin
                  echo '
                    <button class="btn btn-danger" data-toggle="modal" data-target="#adminConfirmacionModal"
                      onclick="setInscripcionId(' . $data['idinscripcion'] . ')">
                      <i class="fas fa-trash-alt"></i> Solicitar Admin
                    </button>';
                }
                ?>
              </div>
            </td>
          </tr>
      <?php }
      } ?>
    </tbody>
  </table>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="eliminar_inscripcion.php">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar esta inscripción?</p>
          <input type="hidden" name="idinscripcion" id="idinscripcion">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="eliminar_inscripcion_permiso.php">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar esta inscripción?</p>
          <input type="hidden" name="idinscripcion" id="confirmarIdInscripcion">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="adminConfirmacionModal" tabindex="-1" aria-labelledby="adminConfirmacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-admin-confirmacion" method="POST" action="inscripcion.php">
      <input type="hidden" name="action" value="eliminar_inscripcion">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminConfirmacionModalLabel">Confirmación de administrador</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idinscripcion" name="idinscripcion">
          <div class="form-group">
            <label for="password">Ingrese la contraseña del administrador:</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Confirmar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal de confirmación de contraseña -->
<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarModalLabel">Confirmar contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="eliminar_inscripcion.php?id=<?php echo $data['idinscripcion']; ?>">
          <label for="password">Ingrese su contraseña:</label>
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="submit" class="btn btn-danger mt-2">Confirmar Eliminación</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="impresionModal" tabindex="-1" role="dialog" aria-labelledby="impresionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="impresionModalLabel">Seleccionar Tipo de Impresión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
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
        <button type="button" class="btn btn-primary" onclick="generarImpresion( )">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<div id="nuevo_alumno" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="my-modal-title">Nuevo Estudiante</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" autocomplete="off">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="cantidad">CÉDULA</label>
            <input type="number" placeholder="Ingrese CÉDULA" class="form-control" name="dni" id="dni">
          </div>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" placeholder="Ingrese Apellido" name="apellido" id="apellido">
          </div>
          <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" placeholder="Ingrese Dirección" name="direccion" id="direccion">
          </div>
          <div class="form-group">
            <label for="celular">Celular</label>
            <input type="number" placeholder="Ingrese celular" class="form-control" name="celular" id="celular">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="email" id="email">
          </div>
          <div class="form-group">
            <label for="tutor">Tutor</label>
            <input type="text" class="form-control" placeholder="Ingrese Nombre" name="tutor" id="tutor">
          </div>
          <div class="form-group">
            <label for="contacto">Celular Contacto</label>
            <input type="number" placeholder="Ingrese Contacto" class="form-control" name="contacto" id="contacto">
          </div>
          <div class="form-group" hidden>
            <label for="sedes">Sede</label>
            <select name="sedes" class="form-control">
              <?php
              // Traer sedes
              $rs = mysqli_query($conexion, "SELECT sedes.nombre, sedes.idsede FROM usuario 
                                  INNER JOIN sedes ON usuario.idsede = sedes.idsede WHERE usuario.idusuario = '$id_user'");
              while ($row = mysqli_fetch_array($rs)) {
                $sede = $row['nombre'];
              }
              if ($sede == "GENERAL") {
                $query = mysqli_query($conexion, "SELECT nombre, idsede FROM sedes");
              } else {
                $query = mysqli_query($conexion, "SELECT nombre, idsede FROM sedes WHERE sedes.nombre = '$sede'");
              }
              while ($row = mysqli_fetch_assoc($query)) {
                // Asegúrate de usar el idsede como value
                echo "<option value='{$row['idsede']}'>{$row['nombre']}</option>";
              }
              ?>
            </select>
          </div>
          <input type="submit" name="submit" value="Guardar Estudiante" class="btn btn-primary">
        </form>
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

<!-- Modal -->
<div class="modal fade" id="respuestaModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div id="modalHeader" class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalMessage">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<!-- Funcion buscar curso mejorada-->
<script src="js/curso_modal_scripts.js"></script>

<script src="js/busqueda_anterior.js"></script>
<!-- Funcion ajax jason Buscar Profesor-->
<script src="js/inscripcion.js"></script>

<!-- Funcion ajax jason Buscar Curso-->
<script src="js/buscar_curso_anterior.js"></script>

<!-- Funcion ajax jason Inscribir-->
<script src="js/guardar_inscripcion.js"></script>

<?php if ($permiso === "inscripcion") : ?>
  <script>
    // Esperar a que el DOM esté completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
      const detalleCheckbox = document.getElementById("detalleCheckbox");
      const detalleTextarea = document.getElementById("detalleTextarea");

      // Evento de cambio en el checkbox
      detalleCheckbox.addEventListener("change", function() {
        if (this.checked) {
          // Si el checkbox está marcado, cargar el detalle desde la API
          cargarDetalleFac().then((detalleFac) => {
            detalleTextarea.value = detalleFac; // Asignar el valor al textarea
            detalleTextarea.style.display = "block"; // Mostrar el textarea
          }).catch((error) => {
            console.error("Error al cargar el detalle:", error);
            detalleTextarea.value = "Error al cargar el detalle.";
            detalleTextarea.style.display = "block";
          });
        } else {
          // Si el checkbox no está marcado, ocultar el textarea
          detalleTextarea.value = ""; // Limpiar el contenido
          detalleTextarea.style.display = "none"; // Ocultar el textarea
        }
      });

      /**
       * Función para cargar el detalle_fac desde la API.
       * @returns {Promise<string>} - El valor de detalle_fac.
       */
      function cargarDetalleFac() {
        return new Promise((resolve, reject) => {
          // Realizar la solicitud AJAX
          $.ajax({
            url: "cargar_configuracion.php", // URL de la API
            method: "GET",
            dataType: "json", // Esperamos una respuesta JSON
            success: function(response) {
              if (Array.isArray(response) && response.length > 0) {
                // Tomar el primer registro (si hay varios)
                const detalleFac = response[0].detalle_fac || "";
                resolve(detalleFac); // Resolvemos con el valor de detalle_fac
              } else {
                reject("No se encontraron datos de configuración.");
              }
            },
            error: function(xhr, status, error) {
              reject("Error en la solicitud AJAX: " + error);
            },
          });
        });
      }
    });
  </script>
<?php endif; ?>
<script>
  function verificarCuotas() {
    const alumnoId = document.getElementById("alumno_id").value;

    // Verificar si el campo tiene un valor válido
    if (!alumnoId) {
      alert("Por favor, seleccione un estudiante primero.");

      return;
    }

    // Petición AJAX para verificar cuotas
    fetch(`verificar_cuotas.php?idAlumno=${alumnoId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error("Error en la respuesta de la petición.");
        }
        return response.json(); // Obtener la respuesta como JSON
      })
      .then(data => {
        // Si hay cuotas pendientes, mostrar el modal
        console.log(data);
        if (data.cuotasPendientes > 0) {
          const modal = document.getElementById("modalCuotasPendientes");
          modal.classList.add("show");
          modal.style.display = "block";
          document.getElementById("cuotasPendientes").innerText = data.cuotasPendientes;

          // Agregar evento para cerrar el modal
          modal.querySelector("[data-dismiss='modal']").addEventListener("click", () => {
            modal.classList.remove("show");
            modal.style.display = "none";
          });
        } else {
          console.log("El estudiante no tiene cuotas pendientes.");
        }
      })
      .catch(error => {
        console.error("Error verificando cuotas:", error);
      });
  }

  // Escuchar cambios en el campo `alumno_id` usando el evento input
  document.getElementById("alumno_id").addEventListener("input", verificarCuotas);
</script>

<script>
  let timeout;
  let precioInscripcion = 0; // Precio de inscripción por defecto
  let precioUniforme = 0; // Precio del uniforme por defecto

  $(document).ready(function() {
    inicializarValores();
    configurarEventos();
  });

  function inicializarValores() {
    // Inicializa los valores iniciales de los elementos
    $("#total").val("0.00");
    document.getElementById("alumno_id").value = "0";
    verificarCuotas();
  }

  function configurarEventos() {
    const uniformeCheckbox = document.getElementById("uniformeCheckbox");
    const uniformeContainer = document.getElementById("uniformeContainer");
    const totalInput = document.getElementById("total");

    // Mostrar u ocultar el contenedor según el estado inicial del checkbox
    uniformeContainer.style.display = uniformeCheckbox.checked ? "block" : "none";

    // Actualizar el total según el estado inicial del checkbox
    actualizarTotal(uniformeCheckbox.checked);

    // Evento de cambio en el checkbox
    uniformeCheckbox.addEventListener("change", function() {
      uniformeContainer.style.display = this.checked ? "block" : "none";
      actualizarTotal(this.checked);
    });
  }

  function actualizarTotal(checkboxChecked) {
    const total = checkboxChecked ? precioInscripcion + precioUniforme : precioInscripcion;
    document.getElementById("total").value = formatoMoneda(total);
  }

  function formatoMoneda(valor) {
    return valor.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
  }

  function buscarUniforme() {
    clearTimeout(timeout);

    timeout = setTimeout(function() {
      const buscar = document.getElementById("cuadro_buscar_uniforme").value;
      const parametros = {
        buscar_uniforme: buscar,
        accion: "buscar_uniformes",
      };

      $.ajax({
        data: parametros,
        url: "buscar_uniformes.php",
        type: "POST",
        beforeSend: function() {
          $("#mostrar_uniformes").html("Buscando...");
        },
        success: function(respuesta) {
          $("#mostrar_uniformes").html(respuesta);
        },
        error: function(xhr, status, error) {
          console.log("Error: " + error);
        },
      });
    }, 500); // Retardo de 500 ms
  }

  function seleccionarUniforme(id, nombre, precio, stock) {
    $("#uniformeSeleccionado").val(nombre);
    $("#id_uniforme").val(id);

    precioUniforme = parseFloat(precio) || 0;
    console.log("Último precio seleccionado:", precioUniforme);

    // Obtenemos si el checkbox está marcado
    const checkboxChecked = document.getElementById("uniformeCheckbox").checked;

    // Actualizamos el total con base en el checkbox
    actualizarTotal(checkboxChecked);

    // Cerramos el modal
    $("#modalUniformes").modal("hide");
  }


  function obtenerPrecioInscripcion() {
    // Esta función debe devolver el precio de inscripción desde donde corresponda
    return 100.0; // Ejemplo de valor
  }

  function obtenerPrecioUniforme() {
    // Esta función debe devolver el precio del uniforme desde donde corresponda
    return precioUniforme;
  }
</script>

<script>
  // Captura el ID de la inscripción y lo coloca en el formulario
  document.addEventListener('DOMContentLoaded', function() {
    const confirmarModal = document.getElementById('confirmarEliminacionModal');
    const adminModal = document.getElementById('adminConfirmacionModal');

    confirmarModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const idInscripcion = button.getAttribute('data-idinscripcion');
      const inputId = document.getElementById('confirmarIdInscripcion');
      inputId.value = idInscripcion;
    });

    adminModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const idInscripcion = button.getAttribute('data-idinscripcion');
      setInscripcionId(idInscripcion);
    });
  });

  function setInscripcionId(idInscripcion) {
    const inputId = document.getElementById('idinscripcion');
    inputId.value = idInscripcion;
  }
</script>
<?php include_once "includes/footer.php"; ?>