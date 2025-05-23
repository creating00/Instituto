<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

// Crear el PDF
date_default_timezone_set('America/Argentina/Buenos_Aires');
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetTitle("Comprobante de Venta");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Image("../../assets/img/agua.jpg", 15, 45, 50, 0, 'JPG');

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

// Encabezado
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode("Comprobante de Venta"), 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, utf8_decode("Nro. Factura: " . $datosVenta['numero_factura']), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha: " . date("d-m-Y H:i", strtotime($datosVenta['fecha_venta']))), 0, 1, 'L');
$pdf->Ln(3);

// Nombre de la academia
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, utf8_decode($datos['nombre']), 0, 1, 'C');  // Nombre centrado
$pdf->Ln(1);  // Espacio antes de la imagen


// Imagen ajustada
$pdf->Image("../../assets/img/academia1.png", 60, ($pdf->GetY() / 3) + 2, 15, 15, 'PNG');
$pdf->Ln(1);  // Espacio después de la imagen

// Datos de contacto de la academia
$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['direccion']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();

// Datos del alumno
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Datos del Alumno", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 6);
//$pdf->Cell(30, 5, utf8_decode("Cédula: " . $datosVenta['alumno_dni']), 0, 1, 'L');
$pdf->Cell(30, 5, utf8_decode("Nombre: " . $datosVenta['alumno_nombre']), 0, 1, 'L');
$pdf->Cell(30, 5, utf8_decode("Apellido: " . $datosVenta['alumno_apellido']), 0, 1, 'L');
$pdf->Ln(3);

// Detalle de la compra
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Compra", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(40, 5, utf8_decode("Uniforme: " . $datosVenta['uniforme']), 0, 1, 'L');
$pdf->Cell(20, 5, utf8_decode("Cantidad: " . $datosVenta['cantidad']), 0, 1, 'L');
$pdf->Cell(20, 5, utf8_decode("Precio Unitario: $" . number_format($datosVenta['precio_unitario'], 2)), 0, 1, 'L');
$pdf->Ln(3);

// Medio de pago
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Medio de Pago", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 5, utf8_decode($datosVenta['medio_pago']), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 5, utf8_decode("Total: $" . number_format($datosVenta['total'], 2)), 0, 1, 'R');


// Mostrar el PDF
$pdf->Output("venta_uniformes.pdf", "I");
