<?php

require("../conexion.php");
$idexamen = 0;
if (isset($_POST['mostrarT'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario2 = $_POST['idusuario'];
  $fecha = $_POST['fecha'];

  $valores = array();
  $valores['totalT'] = 0;

  $resultados = mysqli_query($conexion, "SELECT SUM(total) AS total, idexamen FROM cuotas 
                                       WHERE (fecha = '$fecha' OR (mes='$mes1' AND año='$anio')) 
                                       AND condicion='PAGADO' 
                                       AND idusuario='$idusuario2'");

  while ($consulta = mysqli_fetch_array($resultados)) {
    $valores['totalT'] = $consulta['total'] ?: 0; // Si es NULL, asignamos 0
  }

  echo json_encode($valores);
}


if (isset($_POST['totalExamenn'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario3 = $_POST['idusuario'];
  $fecha = $_POST['fecha'];
  $valores2 = array();

  $resultados2 = mysqli_query($conexion, "SELECT SUM(total) AS total FROM examen WHERE idusuario='$idusuario3' AND mes='$mes1'");

  if ($resultados2) {
    $consulta2 = mysqli_fetch_array($resultados2);
    $valores2['totalE'] = $consulta2['total'] ? $consulta2['total'] : 0;
  } else {
    $valores2['totalE'] = 0;
  }

  sleep(1);
  echo json_encode($valores2);
}

if (isset($_POST['totalInscripcion'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario2 = $_POST['idusuario'];
  $fecha = $_POST['fecha'];

  $valores3 = array();
  $valores3['totalI'] = 0;

  $resultados3 = mysqli_query($conexion, "SELECT SUM(importe) AS total FROM inscripcion 
                                          WHERE idusuario='$idusuario2' 
                                          AND (DATE(fecha) = '$fecha' OR (mes='$mes1' AND anio='$anio'))");

  if ($consulta3 = mysqli_fetch_array($resultados3)) {
    $valores3['totalI'] = $consulta3['total'] ?: 0;
  }

  echo json_encode($valores3);
}

if (isset($_POST['buscarSP'])) {

  $sp2 = $_POST['sp2'];
  $valores = array();
  $valores['existe'] = "0";
  $resultados = mysqli_query($conexion, "SELECT importe FROM servicioproducto WHERE descripcion='$sp2'");
  while ($consulta = mysqli_fetch_array($resultados)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $valores['importe3'] = $consulta['importe'];
  }
  sleep(1);
  $valores = json_encode($valores);
  echo $valores;
}

if (isset($_GET['id'])) {
  $ida = $_GET['id'];
  $resultados = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno = '$ida'");
  while ($consulta = mysqli_fetch_array($resultados)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $valores['nombre'] = $consulta['nombre'];
    $valores['apellido'] = $consulta['apellido'];
  }
  sleep(1);
  $valores = json_encode($valores);
  echo $valores;
}

if (isset($_POST['buscar'])) {
  $dni = $_POST['dni'];
  $valores = array();
  $valores['existe'] = "0";

  //CONSULTAR
  $sede = $_POST['sede'];
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  if ($sede == "GENERAL") {
    $resultados = mysqli_query($conexion, "SELECT * FROM alumno WHERE dni = '$dni' AND estado=1");
    while ($consulta = mysqli_fetch_array($resultados)) {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

      $valores['nombre'] = $consulta['nombre'];
      $valores['apellido'] = $consulta['apellido'];
      $valores['idalumno'] = $consulta['idalumno'];
    }
    sleep(1);
    $valores = json_encode($valores);
    echo $valores;
  } else {

    $resultados = mysqli_query($conexion, "SELECT * FROM alumno WHERE dni = '$dni' AND idsede='$idsede' AND estado=1");
    while ($consulta = mysqli_fetch_array($resultados)) {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

      $valores['nombre'] = $consulta['nombre'];
      $valores['apellido'] = $consulta['apellido'];
      $valores['idalumno'] = $consulta['idalumno'];
    }
    sleep(1);
    $valores = json_encode($valores);
    echo $valores;
  }
}
// buscar datos Profesor
if (isset($_POST['buscar_profesor'])) {
  $dniP = $_POST['dniP'];
  $valoresP = array();
  $valoresP['existe'] = "0";

  //CONSULTAR
  $sedeP = $_POST['sedeP'];
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sedeP' AND estado=1");
  while ($row = mysqli_fetch_array($rs)) {
    $idsedeP = $row['idsede'];
  }
  if ($sedeP == "GENERAL") {
    $resultadosP = mysqli_query($conexion, "SELECT * FROM profesor WHERE dni = '$dniP' AND estado=1");
    while ($consultaP = mysqli_fetch_array($resultadosP)) {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
      $valoresP['nombreP'] = $consultaP['nombre'];
      $valoresP['apellidoP'] = $consultaP['apellido'];
    }
    sleep(1);
    $valoresP = json_encode($valoresP);
    echo $valoresP;
  } else {
    $resultadosP = mysqli_query($conexion, "SELECT * FROM profesor WHERE dni = '$dniP' AND idsede='$idsedeP' AND estado=1");
    while ($consultaP = mysqli_fetch_array($resultadosP)) {
      //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
      $valoresP['nombreP'] = $consultaP['nombre'];
      $valoresP['apellidoP'] = $consultaP['apellido'];
    }
    sleep(1);
    $valoresP = json_encode($valoresP);
    echo $valoresP;
  }
}

//datos Cursos
if (isset($_POST['buscar_curso'])) {
  $curso = $_POST['curso'];
  $valoresCu = array();
  $valoresCu['existe'] = "0";

  //CONSULTAR
  $resultadosCu = mysqli_query($conexion, "SELECT * FROM curso WHERE nombre = '$curso'");
  while ($consultaCu = mysqli_fetch_array($resultadosCu)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
    $valoresCu['duracion'] = $consultaCu['tipo'];
    $valoresCu['precio'] = $consultaCu['precio'];
    $valoresCu['inscripcion'] = $consultaCu['inscripcion'];
  }
  sleep(1);
  $valoresCu = json_encode($valoresCu);
  echo $valoresCu;
}

//Inscribir en bd
if (isset($_POST['guardar'])) {

  $dni = $_POST['dni'];
  //traer id Alumno
  $rs = mysqli_query($conexion, "SELECT idalumno, estado FROM alumno WHERE dni ='$dni'");
  while ($row = mysqli_fetch_array($rs)) {
    $idalumno = $row['idalumno'];
    $estadoAlumno = $row['estado'];
  }

  $dniP = $_POST['dniP'];
  //traer id Profesor
  $rs = mysqli_query($conexion, "SELECT idprofesor FROM profesor WHERE dni ='$dniP'");
  $idprofesor = null;
  while ($row = mysqli_fetch_array($rs)) {
    $idprofesor = isset($row['idprofesor']) ? $row['idprofesor'] : null;
  }

  $salas = $_POST['salas'];
  //traer id Alumno
  $rs = mysqli_query($conexion, "SELECT idsala FROM sala WHERE sala ='$salas'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsala = $row['idsala'];
  }
  $usuario = $_POST['usuario'];
  //traer id Alumno
  $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE nombre ='$usuario' or usuario='$usuario'");
  while ($row = mysqli_fetch_array($rs)) {
    $idusuario = $row['idusuario'];
  }
  $sede = $_POST['sede'];
  $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
  }
  $curso = $_POST['curso'];
  //traer id curso
  $rs = mysqli_query($conexion, "SELECT idcurso FROM curso WHERE nombre ='$curso' and idsede='$idsede'");
  while ($row = mysqli_fetch_array($rs)) {
    $idcurso = $row['idcurso'];
  }
  $fechaComienzo = $_POST['fechaComienzo'];
  $fechaTermino = $_POST['fechaTermino'];
  $medioPago = $_POST['medioPago'];
  $total1 = $_POST['total'];
  $precio = $_POST['precio'];
  date_default_timezone_set('America/Santo_Domingo');
  $feha_actual = date("Y-m-d H:i:s");
  $fechaComoEntero = strtotime($feha_actual);
  $anio = date("Y", $fechaComoEntero);
  $mes = date("m", $fechaComoEntero);
  $isDetalle = isset($_POST['isDetalle']) && $_POST['isDetalle'] === 'true' ? 1 : 0;
  $detalle = isset($_POST['detalle']) ? mysqli_real_escape_string($conexion, $_POST['detalle']) : '';
  $nroFactura = $_POST['nroFactura'];

  if ($mes == 1) {
    $mesText = "Enero";
  }
  if ($mes == 2) {
    $mesText = "Febrero";
  }
  if ($mes == 3) {
    $mesText = "Marzo";
  }
  if ($mes == 4) {
    $mesText = "Abril";
  }
  if ($mes == 5) {
    $mesText = "Mayo";
  }
  if ($mes == 6) {
    $mesText = "Junio";
  }
  if ($mes == 7) {
    $mesText = "Julio";
  }
  if ($mes == 8) {
    $mesText = "Agosto";
  }
  if ($mes == 9) {
    $mesText = "Septiembre";
  }
  if ($mes == 10) {
    $mesText = "Octubre";
  }
  if ($mes == 11) {
    $mesText = "Noviembre";
  }
  if ($mes == 12) {
    $mesText = "Diciembre";
  }
  //validar si el alumno esta dado de baja
  if ($estadoAlumno == 0) {
    echo '<script language="javascript">';
    echo 'alert("El Alumno esta dado de baja");';
    echo '</script>';
  } else {

    //validar si ya existe el alumno en ese curso
    $alert = "";
    if (empty($dni) || empty($curso) || empty($fechaComienzo) || empty($total1)) {
      echo '<script language="javascript">';
      echo 'alert("Todos los campos Son Obligatorios");';
      echo '</script>';
    } else {
      $query = mysqli_query($conexion, "SELECT * FROM inscripcion WHERE idalumno = '$idalumno' and idcurso = '$idcurso' ");
      $result = mysqli_fetch_array($query);
      if ($result > 0) {
        echo '<script language="javascript">';
        echo 'alert("El alumno ya esta inscripto en ese Curso");';
        echo '</script>';
      } else {

        try {
          //datos vacios
          //echo "$fechaComienzo'',$medioPago'',$total1'',$feha_actual'',$mesText'',$anio'',$fechaTermino,''";
          //insertar de inscripcion
          $query_insert = mysqli_query(
            $conexion,
            "INSERT INTO inscripcion(
        idusuario, 
        idalumno, 
        idprofesor, 
        idcurso, 
        idsala, 
        idsede, 
        fechacomienzo, 
        mediodepago, 
        importe, 
        fecha, 
        mes, 
        anio, 
        fechaTermino, 
        isDetalle, 
        detalle,
        nroFactura
    ) 
    VALUES (
        '$idusuario', 
        '$idalumno', 
        " . ($idprofesor !== null ? "'$idprofesor'" : "NULL") . ", 
        '$idcurso', 
        '$idsala', 
        '$idsede', 
        '$fechaComienzo', 
        '$medioPago', 
        '$total1', 
        '$feha_actual', 
        '$mesText', 
        '$anio', 
        '$fechaTermino', 
        '$isDetalle', 
        '$detalle',
        '$nroFactura'
    )"
          );
          if ($query_insert) {
            echo '<script language="javascript">';
            echo 'alert("Alumno Inscripto correctamente");';
            echo '</script>';

            $idinscripcion = mysqli_insert_id($conexion);
            echo '<script>';
            echo 'localStorage.setItem("idinscripcion", ' . $idinscripcion . ');';
            echo '</script>';
          } else {
            echo '<script language="javascript">';
            echo 'alert("Error al Inscribir");';
            echo '</script>';
            // Llamar a la función para crear recordatorios específicos
            crearRecordatoriosEspecificos($conexion, $idalumno, $idcurso);
          }
        } catch (PDOException  $e) {
          echo $e->getMessage();
        }
      }

      //traer datos de inscripcion 
      $rs = mysqli_query($conexion, "SELECT idinscripcion, inscripcion.fechacomienzo, importe, curso.duracion, inscripcion.fechaTermino FROM inscripcion
        INNER JOIN curso on inscripcion.idcurso=curso.idcurso ORDER BY idinscripcion desc LIMIT 1");
      while ($row = mysqli_fetch_array($rs)) {
        $idinscripcion = $row['idinscripcion'];
        $duracion = $row['duracion'];
        $fechaComienzo = $row['fechacomienzo'];
        $fechaTermino = $row['fechaTermino'];
        //$importe = $row['importe'];
        $fechaComoEntero = strtotime($fechaComienzo);
        $anio = date(
          "Y",
          $fechaComoEntero
        );
        $mes = date("m", $fechaComoEntero);
        //extraer datos fecha termino
        $fechaComoEnteroT = strtotime($fechaTermino);
        $anioT = date(
          "Y",
          $fechaComoEnteroT
        );
        $mesT = date(
          "m",
          $fechaComoEnteroT
        );
      }

      $int_mes = intval($mes);
      $int_mesT = intval($mesT);
      //cuotas genericas
      $i = $int_mes;
      $j = 1;
      while ($i <= $int_mesT) {

        if ($i == 1) {
          $concepto = "Enero";
        }
        if (
          $i == 2
        ) {
          $concepto = "Febrero";
        }
        if (
          $i == 3
        ) {
          $concepto = "Marzo";
        }
        if (
          $i == 4
        ) {
          $concepto = "Abril";
        }
        if (
          $i == 5
        ) {
          $concepto = "Mayo";
        }
        if (
          $i == 6
        ) {
          $concepto = "Junio";
        }
        if (
          $i == 7
        ) {
          $concepto = "Julio";
        }
        if (
          $i == 8
        ) {
          $concepto = "Agosto";
        }
        if (
          $i == 9
        ) {
          $concepto = "Septiembre";
        }
        if (
          $i == 10
        ) {
          $concepto = "Octubre";
        }
        if (
          $i == 11
        ) {
          $concepto = "Noviembre";
        }
        if (
          $i == 12
        ) {
          $concepto = "Diciembre";
        }

        //insertar cuotas
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        //$feha_cuota = date("Y-m-d");

        $feha_cuota = date("d-m-Y");

        $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$j', '$concepto', '$anio', '$precio', 'PENDIENTE', '$idusuario')");
        if ($query_insert) {
          //echo "Alumno Agregado";

        } else {
          //echo "Error";
        }
        $i++;
        $j++;
      }
    }
  }
}
