<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Venta");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 0, 'JPG');

// Obtener el ID de venta desde la URL
$idVenta = $_GET['idVenta'] ?? '';
if (empty($idVenta)) {
    echo "<h3>Comprobante no encontrado, ID de venta inválido.</h3>";
    exit;
}

// Obtener datos de configuración
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

// Obtener los datos de la venta y del alumno
$venta = mysqli_query($conexion, "SELECT 
        ventas_uniformes.numero_factura, 
        ventas_uniformes.fecha_venta,
        ventas_uniformes.cantidad,
        ventas_uniformes.precio_unitario,
        ventas_uniformes.total,
        ventas_uniformes.medio_pago,
        alumno.dni AS alumno_dni,
        alumno.nombre AS alumno_nombre,
        alumno.apellido AS alumno_apellido,
        uniformes.nombre AS uniforme
    FROM ventas_uniformes
    INNER JOIN alumno ON ventas_uniformes.id_alumno = alumno.idalumno
    INNER JOIN uniformes ON ventas_uniformes.id_uniforme = uniformes.id_uniforme
    WHERE ventas_uniformes.id_venta = '$idVenta'");
$datosVenta = mysqli_fetch_assoc($venta);

// Recuperar datos
$nroFactura = $row['nrofactura'] ?? 'N/A';

// Generar PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 0, utf8_decode("Factura N°: " . $datosVenta['numero_factura']), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 8);
$feha_actual = date("d-m-Y H:i:s");
$pdf->Cell(3, 5, utf8_decode("Fecha           Hora"), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(3, 5, utf8_decode("$feha_actual"), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(180, 6, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Image("../../assets/img/academia1.png", 150, 10, 35, 35, 'PNG');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['email']), 0, 1, 'L');

$pdf->Ln(10);

// Datos del alumno
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Datos del Alumno", 0, 1, 'C', 1);
$pdf->Ln(3);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Nombre', 0, 0, 'L');
$pdf->Cell(50, 6, 'Apellido', 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, utf8_decode($datosVenta['alumno_nombre']), 0, 0, 'L');
$pdf->Cell(50, 6, utf8_decode($datosVenta['alumno_apellido']), 0, 1, 'L');

$pdf->Ln(3);

// Detalle de la compra
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Detalle de la Compra", 0, 1, 'C', 1);
$pdf->Ln(3);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Uniforme', 0, 0, 'L');
$pdf->Cell(50, 6, 'Cantidad', 0, 0, 'L');
$pdf->Cell(50, 6, 'Precio Unitario', 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, utf8_decode($datosVenta['uniforme']), 0, 0, 'L');
$pdf->Cell(50, 6, utf8_decode($datosVenta['cantidad']), 0, 0, 'L');
$pdf->Cell(50, 6, "$" . utf8_decode(number_format($datosVenta['precio_unitario'], 2)), 0, 1, 'L');

//$pdf->Cell(50, 7, utf8_decode("Uniforme: " . $datosVenta['uniforme']), 0, 1, 'L');
//$pdf->Cell(50, 7, utf8_decode("Cantidad: " . $datosVenta['cantidad']), 0, 1, 'L');
//$pdf->Cell(50, 7, utf8_decode("Precio Unitario: $" . number_format($datosVenta['precio_unitario'], 2)), 0, 1, 'L');


// Medio de pago
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Medio de Pago", 0, 1, 'C', 1);
$pdf->Ln(5);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 8, utf8_decode($datosVenta['medio_pago']), 0, 1, 'L'); // Ajustamos el ancho a 190mm
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode("Total: $" . number_format($datosVenta['total'], 2)), 1, 1, 'R'); // Misma alineación y ancho

$pdf->Output("venta_uniformes.pdf", "I");
