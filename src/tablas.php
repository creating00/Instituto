<?php
require("../conexion.php");
require_once "CursoBusqueda.php";

if (isset($_POST['listaExamen'])) {

  echo
  '   
      <h5><center>Lista de Examenes Cobrados</center></h5>    
      <table id="tablaUsuario" class="table table-hover">
        <tr>
          <th scope="col">#</th>
          <th scope="col">CÉDULA</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Curso</th>
          <th scope="col">Fecha</th>
          <th scope="col">Total</th>
          <th scope="col">Operador</th>
        </tr>
          
    ';
  $dni = $_POST['dni'];
  $sede = $_POST['sede'];

  if ($sede == "GENERAL") {

    $resultados = mysqli_query($conexion, "SELECT idexamen, alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.fecha, total, usuario.usuario'usuario' FROM examen 
              INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
              INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
              INNER JOIN curso on inscripcion.idcurso=curso.idcurso
              INNER JOIN usuario on examen.idusuario=usuario.idusuario");
  } else {

    $resultados = mysqli_query($conexion, "SELECT idexamen, alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.fecha, total, usuario.usuario'usuario' FROM examen 
            INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
            INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
            INNER JOIN curso on inscripcion.idcurso=curso.idcurso
            INNER JOIN usuario on examen.idusuario=usuario.idusuario WHERE examen.sede='$sede' and alumno.dni='$dni'");
  }
  while ($consulta = mysqli_fetch_array($resultados)) {
    echo "<tr>";
    echo "<td>" . $consulta['idexamen'] . "</td>";
    echo "<td>" . $consulta['dni'] . "</td>";
    echo "<td>" . $consulta['nombre'] . "</td>";
    echo "<td>" . $consulta['apellido'] . "</td>";
    echo "<td>" . $consulta['curso'] . "</td>";
    echo "<td>" . $consulta['fecha'] . "</td>";
    echo "<td>" . $consulta['total'] . "</td>";
    echo "<td>" . $consulta['usuario'] . "</td>";
    echo "<td><form action='eliminar_examen.php?id=" . $consulta['idexamen'] . "' method='post' class='confirmar d-inline'>
                                    <button class='btn btn-danger' type='submit'><i class='fas fa-trash-alt'></i> </button>
                      </form></td>";
    //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
    //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
    //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
    echo "</tr>";
  }
}


if (isset($_POST['mi_busqueda_usuario'])) {
  echo
  '   
        
		<table id="tablaUsuario" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Usuario</th>
	      <th scope="col">Nombre</th>
	      <th scope="col">Correo</th>
	    </tr>
        
	';
  $mi_busqueda_usuario = $_POST['mi_busqueda_usuario'];
  $sede = $_POST['sede'];

  //CONSULTAR
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  if ($sede == "GENERAL") {

    $resultados = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario LIKE '%$mi_busqueda_usuario%' LIMIT 5");
  } else {

    $resultados = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario LIKE '%$mi_busqueda_usuario%' and idsede='$idsede' LIMIT 5");
  }
  while ($consulta = mysqli_fetch_array($resultados)) {
    echo "<tr>";
    echo "<td>" . $consulta['idusuario'] . "</td>";
    echo "<td>" . $consulta['usuario'] . "</td>";
    echo "<td>" . $consulta['nombre'] . "</td>";
    echo "<td>" . $consulta['correo'] . "</td>";
    //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
    //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
    //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
    echo "</tr>";
  }
}

if (isset($_POST['mi_busqueda_curso'])) {
  echo
  '   
        
		<table id="tablaCurso" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">CURSO</th>
	      <th scope="col">PRECIO</th>
	      <th scope="col">DURACION</th>
        <th scope="col">SEDE</th>
        <th scope="col">INSCRIPCION</th>
	    </tr>
        
	';
  $mi_busqueda_curso = $_POST['mi_busqueda_curso'];
  $sede = $_POST['sede'];
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  if ($sede == "GENERAL") {
    $resultados = mysqli_query($conexion, "SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre'sede' FROM curso 
    inner join sedes on curso.idsede=sedes.idsede WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado=1 LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {
      echo "<tr>";
      echo "<td>" . $consulta['idcurso'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>$" . number_format($consulta['precio'], 2, '.', ',') . "</td>";
      echo "<td>" . $consulta['tipo'] . "</td>";
      echo "<td>" . $consulta['sede'] . "</td>";
      echo "<td>$" . number_format($consulta['inscripcion'], 2, '.', ',') . "</td>";
      //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  } else {

    $resultados = mysqli_query($conexion, "SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre'sede'  FROM curso
    inner join sedes on curso.idsede=sedes.idsede WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado=1 and curso.idsede='$idsede' LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {
      echo "<tr>";
      echo "<td>" . $consulta['idcurso'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>$" . number_format($consulta['precio'], 2, '.', ',') . "</td>";
      echo "<td>" . $consulta['tipo'] . "</td>";
      echo "<td>" . $consulta['sede'] . "</td>";
      echo "<td>$" . number_format($consulta['inscripcion'], 2, '.', ',') . "</td>";
      //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  }
}


if (isset($_POST['mi_busqueda'])) {
  echo
  '   
        
		<table id="tablaAlumno" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">NOMBRE</th>
	      <th scope="col">APELLIDO</th>
        <th scope="col" hidden>SEDE</th>
	    </tr>
        
	';
  $mi_busqueda = $_POST['mi_busqueda'];
  $usuario = $_POST['usuario'];
  $sede = $_POST['sede'];
  //echo "Alumnos de La Sede: ",$sede;

  //sedes
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  if ($sede == "GENERAL") {
    $resultados = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.nombre LIKE '%$mi_busqueda%' LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {
      echo "<tr>";
      echo "<td>" . $consulta['idalumno'] . "</td>";
      echo "<td>" . $consulta['dni'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>" . $consulta['apellido'] . "</td>";
      echo "<td hidden>" . $consulta['sede'] . "</td>";
      //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  } else {

    $resultados = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.idsede='$idsede' AND alumno.nombre LIKE '%$mi_busqueda%' AND alumno.estado=1 LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {
      echo "<tr>";
      echo "<td>" . $consulta['idalumno'] . "</td>";
      echo "<td>" . $consulta['dni'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>" . $consulta['apellido'] . "</td>";
      echo "<td>" . $consulta['sede'] . "</td>";
      //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  }
}

if (isset($_POST['mi_busquedaP'])) {
  echo
  '   
        
		<table id="tablaProfesor" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">NOMBRE</th>
	      <th scope="col">APELLIDO</th>
	    </tr>
        
	';
  $mi_busquedaP = $_POST['mi_busquedaP'];
  $usuario = $_POST['usuario'];
  $sede = $_POST['sede'];
  //echo "Alumnos de La Sede: ",$sede;
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  if ($sede == "GENERAL") {
    $resultados = mysqli_query($conexion, "SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
		INNER JOIN sedes on profesor.idsede=sedes.idsede WHERE profesor.nombre LIKE '%$mi_busquedaP%' OR dni LIKE '%$mi_busquedaP%' AND profesor.estado=1 LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {

      echo "<tr>";
      echo "<td>" . $consulta['idprofesor'] . "</td>";
      echo "<td>" . $consulta['dni'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>" . $consulta['apellido'] . "</td>";
      //echo "<td><a href='inscripcion.php?idP=".$consulta['idprofesor']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  } else {

    $resultados = mysqli_query($conexion, "SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
		INNER JOIN sedes on profesor.idsede=sedes.idsede WHERE profesor.nombre LIKE '%$mi_busquedaP%' AND profesor.idsede='$idsede' AND profesor.estado=1 LIMIT 5");
    while ($consulta = mysqli_fetch_array($resultados)) {
      echo "<tr>";
      echo "<td>" . $consulta['idprofesor'] . "</td>";
      echo "<td>" . $consulta['dni'] . "</td>";
      echo "<td>" . $consulta['nombre'] . "</td>";
      echo "<td>" . $consulta['apellido'] . "</td>";
      //echo "<td><a href='inscripcion.php?idP=".$consulta['idprofesor']."'><i class='fas fa-trash-alt'></i></a></td>";
      //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
      //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
      echo "</tr>";
    }
  }
}

if ($_POST['accion'] == "5") { // Acción 5 para buscar cursos por alumno
  if (!isset($_POST['accion'])) {
    die("Error: No se recibió la acción.");
  }

  $alumno_id = $_POST['alumno_id']; //aqui dice que esta el error
  $cursoBusqueda = new CursoBusqueda($conexion);
  $resultados = $cursoBusqueda->buscarCursosPorAlumno($alumno_id);

  if (mysqli_num_rows($resultados) > 0) {
    while ($data = mysqli_fetch_assoc($resultados)) {
      echo "<tr>";
      echo "<td style='display: none;'>{$data['idinscripcion']}</td>";
      echo "<td>{$data['idcurso']}</td>";
      echo "<td>{$data['nombre']}</td>";
      echo "<td>{$data['fecha_inscripcion']}</td>";
      echo "<td>{$data['fecha_inicio']}</td>";
      // Verifica si cuotas_pendientes es 0
      if ($data['cuotas_pendientes'] == 0) {
        echo "<td>{$data['fecha_fin']}</td>";
      } else {
        echo "<td>NO FINALIZÓ</td>";
      }
      echo "<td style='background-color: " . getColorCuotas($data['cuotas_pendientes']) . ";'>";
      echo "{$data['cuotas_pendientes']}";
      echo "</td>";

      // Columna de acciones con los botones
      echo "<td>";
      // Botón para imprimir
      echo "<button class='btn btn-success btn-lg' style='padding: 5px 10px;' onclick='imprimirDiploma({$data['idcurso']})'>";
      echo "<i class='fa fa-print' aria-hidden='true' style='font-size: 1.2em;'></i>";
      echo "</button> ";

      // Botón para añadir
      echo "<button class='btn btn-primary btn-lg' style='padding: 5px 10px; margin-left: 5px;' onclick='agregarDiploma({$data['idcurso']})'>";
      echo "<i class='fa fa-plus' aria-hidden='true' style='font-size: 1.2em;'></i>";
      echo "</button> ";

      // Botón para enviar
      echo "<button class='btn btn-info btn-lg' style='padding: 5px 10px; margin-left: 5px;' 
      onclick='enviarDiploma({$data['idcurso']}, \"{$data['nombre']}\")'>";
      echo "<i class='fa fa-paper-plane' aria-hidden='true' style='font-size: 1.2em;'></i>";
      echo "</button>";

      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='6' class='text-center'>No se encontraron cursos para este alumno.</td></tr>";
  }
  exit;
}

echo '</table>';
function getColorCuotas($cuotasPendientes)
{
  if ($cuotasPendientes >= 5) {
    return "red"; // Muchas cuotas pendientes (rojo)
  } elseif ($cuotasPendientes >= 1) {
    return "yellow"; // Algunas cuotas pendientes (amarillo)
  } else {
    return "green"; // Ninguna cuota pendiente (verde)
  }
}

?>
<script type="text/javascript">
  //click tabla usuario modal
  $('#tablaUsuario tr').on('click', function() {
    var dato2 = $(this).find('td:nth-child(2)').html();
    var dato3 = $(this).find('td:nth-child(3)').html();
    var dato4 = $(this).find('td:nth-child(4)').html();
    $('#usuario1').val(dato2);
    $('#nombre').val(dato3);
    $('#correo').val(dato4);
  });


  //click en tabla funcion	
  $('#tablaProfesor tr').on('click', function() {
    var dato = $(this).find('td:nth-child(2)').html();
    var dato3 = $(this).find('td:nth-child(3)').html();
    var dato4 = $(this).find('td:nth-child(4)').html();
    $('#dniP').val(dato);
    $('#nombreP').val(dato3);
    $('#apellidoP').val(dato4);
  });

  //click en tabla curso
  $('#tablaCurso tr').on('click', function() {
    var dato = $(this).find('td:nth-child(2)').html();
    var dato3 = $(this).find('td:nth-child(3)').html();
    var dato4 = $(this).find('td:nth-child(4)').html();
    var dato5 = $(this).find('td:nth-child(5)').html();
    $('#curso').val(dato);
    $('#precio').val(dato3);
    $('#duracion').val(dato4);
    $('#inscripcion').val(dato5);
  });

  $('#tablaAlumno tr').on('click', function() {

    var dato = $(this).find('td:nth-child(2)').html();
    var dato3 = $(this).find('td:nth-child(3)').html();
    var dato4 = $(this).find('td:nth-child(4)').html();
    var dato5 = $(this).find('td:nth-child(5)').html();
    var idAlumno = $(this).find('td:nth-child(1)').html();
    $('#dni').val(dato);
    $('#nombre').val(dato3);
    $('#apellido').val(dato4);
    $('#sede').val(dato5);

    // Verificar si el campo 'alumno_id' existe
    const alumnoIdElement = document.getElementById("alumno_id");

    obtenerAlumno(idAlumno);

    if (alumnoIdElement) {
      // Asignar valor al campo oculto 'alumno_id'
      alumnoIdElement.value = idAlumno;

      // Verificar si tiene valor asignado y llamar a verificarCuotas
      if (alumnoIdElement.value) {
        //verificarCuotas();

        if (typeof verificarCuotas === 'function') {
          verificarCuotas();
        } else {
          console.log('El método cargarTablaCursos no está definido.');
        }

        // Validar si el método cargarTablaCursos está definido antes de llamarlo
        if (typeof cargarTablaCursos === 'function') {
          cargarTablaCursos(idAlumno);
        } else {
          console.log('El método cargarTablaCursos no está definido.');
        }
      }
    } else {
      console.log('El campo "alumno_id" no está presente en el DOM.');
    }
  });

  function obtenerAlumno(idalumno) {
    // Crear un objeto FormData para enviar los datos
    const datos = new FormData();
    datos.append("idalumno", idalumno);

    // Hacer la llamada al servidor
    fetch("obtenerAlumno.php", {
        method: "POST",
        body: datos,
      })
      .then(response => response.json()) // Convertir la respuesta a JSON
      .then(data => {
        if (data.error) {
          console.error("Error:", data.error);
        } else {
          // Verificar si los elementos están en el DOM antes de actualizar
          if (document.getElementById("direccion")) {
            document.getElementById("direccion").value = data.direccion;
          }
          if (document.getElementById("celular")) {
            document.getElementById("celular").value = data.celular;
          }
          if (document.getElementById("email")) {
            document.getElementById("email").value = data.email;
          }
          if (document.getElementById("tutor")) {
            document.getElementById("tutor").value = data.tutor;
          }
          if (document.getElementById("contacto")) {
            document.getElementById("contacto").value = data.contacto;
          }
          if (document.getElementById("sedeAlumno")) {
            document.getElementById("sedeAlumno").value = data.sede;
          }


          //console.log("Alumno encontrado:", data);
        }
      })
      .catch(error => {
        console.error("Error en la solicitud:", error);
      });
  }

  function imprimirDiploma(idCurso) {
    const alumnoId = document.getElementById('alumno_id').value;

    if (!alumnoId) {
      alert("Por favor, selecciona un alumno antes de continuar.");
      return;
    }

    const url = `obtener_diploma.php?idCurso=${idCurso}&idAlumno=${alumnoId}`;

    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error("No se encontró el archivo PDF o hubo un problema al obtenerlo.");
        }
        return response.blob();
      })
      .then(blob => {
        const fileURL = URL.createObjectURL(blob);
        window.open(fileURL, '_blank'); // Abre el PDF en una nueva pestaña
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al intentar abrir el diploma: " + error.message);
      });
  }

  function agregarDiploma(idCurso) {
    const alumnoId = document.getElementById('alumno_id').value;

    if (!alumnoId) {
      alert("Por favor, selecciona un alumno antes de continuar.");
      return;
    }

    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'application/pdf';
    fileInput.onchange = function() {
      const file = fileInput.files[0];

      if (file) {
        const formData = new FormData();
        formData.append('pdf', file);
        formData.append('idCurso', idCurso);
        formData.append('idAlumno', alumnoId);

        // Verificar si ya existe un archivo antes de subirlo
        fetch('verificar_archivo.php', {
            method: 'POST',
            body: JSON.stringify({
              idCurso: idCurso,
              idAlumno: alumnoId
            }),
            headers: {
              "Content-Type": "application/json"
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.exists) {
              if (confirm("Ya existe un archivo para este alumno. ¿Deseas sobrescribirlo?")) {
                // Si el usuario acepta sobrescribir, procedemos con la subida del archivo
                subirArchivo(formData);
              }
            } else {
              // Si no existe el archivo, subimos el nuevo archivo
              subirArchivo(formData);
            }
          })
          .catch(error => {
            console.error("Error en la solicitud de verificación:", error);
            alert("Ocurrió un error al verificar el archivo.");
          });
      }
    };
    fileInput.click();
  }

  function subirArchivo(formData) {
    fetch('subir_diploma.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Diploma subido exitosamente.");
        } else {
          alert("Error al subir el diploma: " + data.message);
        }
      })
      .catch(error => {
        console.error("Error en la solicitud:", error);
        alert("Ocurrió un error al intentar subir el diploma.");
      });
  }

  function enviarDiploma(idCurso, nombreCurso) {
    const alumnoId = document.getElementById('alumno_id').value;
    const cedula = document.getElementById('dni').value; // Capturamos la cédula
    const nombreAlumno = document.getElementById('nombre').value;
    const apellidoAlumno = document.getElementById('apellido').value;
    const email = document.getElementById('email').value;

    if (!alumnoId || !nombreAlumno || !apellidoAlumno) {
      alert("Por favor, asegúrate de seleccionar un alumno.");
      return;
    }

    if (!email) {
      alert("El correo electrónico no está disponible.");
      return;
    }

    const url = `enviar_diploma.php`;

    // Enviar datos al servidor
    fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          idCurso,
          nombreCurso,
          alumnoId,
          cedula,
          nombreAlumno,
          apellidoAlumno,
          email
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Diploma enviado exitosamente a " + email);
        } else {
          alert("Error al enviar el diploma: " + data.message);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al intentar enviar el diploma.");
      });
  }
</script>