<?php
require "../conexion.php"; // Asegúrate de incluir la conexión
require_once "recordatorio.php"; // Incluye la clase Recordatorio
require_once 'emailService.php'; // Clase para envío de correos
require_once "../src/mensajeRecordatorio.php"; 

date_default_timezone_set('America/Argentina/Buenos_Aires');
// Obtener la fecha actual del sistema
$fechaActual = date('Y-m-d');

function crearRecordatorios($conexion)
{
    // Obtener todos los alumnos activos
    $queryAlumnos = "SELECT idalumno FROM alumno WHERE estado = 1"; // Suponemos que 1 significa activo
    $resultAlumnos = mysqli_query($conexion, $queryAlumnos);

    if (!$resultAlumnos) {
        die('Error en la consulta de alumnos: ' . mysqli_error($conexion));
    }

    while ($alumno = mysqli_fetch_assoc($resultAlumnos)) {
        $idAlumno = $alumno['idalumno'];

        // Obtener todos los cursos activos en los que el alumno está inscrito
        $queryCursos = "SELECT c.idcurso, c.duracion, i.fechacomienzo, c.diasRecordatorio
                        FROM curso c
                        INNER JOIN inscripcion i ON c.idcurso = i.idcurso
                        WHERE i.idalumno = '$idAlumno' AND c.estado = 1";

        $resultCursos = mysqli_query($conexion, $queryCursos);

        if (!$resultCursos) {
            die('Error en la consulta de cursos: ' . mysqli_error($conexion));
        }

        while ($curso = mysqli_fetch_assoc($resultCursos)) {
            $idCurso = $curso['idcurso'];
            $duracion = $curso['duracion'];
            $fechaInicio = $curso['fechacomienzo'];
            $diaPago = $curso['diasRecordatorio'];

            // Crear los recordatorios con la clase Recordatorio
            $recordatorio = new Recordatorio($conexion);
            $recordatorio->setDatos($idCurso, $idAlumno);
            $recordatorio->crearRecordatorios($fechaInicio, $duracion);
        }
    }
}

function crearRecordatoriosEspecificos($conexion, $idAlumno, $idCurso)
{
    // Obtener los detalles del curso específico en el que está inscrito el alumno
    $queryCurso = "SELECT c.idcurso, c.duracion, i.fechacomienzo, c.diasRecordatorio
                   FROM curso c
                   INNER JOIN inscripcion i ON c.idcurso = i.idcurso
                   WHERE i.idalumno = ? AND c.idcurso = ? AND c.estado = 1"; // Solo cursos activos

    $stmt = $conexion->prepare($queryCurso);
    $stmt->bind_param("ii", $idAlumno, $idCurso);
    $stmt->execute();
    $resultCurso = $stmt->get_result();

    if ($resultCurso->num_rows == 0) {
        // No se encontró ningún curso activo para este alumno
        // echo "No se encontró un curso activo para el alumno $idAlumno y curso $idCurso.<br>";
        return;
    }

    while ($curso = $resultCurso->fetch_assoc()) {
        $duracion = $curso['duracion'];           // Duración del curso
        $fechaInicio = $curso['fechacomienzo'];  // Fecha de inicio del curso
        $diaPago = $curso['diasRecordatorio'];               // Día de pago (puede ser NULL)

        // Crear los recordatorios con la clase Recordatorio
        $recordatorio = new Recordatorio($conexion);
        $recordatorio->setDatos($idCurso, $idAlumno); // Pasamos curso y alumno
        $recordatorio->crearRecordatorios($fechaInicio, $duracion); // Creamos los recordatorios
    }
}

function obtenerRecordatoriosPendientes($conexion, $fechaActual)
{
    $sql = "
    SELECT r.id_recordatorio, r.id_curso, r.id_alumno, c.nombre AS curso, a.nombre, a.apellido, a.email
    FROM recordatorios r
    INNER JOIN curso c ON r.id_curso = c.idcurso
    INNER JOIN alumno a ON r.id_alumno = a.idalumno
    WHERE r.fecha_recordatorio = ? AND r.estado = 'pendiente'
    ";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $fechaActual);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerMensajeBase($url)
{
    $response = file_get_contents($url);

    if ($response === false) {
        throw new Exception("Error al obtener la respuesta desde $url");
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error en el JSON recibido: " . json_last_error_msg());
    }

    if (!isset($data['contenidoMensaje'])) {
        throw new Exception("No se pudo obtener el mensaje desde la base de datos.");
    }

    return $data['contenidoMensaje'];
}

function reemplazarComodines($mensajeBase, $nombreAlumno, $apellidoAlumno, $curso, $fechaConAnticipacion)
{
    $mensaje = str_replace('{alumno}', "$nombreAlumno $apellidoAlumno", $mensajeBase);
    $mensaje = str_replace('{curso}', $curso, $mensaje);
    $mensaje = str_replace('{fecha}', $fechaConAnticipacion, $mensaje);
    return $mensaje;
}

function actualizarEstadoRecordatorio($conexion, $idRecordatorio, $nuevoEstado)
{
    $sqlUpdate = "UPDATE recordatorios SET estado = ? WHERE id_recordatorio = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);

    if ($stmtUpdate === false) {
        throw new Exception("Error en la preparación de la consulta de actualización: " . $conexion->error);
    }

    $stmtUpdate->bind_param("si", $nuevoEstado, $idRecordatorio);
    $stmtUpdate->execute();
}

function procesarRecordatorios($conexion)
{
    $fechaActual = date('Y-m-d');
    $emailService = new EmailService();

    // Instancia de la clase MensajeRecordatorio
    $mensajeRecordatorio = new MensajeRecordatorio($conexion);

    try {
        $recordatorios = obtenerRecordatoriosPendientes($conexion, $fechaActual);
    } catch (Exception $e) {
        echo "Error al obtener los recordatorios: " . $e->getMessage() . "<br>";
        return;
    }

    while ($row = $recordatorios->fetch_assoc()) {
        $idRecordatorio = $row['id_recordatorio'];
        $curso = $row['curso'];
        $nombreAlumno = $row['nombre'];
        $apellidoAlumno = $row['apellido'];
        $email = $row['email'];

        try {
            // Obtener el mensaje base desde la clase
            $mensajeBase = $mensajeRecordatorio->obtenerMensajeTexto();  // O usa obtenerMensajeJson() si prefieres JSON
            $fechaConAnticipacion = date('d/m/Y', strtotime('+5 days'));
            $mensaje = reemplazarComodines($mensajeBase, $nombreAlumno, $apellidoAlumno, $curso, $fechaConAnticipacion);

            // Enviar el correo
            $emailService->enviarCorreo($email, 'Recordatorio de Pago', $mensaje);

            // Actualizar el estado del recordatorio
            actualizarEstadoRecordatorio($conexion, $idRecordatorio, 'enviado');
        } catch (Exception $e) {
            echo "Error al enviar recordatorio a $email: " . $e->getMessage() . "<br>";

            try {
                // Si hubo error, actualizar el estado a "fallido"
                actualizarEstadoRecordatorio($conexion, $idRecordatorio, 'fallido');
            } catch (Exception $innerException) {
                echo "Error al actualizar estado del recordatorio: " . $innerException->getMessage() . "<br>";
            }
        }
    }
}

