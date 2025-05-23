<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Inscripcion");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 0, 'JPG');

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual = date("d-m-Y ");
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);

// Obtener parámetros de la URL
$idinscripcion = $_GET['idInscripcion'];

$query = "
SELECT 
    inscripcion.idinscripcion, 
    alumno.nombre AS alumno_nombre, 
    alumno.apellido AS alumno_apellido, 
    curso.nombre AS curso_nombre, 
    inscripcion.fechacomienzo, 
    inscripcion.nrofactura, 
    inscripcion.importe, 
    inscripcion.mediodepago, 
    usuario.nombre AS usuario_nombre, 
    usuario.usuario AS usuario_usuario,
    sedes.nombre AS sede_nombre
FROM inscripcion
INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
INNER JOIN usuario ON inscripcion.idusuario = usuario.idusuario
WHERE inscripcion.idinscripcion = '$idinscripcion'";

$inscripcionData = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($inscripcionData);

// Recuperar datos
$nroFactura = $row['nrofactura'] ?? 'N/A';
$curso = $row['curso_nombre'];
$importe = $row['importe'];
$mediodepago = $row['mediodepago'];
$usuarioNombre = $row['usuario_nombre'];
$sede = $row['sede_nombre'];
$fechacomienzo = $row['fechacomienzo'];
$nombre = $row['alumno_nombre'];
$apellido = $row['alumno_apellido'];

// Generar PDF
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

// Información de la inscripción
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

// Detalle de inscripción
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Detalle de Inscripcion", 0, 1, 'C', 1);
$pdf->Ln(5);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Curso', 0, 0, 'L');
$pdf->Cell(50, 6, 'Fecha Inicio', 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, utf8_decode($curso), 0, 0, 'L');
$pdf->Cell(50, 6, utf8_decode($fechacomienzo), 0, 1, 'L');

// Detalle de pago
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 8, "Detalle de Pago", 0, 1, 'C', 1);
$pdf->Ln(5);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 8, 'Monto', 0, 0, 'C');
$pdf->Cell(65, 8, 'Medio de Pago', 0, 0, 'C');
$pdf->Cell(56, 8, 'Operador', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 8, "$" . number_format($importe, 2), 0, 0, 'C');
$pdf->Cell(65, 8, utf8_decode($mediodepago), 0, 0, 'C');
$pdf->Cell(56, 8, utf8_decode($usuarioNombre), 0, 1, 'C');

$pdf->Ln(10);

$queryUniforme = "
SELECT 
    u.nombre AS uniforme_nombre, 
    u.descripcion AS uniforme_descripcion, 
    u.talla AS uniforme_talla,
    vu.precio_unitario AS uniforme_precio
FROM ventas_uniformes vu
INNER JOIN uniformes u ON vu.id_uniforme = u.id_uniforme
WHERE vu.numero_factura = '$nroFactura' AND vu.vino_de_inscripcion = 1";

$uniformeData = mysqli_query($conexion, $queryUniforme);

if (mysqli_num_rows($uniformeData) > 0) {
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(196, 8, "Uniforme", 0, 1, 'C', 1);
    $pdf->Ln(5);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 8, 'Nombre', 0, 0, 'C');
    $pdf->Cell(50, 8, 'Descripcion', 0, 0, 'C');
    $pdf->Cell(50, 8, 'Talla', 0, 0, 'C');
    $pdf->Cell(50, 8, 'Precio', 0, 1, 'C');

    while ($rowUniforme = mysqli_fetch_assoc($uniformeData)) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 8, utf8_decode($rowUniforme['uniforme_nombre']), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode($rowUniforme['uniforme_descripcion']), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode($rowUniforme['uniforme_talla']), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode("$" . number_format($rowUniforme['uniforme_precio'], 2)), 0, 1, 'C');
    }
}

if (!empty($datos['detalle_fac'])) {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(196, 8, utf8_decode($datos['detalle_fac']), 0, 'C');
}

$pdf->Output("inscripcion.pdf", "I");
