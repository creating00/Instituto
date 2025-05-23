<?php
class CuotasHandler
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para obtener todas las cuotas de un alumno
    public function obtenerCuotasPorAlumno($idAlumno)
    {
        // Escapar el idAlumno para evitar inyección SQL
        $idAlumno = mysqli_real_escape_string($this->conexion, $idAlumno);

        // Consulta para obtener las cuotas
        $query = "
                    SELECT 
                        cuotas.idcuotas, 
                        cuotas.fecha, 
                        cuotas.cuota, 
                        cuotas.mes, 
                        cuotas.año, 
                        cuotas.importe, 
                        cuotas.interes, 
                        cuotas.total, 
                        cuotas.condicion, 
                        usuario.usuario, 
                        cuotas.idexamen,
                        curso.nombre AS nombre_curso,
                        curso.idcurso AS id_curso
                    FROM 
                        cuotas
                    INNER JOIN 
                        inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
                    INNER JOIN 
                        usuario ON cuotas.idusuario = usuario.idusuario
                    INNER JOIN 
                        curso ON inscripcion.idcurso = curso.idcurso
                    WHERE 
                        inscripcion.idalumno = '$idAlumno'
                        AND cuotas.condicion = 'PENDIENTE'
                ";

        $resultado = mysqli_query($this->conexion, $query);

        // Verificar si la consulta fue exitosa
        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        // Recoger los datos en un array
        $cuotas = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            $cuotas[] = $row;
        }

        return $cuotas;
    }

    public function verificarCuotasPendientes($idAlumno)
    {
        $query = "SELECT COUNT(*) as cuotasPendientes 
                  FROM cuotas 
                  INNER JOIN inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
                  WHERE inscripcion.idalumno = ? AND cuotas.condicion = 'PENDIENTE'";

        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $idAlumno);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data['cuotasPendientes'] ?? 0;
    }


    // Método para aplicar la mora automáticamente a las cuotas que estén vencidas
    public function aplicarMoraAutomaticamente()
    {
        // Consulta para obtener todas las cuotas pendientes
        $query = "
            SELECT 
                cuotas.idcuotas, 
                cuotas.idinscripcion, 
                cuotas.importe, 
                cuotas.fecha, 
                cuotas.tieneMora, 
                curso.mora AS mora_curso
            FROM 
                cuotas
            INNER JOIN 
                inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
            INNER JOIN 
                curso ON inscripcion.idcurso = curso.idcurso
            WHERE 
                cuotas.condicion = 'PENDIENTE' AND cuotas.tieneMora = 0
        ";

        $resultado = mysqli_query($this->conexion, $query);

        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        // Iterar sobre todas las cuotas y aplicar la mora si han pasado más de 5 días
        while ($cuota = mysqli_fetch_assoc($resultado)) {
            $fechaCuota = new DateTime($cuota['fecha']);
            $fechaActual = new DateTime();
            $diferencia = $fechaActual->diff($fechaCuota);

            // Verificamos si han pasado más de 5 días
            if ($diferencia->days > 5) {
                // Calculamos el nuevo importe con la mora
                $nuevoImporte = $cuota['importe'] + $cuota['mora_curso'];

                // Actualizamos la cuota con el nuevo importe y marcamos que tiene mora
                $updateQuery = "
                    UPDATE cuotas
                    SET 
                        importe = ?, 
                        tieneMora = 1
                    WHERE 
                        idcuotas = ?
                ";

                $stmt = $this->conexion->prepare($updateQuery);
                $stmt->bind_param("di", $nuevoImporte, $cuota['idcuotas']);
                $stmt->execute();
            }
        }
    }

    public function aplicarMoraComoInteres()
    {
        // Consulta para obtener todas las cuotas pendientes
        $query = "
        SELECT
            cuotas.idcuotas,
            cuotas.idinscripcion,
            cuotas.importe,
            cuotas.fecha,
            cuotas.tieneMora,
            cuotas.interes,
            curso.mora AS mora_curso
        FROM
            cuotas
        INNER JOIN
            inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
        INNER JOIN
            curso ON inscripcion.idcurso = curso.idcurso
        WHERE
            cuotas.condicion = 'PENDIENTE' AND cuotas.tieneMora = 0
    ";

        $resultado = mysqli_query($this->conexion, $query);
        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        // Iterar sobre todas las cuotas y aplicar la mora como interés si han pasado más de 5 días
        while ($cuota = mysqli_fetch_assoc($resultado)) {
            $fechaCuota = new DateTime($cuota['fecha']);
            $fechaActual = new DateTime();
            $diferencia = $fechaActual->diff($fechaCuota);

            // Verificamos si han pasado más de 5 días
            if ($diferencia->days > 5) {
                // Asignamos la mora al campo interés
                $interes = $cuota['mora_curso'];
                // Calculamos el nuevo total (importe + interés)
                //$total = $cuota['importe'] + $interes;

                // Actualizamos la cuota con el nuevo interés y total, y marcamos que tiene mora
                $updateQuery = "
                UPDATE cuotas
                SET
                    interes = ?,
                    
                    tieneMora = 1
                WHERE
                    idcuotas = ?
            ";

                $stmt = $this->conexion->prepare($updateQuery);
                $stmt->bind_param("di", $interes, $cuota['idcuotas']);
                $stmt->execute();
            }
        }
    }
}
