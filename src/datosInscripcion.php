<?php

require("../conexion.php");
$idexamen = 0;
if (isset($_POST['mostrarT'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario2 = $_POST['idusuario'];
  $valores = array();
  $resultados = mysqli_query($conexion, "SELECT SUM(total)'total', idexamen FROM cuotas where mes='$mes1' and año='$anio' and condicion='PAGADO' and idusuario='$idusuario2'");
  while ($consulta = mysqli_fetch_array($resultados)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $valores['totalT'] = $consulta['total'];
    $idexamen = $consulta['idexamen'];
  }


  sleep(1);
  $valores = json_encode($valores);
  echo $valores;
}

if (isset($_POST['totalExamenn'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario3 = $_POST['idusuario'];
  $valores2 = array();


  $resultados2 = mysqli_query($conexion, "SELECT SUM(total)'total' FROM examen WHERE idusuario='$idusuario3' and mes='$mes1' and año='$anio'  ");
  while ($consulta2 = mysqli_fetch_array($resultados2)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $valores2['totalE'] = $consulta2['total'];
  }

  sleep(1);
  $valores2 = json_encode($valores2);
  echo $valores2;
}

if (isset($_POST['totalInscripcion'])) {
  $mes1 = $_POST['mes1'];
  $anio = $_POST['anio'];
  $idusuario2 = $_POST['idusuario'];
  $valores = array();
  $resultados3 = mysqli_query($conexion, "SELECT SUM(importe)'total' FROM inscripcion where idusuario='$idusuario2' and mes='$mes1' and año='$anio'  ");
  while ($consulta3 = mysqli_fetch_array($resultados3)) {
    //$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php

    $valores3['totalI'] = $consulta3['total'];
  }

  sleep(1);
  $valores3 = json_encode($valores3);
  echo $valores3;
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
  while ($row = mysqli_fetch_array($rs)) {
    $idprofesor = $row['idprofesor'];
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
  $medioPago = $_POST['medioPago'];
  $total1 = $_POST['total'];
  $precio = $_POST['precio'];
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  $feha_actual = date("Y-m-d H:i:s");
  $fechaComoEntero = strtotime($feha_actual);
  $anio = date("Y", $fechaComoEntero);
  $mes = date("m", $fechaComoEntero);
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
    if (empty($dni) || empty($dniP) || empty($curso) || empty($fechaComienzo) || empty($total1)) {
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
          //insertar de inscripcion
          $query_insert = mysqli_query($conexion, "INSERT INTO inscripcion(idusuario, idalumno, idprofesor, idcurso, idsala, idsede, fechacomienzo, mediodepago, importe, fecha, mes, año) values ('$idusuario', '$idalumno', '$idprofesor', '$idcurso', '$idsala', '$idsede', '$fechaComienzo', '$medioPago', '$total1', '$feha_actual', '$mesText', '$anio')");
          if ($query_insert) {
            echo '<script language="javascript">';
            echo 'alert("Alumno Inscripto correctamente");';
            echo '</script>';
          } else {
            echo '<script language="javascript">';
            echo 'alert("Error al Inscribir");';
            echo '</script>';
          }
        } catch (Exception  $e) {
          echo $e->getMessage();
        }
      }

      //traer datos de inscripcion 
      $rs = mysqli_query($conexion, "SELECT idinscripcion, fechacomienzo, importe, curso.duracion FROM inscripcion
INNER JOIN curso on inscripcion.idcurso=curso.idcurso ORDER BY idinscripcion desc LIMIT 1");
      while ($row = mysqli_fetch_array($rs)) {
        $idinscripcion = $row['idinscripcion'];
        $duracion = $row['duracion'];
        $fechaComienzo = $row['fechacomienzo'];
        //$importe = $row['importe'];
        $fechaComoEntero = strtotime($fechaComienzo);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
      }
      echo $mes;
      $int_mes = intval($mes);
      if ($int_mes == 3) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Marzo";
          }
          if ($i == 2) {
            $concepto = "Abril";
          }
          if ($i == 3) {
            $concepto = "Mayo";
          }
          if ($i == 4) {
            $concepto = "Junio";
          }
          if ($i == 5) {
            $concepto = "Julio";
          }
          if ($i == 6) {
            $concepto = "Agosto";
          }
          if ($i == 7) {
            $concepto = "Septiembre";
          }
          if ($i == 8) {
            $concepto = "Octubre";
          }
          if ($i == 9) {
            $concepto = "Noviembre";
          }
          if ($i == 10) {
            $concepto = "Diciembre";
          }
          //insertar cuotas
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }

      if ($int_mes == 4) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Abril";
          }
          if ($i == 2) {
            $concepto = "Mayo";
          }
          if ($i == 3) {
            $concepto = "Junio";
          }
          if ($i == 4) {
            $concepto = "Julio";
          }
          if ($i == 5) {
            $concepto = "Agosto";
          }
          if ($i == 6) {
            $concepto = "Septiembre";
          }
          if ($i == 7) {
            $concepto = "Octubre";
          }
          if ($i == 8) {
            $concepto = "Noviembre";
          }
          if ($i == 9) {
            $concepto = "Diciembre";
          }
          //insertar cuotas
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }

      if ($int_mes == 5) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Mayo";
          }
          if ($i == 2) {
            $concepto = "Junio";
          }
          if ($i == 3) {
            $concepto = "Julio";
          }
          if ($i == 4) {
            $concepto = "Agosto";
          }
          if ($i == 5) {
            $concepto = "Septiembre";
          }
          if ($i == 6) {
            $concepto = "Octubre";
          }
          if ($i == 7) {
            $concepto = "Noviembre";
          }
          if ($i == 8) {
            $concepto = "Diciembre";
          }
          //insertar cuotas
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }

      if ($int_mes == 6) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Junio";
          }
          if ($i == 2) {
            $concepto = "Julio";
          }
          if ($i == 3) {
            $concepto = "Agosto";
          }
          if ($i == 4) {
            $concepto = "Septiembre";
          }
          if ($i == 5) {
            $concepto = "Octubre";
          }
          if ($i == 6) {
            $concepto = "Noviembre";
          }
          if ($i == 7) {
            $concepto = "Diciembre";
          }
          //insertar cuotas
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }

      if ($int_mes == 7) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Julio";
          }
          if ($i == 2) {
            $concepto = "Agosto";
          }
          if ($i == 3) {
            $concepto = "Septiembre";
          }
          if ($i == 4) {
            $concepto = "Octubre";
          }
          if ($i == 5) {
            $concepto = "Noviembre";
          }
          if ($i == 6) {
            $concepto = "Diciembre";
          }
          //insertar cuotas
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }

      //alumnos que se inscriben en agosto
      if ($int_mes == 8) {
        $concepto = "";
        $i = 1;
        while ($i <= $duracion) {

          if ($i == 1) {
            $concepto = "Agosto";
          }
          if ($i == 2) {
            $concepto = "Septiembre";
          }
          if ($i == 3) {
            $concepto = "Octubre";
          }
          if ($i == 4) {
            $concepto = "Noviembre";
          }
          if ($i == 5) {
            $concepto = "Diciembre";
          }

          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $feha_cuota = date("d-m-Y");

          $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion,fecha, cuota,  mes, año, importe, condicion, idusuario) values ('$idinscripcion', '$feha_cuota', '$i', '$concepto', '$anio', '$precio', 'IMPAGA', '$idusuario')");
          if ($query_insert) {
            //echo "Alumno Agregado";

          } else {
            //echo "Error";
          }

          $i++;
        }
      }
    }
  }
}
