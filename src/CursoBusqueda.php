<?php
class CursoBusqueda
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para registrar un nuevo curso
    public function registrarCurso($nombre, $precio, $duracion, $estado, $tipo, $idsede, $dias, $inscripcion, $idprofesor, $monto, $fechaComienzo, $fechaTermino, $mora, $diasRecordatorio, $horarioDesde, $horarioHasta)
    {
        // Construcción de la consulta SQL con los nuevos campos
        $query = "INSERT INTO curso(
                    nombre, 
                    precio, 
                    duracion, 
                    estado, 
                    tipo, 
                    idsede, 
                    dias, 
                    inscripcion, 
                    idprofesor, 
                    monto, 
                    fechaComienzo, 
                    fechaTermino, 
                    mora, 
                    diasRecordatorio, 
                    horarioDesde, 
                    horarioHasta
                ) 
                VALUES(
                    '$nombre', 
                    '$precio', 
                    '$duracion', 
                    $estado, 
                    '$tipo', 
                    '$idsede', 
                    " .
            ($dias !== null ? $dias : "NULL") . ", " .
            ($inscripcion !== null ? "'$inscripcion'" : "NULL") . ", " .
            ($idprofesor !== null ? "'$idprofesor'" : "NULL") . ", " .
            ($monto !== null ? "'$monto'" : "NULL") . ", " .
            ($fechaComienzo !== null ? "'$fechaComienzo'" : "NULL") . ", " .
            ($fechaTermino !== null ? "'$fechaTermino'" : "NULL") . ", " .
            ($mora !== null ? "'$mora'" : "NULL") . ", " .
            ($diasRecordatorio !== null ? "'$diasRecordatorio'" : "NULL") . ", " .
            ($horarioDesde !== null ? "'$horarioDesde'" : "NULL") . ", " .
            ($horarioHasta !== null ? "'$horarioHasta'" : "NULL") .
            ")";

        // Ejecutar la consulta
        $resultado = mysqli_query($this->conexion, $query);

        // Verificar si la consulta fue exitosa
        if (!$resultado) {
            return "Error al registrar el curso: " . mysqli_error($this->conexion);
        }

        return "Curso registrado exitosamente.";
    }

    // Método para buscar cursos según el nombre y sede
    public function buscarCursos($mi_busqueda_curso, $sede)
    {
        // Obtener el ID de la sede si es necesario
        $idsede = $this->obtenerIdSede($sede);

        // Preparar la consulta dependiendo de la sede
        if ($sede == "GENERAL") {
            $query = "
        SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre as sede 
        FROM curso
        INNER JOIN sedes ON curso.idsede = sedes.idsede
        WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado = 1
        LIMIT 5
      ";
        } else {
            $query = "
        SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre as sede
        FROM curso
        INNER JOIN sedes ON curso.idsede = sedes.idsede
        WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado = 1 AND curso.idsede = '$idsede'
        LIMIT 5
      ";
        }

        return $this->ejecutarConsulta($query);
    }

    public function buscarCursosProfesor($mi_busqueda_curso, $sede, $idprofesor)
    {
        // Obtener el ID de la sede si es necesario
        $idsede = $this->obtenerIdSede($sede);

        // Preparar la consulta dependiendo de la sede
        if ($sede == "GENERAL") {
            $query = "
        SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre as sede 
        FROM curso
        INNER JOIN sedes ON curso.idsede = sedes.idsede
        WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado = 1 AND curso.idprofesor = '$idprofesor'
        LIMIT 5
      ";
        } else {
            $query = "
        SELECT idcurso, curso.nombre, precio, tipo, curso.inscripcion, sedes.nombre as sede
        FROM curso
        INNER JOIN sedes ON curso.idsede = sedes.idsede
        WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado = 1 AND curso.idsede = '$idsede' AND curso.idprofesor = '$idprofesor'
        LIMIT 5
      ";
        }

        return $this->ejecutarConsulta($query);
    }

    // Método para obtener el ID de la sede a partir de su nombre
    private function obtenerIdSede($sede)
    {
        $query = "SELECT idsede FROM sedes WHERE nombre = '$sede'";
        $resultado = $this->ejecutarConsulta($query);

        // Si se encuentra la sede, devolver el ID
        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_array($resultado);
            return $row['idsede'];
        } else {
            return null;  // Si no se encuentra la sede, retornar null
        }
    }

    // Método para ejecutar una consulta SQL genérica y devolver el resultado
    private function ejecutarConsulta($query)
    {
        $resultado = mysqli_query($this->conexion, $query);

        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        return $resultado;
    }

    // Método para buscar cursos asociados a un alumno
    public function buscarCursosPorAlumno($alumno_id)
    {
        $query = "
        SELECT 
            c.idcurso, 
            c.nombre, 
            i.fecha AS fecha_inscripcion, 
            c.precio, 
            c.duracion, 
            c.fechaComienzo AS fecha_inicio,
            i.idinscripcion,
             c.fechaTermino AS fecha_fin,
            (SELECT COUNT(*) 
             FROM cuotas q
             WHERE q.idinscripcion = i.idinscripcion 
             AND q.condicion = 'pendiente') AS cuotas_pendientes
        FROM 
            inscripcion i
        INNER JOIN 
            curso c ON i.idcurso = c.idcurso
        WHERE 
            i.idalumno = ?
    ";

        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $alumno_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
