<?php
require '../conexion.php'; // Conexión a la base de datos

if (isset($_POST['idInscripcion'])) {
    $idInscripcion = intval($_POST['idInscripcion']);

    // Activar la inscripción
    $query_inscripcion = "UPDATE inscripcion SET activo = 1 WHERE idinscripcion = $idInscripcion";
    $result_inscripcion = mysqli_query($conexion, $query_inscripcion);

    if ($result_inscripcion) {
        // Obtener los datos de la inscripción para restaurar las cuotas
        $query = "SELECT * FROM inscripcion WHERE idinscripcion = $idInscripcion";
        $result = mysqli_query($conexion, $query);
        $inscripcion = mysqli_fetch_array($result);

        $idcurso = $inscripcion['idcurso'];
        $idalumno = $inscripcion['idalumno'];
        $mes = $inscripcion['mes']; // Aquí se usa el nombre del mes (Ej. "Enero")
        $anio = $inscripcion['anio'];
        $precio = $inscripcion['importe'];
        $idusuario = $inscripcion['idusuario'];
        $fechacomienzo = $inscripcion['fechacomienzo'];
        $fechaTermino = $inscripcion['fechaTermino'];

        // Lista de meses en español
        $meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        // Calcular la duración en meses entre fechacomienzo y fechaTermino
        $dateStart = new DateTime($fechacomienzo);
        $dateEnd = new DateTime($fechaTermino);
        $interval = $dateStart->diff($dateEnd);
        $duracion = $interval->m + 1; // Incluir el mes de inicio

        // Obtener el índice del mes actual
        $mesIndex = array_search($mes, $meses);

        // Obtener la última cuota registrada para esta inscripción
        $query_last_cuota = "SELECT cuota, mes, año FROM cuotas WHERE idinscripcion = $idInscripcion ORDER BY año DESC, FIELD(mes, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') DESC LIMIT 1";
        $result_last_cuota = mysqli_query($conexion, $query_last_cuota);
        $last_cuota = mysqli_fetch_array($result_last_cuota);

        // Si hay cuotas previas, obtenemos el número de la última cuota
        if ($last_cuota) {
            $ultimoNumeroCuota = $last_cuota['cuota']; // Última cuota registrada
            // Determinamos el siguiente mes para restaurar
            $mesIndex = array_search($last_cuota['mes'], $meses) + 1;
            if ($mesIndex > 11) {
                $mesIndex = 0; // Si es diciembre, comenzamos desde enero del siguiente año
                $anio++; // Aumentamos el año
            }
        } else {
            // Si no hay cuotas previas, comenzamos desde la cuota 1
            $ultimoNumeroCuota = 1;
        }

        // Insertar cuotas a partir del mes siguiente
        $i = $mesIndex; // Comenzamos en el siguiente mes disponible
        $j = 0;
        while ($j < $duracion) {
            $concepto = $meses[$i % 12]; // Nombre del mes (circular para meses del año)
            $anioCuota = $anio + floor(($i) / 12); // Ajuste del año cuando pasamos diciembre

            // Verificar si estamos más allá de la fecha de término
            $fechaLimite = new DateTime($fechaTermino);
            $fechaCuota = new DateTime("$anioCuota-" . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . "-01");
            if ($fechaCuota > $fechaLimite) {
                break; // Si la cuota está fuera de la fecha de término, paramos
            }

            // Incrementar el número de cuota para la siguiente
            $ultimoNumeroCuota++;

            // Insertar la cuota con el número correcto de cuota
            $query_insert_cuota = "INSERT INTO cuotas (idinscripcion, fecha, cuota, mes, año, importe, condicion, idusuario)
                                   VALUES ('$idInscripcion', '0000-00-00', '$ultimoNumeroCuota', '$concepto', '$anioCuota', '$precio', 'PENDIENTE', '$idusuario')";
            mysqli_query($conexion, $query_insert_cuota);

            $i++; // Avanzar al siguiente mes
            $j++; // Incrementar el contador de cuotas insertadas
        }

        echo "Inscripción y cuotas restauradas correctamente.";
    } else {
        echo "Error al activar la inscripción.";
    }
} else {
    echo "ID de inscripción no recibido.";
}
