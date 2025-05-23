<?php
class Recordatorio
{
    private $conexion;
    private $idCurso;
    private $idAlumno;
    private $diasPago;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para establecer el ID del curso y el ID del alumno
    public function setDatos($idCurso, $idAlumno)
    {
        $this->idCurso = $idCurso;
        $this->idAlumno = $idAlumno;

        // Obtener el día de pago desde la base de datos (campo 'dias' del curso)
        $this->obtenerDiasDePago();
    }

    // Método para obtener el día de pago del curso
    // Obtener el día de pago desde la base de datos (campo 'dias' del curso)
    // Obtener el día de pago desde la base de datos (campo 'dias' del curso)
    private function obtenerDiasDePago()
    {
        // Modificamos la consulta para que seleccione 'fechacomienzo' de la tabla inscripcion
        $sql = "SELECT c.dias, c.duracion, i.fechacomienzo
            FROM curso c
            INNER JOIN inscripcion i ON c.idcurso = i.idcurso
            WHERE c.idcurso = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $this->idCurso);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->diasPago = $row['dias'];  // El día de pago (campo 'dias')
            $duracionCurso = $row['duracion'];  // Duración del curso
            $fechaInicio = $row['fechacomienzo'];  // Fecha de inicio del curso (ahora tomada de 'inscripcion')

            // Verificar si el día de pago es NULL
            if ($this->diasPago === NULL) {
                // Asignar un valor predeterminado si es NULL, por ejemplo el primer día del mes
                $this->diasPago = 15;  // O cualquier otro valor que consideres adecuado
            }

            // Crear los recordatorios según la duración del curso
            $this->crearRecordatorios($fechaInicio, $duracionCurso);
        }
    }

    // Método para crear los recordatorios
    /*public function crearRecordatorios($fechaInicio, $duracionCurso)
    {
        // Convertir la fecha de inicio a timestamp
        $fechaInicioTimestamp = strtotime($fechaInicio);

        // Calcular los recordatorios por cada mes de duración
        for ($i = 0; $i < $duracionCurso; $i++) {
            // Sumar los meses a la fecha de inicio
            $fechaRecordatorio = date("Y-m-d", strtotime("+$i month", $fechaInicioTimestamp));

            // Establecer el día del recordatorio (sumando el día de pago, si existe)
            $fechaRecordatorio = date("Y-m-" . str_pad($this->diasPago, 2, "0", STR_PAD_LEFT), strtotime($fechaRecordatorio));

            // Restar 5 días para el recordatorio
            $fechaRecordatorio = date("Y-m-d", strtotime("-5 days", strtotime($fechaRecordatorio)));

            // Insertar el recordatorio en la base de datos
            $this->insertarRecordatorio($fechaRecordatorio);
        }
    }*/

    // Método para crear los recordatorios
    public function crearRecordatorios($fechaInicio, $duracionCurso)
    {
        // Convertir la fecha de inicio a timestamp
        $fechaInicioTimestamp = strtotime($fechaInicio);

        // Calcular el primer día de pago basado en las reglas
        $mesInicio = date("Y-m", $fechaInicioTimestamp); // Año y mes del inicio
        $diaPago = str_pad($this->diasPago, 2, "0", STR_PAD_LEFT); // Día de pago ajustado a 2 dígitos

        $primerPago = date("Y-m-$diaPago"); // Crear una fecha con el día de pago en el mismo mes
        if ($fechaInicio > $primerPago) {
            // Si la fecha de inicio es después del día de pago, mover al siguiente mes
            $primerPago = date("Y-m-$diaPago", strtotime("+1 month", $fechaInicioTimestamp));
        }

        // Iterar para crear recordatorios según la duración del curso
        for ($i = 0; $i < $duracionCurso; $i++) {
            // Calcular la fecha del recordatorio para cada mes
            $fechaPago = date("Y-m-$diaPago", strtotime("+$i month", strtotime($primerPago)));

            // Restar 5 días para establecer la fecha del recordatorio
            $fechaRecordatorio = date("Y-m-d", strtotime("-5 days", strtotime($fechaPago)));

            // Insertar el recordatorio en la base de datos
            $this->insertarRecordatorio($fechaRecordatorio);
        }
    }

    // Método para insertar el recordatorio en la base de datos
    private function insertarRecordatorio($fechaRecordatorio)
    {
        // Comprobar si el recordatorio ya existe
        $sqlCheck = "SELECT * FROM recordatorios WHERE id_curso = ? AND id_alumno = ? AND fecha_recordatorio = ?";
        $stmtCheck = $this->conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("iis", $this->idCurso, $this->idAlumno, $fechaRecordatorio);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows == 0) {
            // Si no existe, insertar el nuevo recordatorio
            $sql = "INSERT INTO recordatorios (id_curso, id_alumno, fecha_recordatorio) VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("iis", $this->idCurso, $this->idAlumno, $fechaRecordatorio);

            if ($stmt->execute()) {
                //echo "Recordatorio creado para el " . $fechaRecordatorio . "<br>"; // Depuración
            } else {
                //echo "Error al crear el recordatorio: " . $this->conexion->error . "<br>";
            }
        } else {
            //echo "El recordatorio ya existe para el " . $fechaRecordatorio . "<br>"; // Depuración
        }
    }
}
