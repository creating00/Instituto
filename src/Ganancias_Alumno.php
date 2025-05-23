<?php
include('../conexion.php');  // Asegúrate de incluir tu archivo de conexión a la base de datos

// Obtener los parámetros de la solicitud
$mesInicio = isset($_POST['mesInicio']) ? $_POST['mesInicio'] : '';
$anioInicio = isset($_POST['anioInicio']) ? $_POST['anioInicio'] : '';
$fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
$fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '';
$idCurso = isset($_POST['idCurso']) ? $_POST['idCurso'] : 0; // Nuevo filtro

// Función para obtener cuotas por año y mes
function obtenerCuotasPorAnioMes($anioInicio, $mesInicio, $idCurso)
{
    $filtroCurso = ($idCurso != 0) ? "AND i.idcurso = '$idCurso'" : "";

    return "
        SELECT 
            c.idcuotas, 
            c.fecha AS fecha_cuota, 
            c.cuota, 
            c.mes, 
            c.año, 
            c.importe, 
            c.interes, 
            c.total, 
            c.condicion, 
            c.mediodepago, 
            i.idinscripcion, 
            i.fecha AS fecha_inscripcion, 
            i.idalumno, 
            a.nombre, 
            a.apellido 
        FROM 
            cuotas c
        JOIN 
            inscripcion i ON c.idinscripcion = i.idinscripcion
        JOIN 
            alumno a ON i.idalumno = a.idalumno
        WHERE 
            c.año = '$anioInicio'
            AND c.mes = '$mesInicio'
            $filtroCurso
    ";
}

// Función para obtener cuotas por rango de fechas
function obtenerCuotasPorRangoFechas($fechaInicio, $fechaFin, $idCurso)
{
    $filtroCurso = ($idCurso != 0) ? "AND i.idcurso = '$idCurso'" : "";

    return "
        SELECT 
            c.idcuotas, 
            c.fecha AS fecha_cuota, 
            c.cuota, 
            c.mes, 
            c.año, 
            c.importe, 
            c.interes, 
            c.total, 
            c.condicion, 
            c.mediodepago, 
            i.idinscripcion, 
            i.fecha AS fecha_inscripcion, 
            i.idalumno, 
            a.nombre, 
            a.apellido 
        FROM 
            cuotas c
        JOIN 
            inscripcion i ON c.idinscripcion = i.idinscripcion
        JOIN 
            alumno a ON i.idalumno = a.idalumno
        WHERE 
            (c.fecha >= '$fechaInicio' AND c.fecha <= '$fechaFin') 
            OR (c.fecha = '0000-00-00')
            $filtroCurso
    ";
}

// Construir la consulta según los parámetros recibidos
if (!empty($anioInicio) && !empty($mesInicio)) {
    $query = obtenerCuotasPorAnioMes($anioInicio, $mesInicio, $idCurso);
} elseif (!empty($fechaInicio) && !empty($fechaFin)) {
    $query = obtenerCuotasPorRangoFechas($fechaInicio, $fechaFin, $idCurso);
} else {
    echo json_encode(array('error' => 'No se han proporcionado filtros válidos.'));
    exit();
}

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $query);

// Verificar si se obtuvieron resultados
if ($resultado) {
    $data = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $data[] = $fila;
    }

    // Retornar resultados como JSON
    echo json_encode($data);
} else {
    error_log('Error en la consulta: ' . mysqli_error($conexion)); // Muestra el error de la consulta
    echo json_encode(array('error' => 'No se encontraron datos.'));
}

mysqli_close($conexion);
