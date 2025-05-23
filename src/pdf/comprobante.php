<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

$pdf = new FPDF('P', 'mm', array(80, 200)); // Tamaño ticket
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetTitle("Comprobante de Pago");

$pdf->Image("../../assets/img/agua.jpg", 15, 40, 50, 0, 'JPG');

// Configuración
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

// Obtener datos de la factura
$idcuota = $_GET['id'];
$factura = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre AS curso, cuotas.nroFactura, cuotas.cuota, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre AS usuario, sedes.nombre AS sede, cuotas.mediodepago 
    FROM cuotas 
    INNER JOIN inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
    INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
    INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
    INNER JOIN usuario ON inscripcion.idusuario = usuario.idusuario
    INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
    WHERE idcuotas='$idcuota'");

$resultados = [];
while ($row = mysqli_fetch_assoc($factura)) {
    $resultados[] = $row;
}

if (empty($resultados)) {
    die("No se encontraron datos para el ID de cuota proporcionado.");
}

// Encabezado
// Encabezado: Factura N°
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(0, 5, utf8_decode("Factura N°: " . $resultados[0]['nroFactura']), 0, 1, 'L');
$pdf->Ln(2);

// Fecha y hora
date_default_timezone_set('America/Argentina/Buenos_Aires');
$fecha_actual = date("d-m-Y H:i:s");
$fecha = date("d-m-Y");
$hora = date("H:i:s");
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(35, 4, utf8_decode("Fecha: $fecha"), 0, 0, 'L');
$pdf->Cell(45, 4, utf8_decode("Hora: $hora"), 0, 1, 'L');

// Nombre de la academia (centrado)
$pdf->Image("../../assets/img/academia1.png", 30, $pdf->GetY(), 20, 20, 'PNG');
$pdf->Ln(20); // Espacio después de la imagen   

// Nombre de la empresa
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, utf8_decode($datos['nombre']), 0, 1, 'C');

// Logo centrado
$logoWidth = 30; // Ancho del logo
$logoHeight = 15; // Alto del logo
$pdf->Ln(2); // Espacio después del logo

// Datos de contacto (alineados a la izquierda)
$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, utf8_decode("Teléfono:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, utf8_decode("Dirección:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['direccion']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(15, 3, utf8_decode("Correo:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['email']), 0, 1, 'L');


// Datos del alumno
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Datos del Alumno", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(25, 5, utf8_decode('Cedula'), 0, 0, 'L');
$pdf->Cell(25, 5, 'Nombre', 0, 0, 'L');
$pdf->Cell(25, 5, 'Apellido', 0, 1, 'L');

$pdf->SetFont('Arial', '', 5);
foreach ($resultados as $row) {
    $dni = !empty($row['dni']) ? $row['dni'] : 'N/A';
    $nombre = !empty($row['nombre']) ? $row['nombre'] : 'N/A';
    $apellido = !empty($row['apellido']) ? $row['apellido'] : 'N/A';

    $pdf->Cell(25, 5, $dni, 0, 0, 'L');
    $pdf->Cell(25, 5, $nombre, 0, 0, 'L');
    $pdf->Cell(25, 5, $apellido, 0, 1, 'L');
}

// Detalle de pago
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Pago", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(30, 5, 'Curso', 0, 0, 'L');
$pdf->Cell(20, 5, 'Pago', 0, 0, 'L');
$pdf->Cell(16, 5, 'Concepto', 0, 0, 'L');
$pdf->Cell(8, 5, '', 0, 1, 'L');

$pdf->SetFont('Arial', '', 5);
foreach ($resultados as $row) {
    $pdf->Cell(30, 5, $row['curso'], 0, 0, 'L');
    $pdf->Cell(20, 5, $row['cuota'], 0, 0, 'L');
    $pdf->Cell(25, 5, utf8_decode($row['mes'] . " - " . $row['año']), 0, 1, 'L'); // Mes y año combinados
}

// Totales
$pdf->Ln(3); // Espacio antes de la tabla

// Encabezados
$pdf->SetFont('Arial', 'B', 5); // Fuente más pequeña para los encabezados
$pdf->Cell(18, 5, 'Fecha', 0, 0, 'L');       // Fecha y hora combinadas
$pdf->Cell(9, 5, 'Monto', 0, 0, 'L');        // Monto
$pdf->Cell(8, 5, 'Mora', 0, 0, 'L');         // Mora
$pdf->Cell(8, 5, 'Total', 0, 0, 'L');        // Total
$pdf->Cell(12, 5, 'Operador', 0, 0, 'L');    // Operador
$pdf->Cell(15, 5, 'Medio Pago', 0, 1, 'L');  // Medio de pago

// Datos
$pdf->SetFont('Arial', '', 5); // Fuente más pequeña para los datos
foreach ($resultados as $row) {
    $pdf->Cell(18, 3, $row['fecha'], 0, 0, 'L');                  // Fecha
    $pdf->Cell(9, 3, "$" . number_format($row['importe'], 2), 0, 0, 'L'); // Monto formateado
    $pdf->Cell(8, 3, "$" . number_format($row['interes'], 2), 0, 0, 'L'); // Mora formateada
    $pdf->Cell(8, 3, "$" . number_format($row['total'], 2), 0, 0, 'L');   // Total formateado
    $pdf->Cell(12, 3, $row['usuario'], 0, 0, 'L');               // Operador
    $pdf->Cell(15, 3, utf8_decode($row['mediodepago']), 0, 1, 'L'); // Medio de pago
}

$pdf->Output();
