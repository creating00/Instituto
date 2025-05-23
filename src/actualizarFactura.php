<?php
require("../conexion.php"); // Incluir la conexión a la base de datos
require("FacturaSecuencia.php"); // Incluir la clase de FacturaSecuencia

// Crear una instancia de FacturaSecuencia
$facturaSecuencia = new FacturaSecuencia($conexion);

try {
    // Incrementar la secuencia y obtener el nuevo número de factura
    $nuevaSecuencia = $facturaSecuencia->incrementarSecuencia();

    // Formatear el número de factura
    $numeroFactura = $facturaSecuencia->formatearFacturaConMes();

    // Retornar el número de factura formateado en formato JSON
    echo json_encode([
        "success" => true,
        "nroFactura" => $numeroFactura
    ]);
} catch (Exception $e) {
    // Manejar errores y enviar una respuesta JSON con el error
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
