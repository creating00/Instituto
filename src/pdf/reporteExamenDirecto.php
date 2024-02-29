<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Pago Examen final");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 'JPG');

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y ");
//echo $feha_actual;
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);
//$mes = date("m", $fechaComoEntero);

$pdf->Cell(20, 5, utf8_decode("Reporte Examen Final"), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y H:i:s");
$pdf->Cell(3, 5, utf8_decode("Fecha           Hora"), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(3, 5, utf8_decode("$feha_actual"), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(180, 6, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Image("../../assets/img/logo.png", 150, 10, 35, 35, 'PNG');
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

$idexamen=$_GET['id'];

$gastos = mysqli_query($conexion, "SELECT alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.sede, examen.fecha, total, usuario.usuario'usuario' FROM examen 
INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN usuario on examen.idusuario=usuario.idusuario WHERE examen.idexamen='$idexamen'");

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(43, 5, 'dni', 0, 0, 'L');
$pdf->Cell(38, 5, 'nombre', 0, 0, 'L');
$pdf->Cell(28, 5, 'apellido', 0, 0, 'L');
$pdf->Cell(38, 5, 'Cursando', 0, 0, 'L');
$pdf->Cell(45, 5, 'Sede', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($gastos)) {
    $pdf->Cell(43, 5, $row1['dni'], 0, 0, 'L');
    $pdf->Cell(38, 5, $row1['nombre'], 0, 0, 'L');
    $pdf->Cell(26, 5, $row1['apellido'], 0, 0, 'L');
    $pdf->Cell(38, 5, $row1['curso'], 0, 0, 'L');
    $pdf->Cell(45, 5, $row1['sede'], 0, 1, 'L');
}
$pdf->Ln(4);
$sumar = mysqli_query($conexion, "SELECT alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.sede, examen.fecha, examen.interes, total, usuario.usuario'usuario', examen.mediodepago FROM examen 
INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN usuario on examen.idusuario=usuario.idusuario WHERE examen.idexamen='$idexamen'");
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(43, 5, 'Interes', 0, 0, 'L');
$pdf->Cell(33, 5, 'Total', 0, 0, 'L');
$pdf->Cell(50, 5, 'Fecha de Pago', 0, 0, 'L');
$pdf->Cell(35, 5, 'Operador', 0, 0, 'L');
$pdf->Cell(50, 5, 'Medio de Pago', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($sumar)) {
    $pdf->Ln(4);
    $pdf->Cell(43, 5, "$".$row1['interes'], 0, 0, 'L');
    $pdf->Cell(33, 5, "$".$row1['total'], 0, 0, 'L');
    $pdf->Cell(50, 5, $row1['fecha'], 0, 0, 'L');
    $pdf->Cell(35, 5, $row1['usuario'], 0, 0, 'L');
    $pdf->Cell(50, 5, $row1['mediodepago'], 0, 1, 'L');
    
}

$pdf->Output("reporteExamenFinal.pdf", "I");


?>