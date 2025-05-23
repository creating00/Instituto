<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 0, 'JPG');

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);

// Obtener parámetros de la URL
$dni = $_GET['dni'];
$idcurso = $_GET['idcurso'];
$sede = $_GET['sede'];
$idcuotas = isset($_GET['idcuotas']) ? explode(',', $_GET['idcuotas']) : [];

$nombre = "";
$apellido = "";

$rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
$row = mysqli_fetch_assoc($rs);
$idsede = $row['idsede'] ?? '';

$rs = mysqli_query($conexion, "SELECT idalumno, nombre, apellido FROM alumno WHERE dni='$dni'");
$row = mysqli_fetch_assoc($rs);
$idalumno = $row['idalumno'] ?? '';
$nombre = $row['nombre'] ?? '';
$apellido = $row['apellido'] ?? '';

$rs = mysqli_query($conexion, "SELECT idinscripcion FROM inscripcion 
  INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
  INNER JOIN curso on inscripcion.idcurso=curso.idcurso 
  WHERE alumno.idalumno='$idalumno' and curso.idcurso='$idcurso'");
$row = mysqli_fetch_assoc($rs);
$idinscripcion = $row['idinscripcion'] ?? '';

if (empty($idinscripcion) || empty($idcuotas)) {
    echo "<h3> Comprobante null </h3>";
    exit;
}

// Convertir array de idcuotas a string para la consulta
$idcuotas_str = implode("','", $idcuotas);

// Obtener datos de las cuotas
$query = "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre AS curso, cuota, cuotas.nroFactura, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre AS usuario, sedes.nombre AS sede, cuotas.mediodepago 
        FROM cuotas 
        INNER JOIN inscripcion ON cuotas.idinscripcion=inscripcion.idinscripcion
        INNER JOIN alumno ON inscripcion.idalumno=alumno.idalumno
        INNER JOIN curso ON inscripcion.idcurso=curso.idcurso
        INNER JOIN usuario ON inscripcion.idusuario=usuario.idusuario
        INNER JOIN sedes ON inscripcion.idsede=sedes.idsede
        WHERE cuotas.idcuotas IN ('$idcuotas_str')";

$factura = mysqli_query($conexion, $query);

if (mysqli_num_rows($factura) == 0) {
    echo "<h3> Comprobante null </h3>";
    exit;
}

// Tomar el primer resultado para el número de factura
$facturaData = mysqli_fetch_assoc($factura);
$nroFactura = $facturaData['nroFactura'] ?? 'N/A';

// Encabezado del PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 0, utf8_decode("Factura N°: " . $nroFactura), 0, 1, 'L');

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

$pdf->Ln();
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
$pdf->Cell(50, 6, utf8_decode($nombre), 0, 0, 'L');
$pdf->Cell(50, 6, utf8_decode($apellido), 0, 1, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Concepto de Pago", 0, 1, 'C', 1);
$pdf->Ln(5);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Curso', 0, 0, 'C');
$pdf->Cell(30, 6, 'Pago', 0, 0, 'C');
$pdf->Cell(56, 6, 'Concepto', 0, 1, 'C');

mysqli_data_seek($factura, 0);
while ($row = mysqli_fetch_assoc($factura)) {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 6, utf8_decode($row['curso']), 0, 0, 'C');
    $pdf->Cell(30, 6, utf8_decode($row['cuota']), 0, 0, 'C');
    $pdf->Cell(56, 6, utf8_decode($row['mes'] . ' ' . $row['año']), 0, 1, 'C');
}


$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Detalle de Pagos", 0, 1, 'C', 1);
$pdf->Ln(5);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);

// Encabezado de la tabla de pagos
$pdf->Cell(53, 5, 'Fecha y Hora de Pago', 0, 0, 'L');
$pdf->Cell(30, 5, 'Monto', 0, 0, 'L');
$pdf->Cell(30, 5, 'Mora', 0, 0, 'L');
$pdf->Cell(25, 5, 'Total', 0, 0, 'L');
$pdf->Cell(28, 5, 'Operador', 0, 0, 'L');
$pdf->Cell(45, 5, 'Medio de Pago', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

$totalFinal = 0; // Inicializamos la suma total

mysqli_data_seek($factura, 0); // Reiniciamos el puntero del resultado

while ($row = mysqli_fetch_assoc($factura)) {
    $pdf->Cell(53, 5, utf8_decode($row['fecha']), 0, 0, 'L');
    $pdf->Cell(30, 5, "$" . number_format($row['importe'], 2), 0, 0, 'L');
    $pdf->Cell(30, 5, "$" . number_format($row['interes'], 2), 0, 0, 'L');
    $pdf->Cell(25, 5, "$" . number_format($row['total'], 2), 0, 0, 'L');
    $pdf->Cell(28, 5, utf8_decode($row['usuario']), 0, 0, 'L');
    $pdf->Cell(45, 5, utf8_decode($row['mediodepago']), 0, 1, 'L');

    // Sumar al total final
    $totalFinal += $row['total'];
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(163, 5, 'TOTAL FINAL:', 0, 0, 'R');
$pdf->Cell(25, 5, "$" . number_format($totalFinal, 2), 0, 1, 'L');

$pdf->Output("cuotas.pdf", "I");
