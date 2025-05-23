<?php
require '../conexion.php'; // Conexión a la base de datos

if (isset($_POST['idInscripcion'])) {
    $idInscripcion = intval($_POST['idInscripcion']);

    // Obtener el mes y año actual
    $mes_actual = date('n'); // Número del mes actual (1-12)
    $año_actual = date('Y');

    // Buscar el último mes y año con la condición "PAGADO"
    $query_ultimo_pagado = "SELECT mes, año FROM cuotas 
                            WHERE idinscripcion = $idInscripcion 
                            AND condicion = 'PAGADO'
                            ORDER BY año DESC, 
                                     CASE mes 
                                         WHEN 'Enero' THEN 1 WHEN 'Febrero' THEN 2 WHEN 'Marzo' THEN 3
                                         WHEN 'Abril' THEN 4 WHEN 'Mayo' THEN 5 WHEN 'Junio' THEN 6
                                         WHEN 'Julio' THEN 7 WHEN 'Agosto' THEN 8 WHEN 'Septiembre' THEN 9
                                         WHEN 'Octubre' THEN 10 WHEN 'Noviembre' THEN 11 WHEN 'Diciembre' THEN 12
                                     END DESC
                            LIMIT 1";
    $resultado = mysqli_query($conexion, $query_ultimo_pagado);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Si hay cuotas pagadas, tomamos el último mes con "PAGADO"
        $mes_ultimo_pagado = $fila['mes'];
        $año_ultimo_pagado = $fila['año'];
    } else {
        // Si no hay cuotas pagadas, eliminamos desde el mes siguiente al actual
        $mes_ultimo_pagado = null;
        $año_ultimo_pagado = null;
    }

    // Determinar el mes y año desde el cual eliminar
    if ($mes_ultimo_pagado !== null) {
        // Convertimos el mes a número
        $mes_inicio_eliminacion = [
            "Enero" => 1, "Febrero" => 2, "Marzo" => 3, "Abril" => 4,
            "Mayo" => 5, "Junio" => 6, "Julio" => 7, "Agosto" => 8,
            "Septiembre" => 9, "Octubre" => 10, "Noviembre" => 11, "Diciembre" => 12
        ][$mes_ultimo_pagado];

        // Determinar el siguiente mes
        $mes_siguiente = $mes_inicio_eliminacion + 1;
        $año_siguiente = $año_ultimo_pagado;

        if ($mes_siguiente > 12) {
            $mes_siguiente = 1;
            $año_siguiente++;
        }
    } else {
        // Si no hay pagos, eliminamos desde el mes siguiente al actual
        $mes_siguiente = $mes_actual + 1;
        $año_siguiente = $año_actual;

        if ($mes_siguiente > 12) {
            $mes_siguiente = 1;
            $año_siguiente++;
        }
    }

    // Eliminar cuotas desde el mes calculado
    $query_cuotas = "DELETE FROM cuotas 
                     WHERE idinscripcion = $idInscripcion 
                     AND (
                         año > $año_siguiente 
                         OR (año = $año_siguiente AND 
                             CASE mes 
                                 WHEN 'Enero' THEN 1 WHEN 'Febrero' THEN 2 WHEN 'Marzo' THEN 3
                                 WHEN 'Abril' THEN 4 WHEN 'Mayo' THEN 5 WHEN 'Junio' THEN 6
                                 WHEN 'Julio' THEN 7 WHEN 'Agosto' THEN 8 WHEN 'Septiembre' THEN 9
                                 WHEN 'Octubre' THEN 10 WHEN 'Noviembre' THEN 11 WHEN 'Diciembre' THEN 12
                             END >= $mes_siguiente
                         )
                     )";
    mysqli_query($conexion, $query_cuotas);

    // Desactivar la inscripción, en lugar de eliminarla
    $query_inscripcion = "UPDATE inscripcion SET activo = 0 WHERE idinscripcion = $idInscripcion";
    mysqli_query($conexion, $query_inscripcion);

    echo "La inscripción ha sido dada de baja. Las cuotas futuras han sido eliminadas, excepto aquellas ya pagadas";
} else {
    echo "ID de inscripción no recibido.";
}
?>
