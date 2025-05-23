<?php
require("../conexion.php");
require_once "crear_recordatorios.php";

if (isset($_POST['guardar'])) {
    $dni = $_POST['dni'];
    $response = [];

    // Traer id Alumno
    $rs = mysqli_query($conexion, "SELECT idalumno, estado FROM alumno WHERE dni ='$dni'");
    $row = mysqli_fetch_array($rs);
    $idalumno = $row['idalumno'];
    $estadoAlumno = $row['estado'];

    if ($estadoAlumno == 0) {
        echo json_encode(['status' => 'error', 'message' => 'El Alumno esta dado de baja']);
        exit;
    }

    $dniP = $_POST['dniP'];
    // Traer id Profesor
    $rs = mysqli_query($conexion, "SELECT idprofesor FROM profesor WHERE dni ='$dniP'");
    $row = mysqli_fetch_array($rs);
    $idprofesor = $row['idprofesor'] ?? null;

    $salas = $_POST['salas'];
    // Traer id Sala
    $rs = mysqli_query($conexion, "SELECT idsala FROM sala WHERE sala ='$salas'");
    $row = mysqli_fetch_array($rs);
    $idsala = $row['idsala'];

    $usuario = $_POST['usuario'];
    // Traer id Usuario
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE nombre ='$usuario' OR usuario='$usuario'");
    $row = mysqli_fetch_array($rs);
    $idusuario = $row['idusuario'];

    $sede = $_POST['sede'];
    // Traer id Sede
    $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
    $row = mysqli_fetch_array($rs);
    $idsede = $row['idsede'];

    $curso = $_POST['curso'];
    // Traer id Curso
    $rs = mysqli_query($conexion, "SELECT idcurso FROM curso WHERE nombre ='$curso' AND idsede='$idsede'");
    $row = mysqli_fetch_array($rs);
    $idcurso = $row['idcurso'];

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
    $mesText = date("F", $fechaComoEntero);

    $isDetalle = isset($_POST['isDetalle']) && $_POST['isDetalle'] === 'true' ? 1 : 0;
    $detalle = isset($_POST['detalle']) ? mysqli_real_escape_string($conexion, $_POST['detalle']) : '';
    $nroFactura = $_POST['nroFactura'];

    // Validar si ya existe el alumno en ese curso
    if (empty($dni) || empty($curso) || empty($fechaComienzo) || empty($total1)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $query = mysqli_query($conexion, "SELECT * FROM inscripcion WHERE idalumno = '$idalumno' AND idcurso = '$idcurso'");
    if (mysqli_num_rows($query) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'El alumno ya está inscrito en ese curso']);
        exit;
    }

    // Insertar inscripcion
    $query_insert = mysqli_query($conexion, "INSERT INTO inscripcion(
        idusuario, idalumno, idprofesor, idcurso, idsala, idsede, fechacomienzo, mediodepago, importe, fecha, mes, anio, fechaTermino, isDetalle, detalle, nroFactura
    ) VALUES (
        '$idusuario', '$idalumno', " . ($idprofesor !== null ? "'$idprofesor'" : "NULL") . ", '$idcurso', '$idsala', '$idsede', '$fechaComienzo', '$medioPago', '$total1', '$feha_actual', '$mesText', '$anio', '$fechaTermino', '$isDetalle', '$detalle', '$nroFactura'
    )");

    if ($query_insert) {
        $idinscripcion = mysqli_insert_id($conexion);
        echo json_encode([
            'status' => 'success',
            'message' => 'Alumno inscrito correctamente',
            'idinscripcion' => $idinscripcion
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al inscribir']);
        exit;
    }

    // Generar cuotas
    $rs = mysqli_query($conexion, "SELECT idinscripcion, inscripcion.fechacomienzo, importe, curso.duracion, inscripcion.fechaTermino FROM inscripcion
    INNER JOIN curso ON inscripcion.idcurso = curso.idcurso ORDER BY idinscripcion DESC LIMIT 1");
    $row = mysqli_fetch_array($rs);
    $idinscripcion = $row['idinscripcion'];
    $duracion = $row['duracion'];
    $fechaComienzo = $row['fechacomienzo'];
    $fechaTermino = $row['fechaTermino'];
    $fechaComoEntero = strtotime($fechaComienzo);
    $anio = date("Y", $fechaComoEntero);
    $mes = date("m", $fechaComoEntero);

    $int_mes = intval($mes);
    $int_mesT = intval(date("m", strtotime($fechaTermino)));

    crearRecordatoriosEspecificos($conexion, $idalumno, $idcurso);

    $i = $int_mes;
    $j = 1;
    while ($i <= $int_mesT) {
        $concepto = date("F", mktime(0, 0, 0, $i, 10));

        // Insertar cuotas
        $feha_cuota = date("d-m-Y");
        $query_insert = mysqli_query($conexion, "INSERT INTO cuotas(idinscripcion, fecha, cuota, mes, año, importe, condicion, idusuario) VALUES ('$idinscripcion', '$feha_cuota', '$j', '$concepto', '$anio', '$precio', 'PENDIENTE', '$idusuario')");

        if (!$query_insert) {
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar cuotas']);
            exit;
        }

        $i++;
        $j++;
    }
}
