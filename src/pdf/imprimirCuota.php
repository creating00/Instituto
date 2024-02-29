<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 'JPG');

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

//$dni = $_GET['dni3'];
//$idusuario = $_GET['idusuario3'];
$idcuota = $_GET['id'];
    
        $factura = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuotas.cuota, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre'usuario', sedes.nombre'sede' , cuotas.mediodepago  FROM cuotas 
        INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
        INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
        INNER JOIN curso on inscripcion.idcurso=curso.idcurso
        INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
        INNER JOIN sedes on inscripcion.idsede=sedes.idsede
        WHERE idcuotas='$idcuota'");

$pdf->Cell(20, 5, utf8_decode("Comprobante de Pago-Cuotas"), 0, 0, 'L');
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

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Datos del Alumno", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);
//$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(47, 5, utf8_decode('dni'), 0, 0, 'L');
$pdf->Cell(40, 5, 'Nombre', 0, 0, 'L');
$pdf->Cell(40, 5, 'Apellido', 0, 0, 'L');
$pdf->Cell(56, 5, 'Sede', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);
$contador = 1;
while ($row = mysqli_fetch_assoc($factura)) {
    //$pdf->Cell(14, 5, $contador, 0, 0, 'L');
    $pdf->Cell(47, 5, $row['dni'], 0, 0, 'L');
    $pdf->Cell(40, 5, $row['nombre'], 0, 0, 'L');
    $pdf->Cell(40, 5, $row['apellido'], 0, 0, 'L');
    $pdf->Cell(56, 5, $row['sede'], 0, 1, 'L');
    //$contador++;

}

$factura = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuotas.cuota, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre'usuario', sedes.nombre'sede', cuotas.mediodepago  FROM cuotas 
INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
INNER JOIN sedes on inscripcion.idsede=sedes.idsede
WHERE idcuotas='$idcuota'");
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Detalle de Pago", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(53, 5, 'Curso', 0, 0, 'L');
$pdf->Cell(30, 5, 'Cuota', 0, 0, 'L');
$pdf->Cell(30, 5, 'Concepto', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($factura)) {
    $pdf->Cell(53, 5, $row1['curso'], 0, 0, 'L');
    $pdf->Cell(30, 5, $row1['cuota'], 0, 0, 'L');
    $pdf->Cell(15, 5, $row1['mes'], 0, 0, 'L');
    $pdf->Cell(20, 5, $row1['año'], 0, 1, 'L');
   
}
$pdf->Ln(3);

$factura = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuotas.cuota, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre'usuario', sedes.nombre'sede', cuotas.mediodepago FROM cuotas 
INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
INNER JOIN sedes on inscripcion.idsede=sedes.idsede
WHERE idcuotas='$idcuota'");
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(53, 5, 'Fecha y Hora de Pago', 0, 0, 'L');
$pdf->Cell(30, 5, 'Importe', 0, 0, 'L');
$pdf->Cell(30, 5, 'Interes', 0, 0, 'L');
$pdf->Cell(30, 5, 'Total', 0, 0, 'L');
$pdf->Cell(28, 5, 'Operador', 0, 0, 'L');
$pdf->Cell(45, 5, 'Medio de Pago', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($factura)) {
    $pdf->Cell(53, 5, $row1['fecha'], 0, 0, 'L');
    $pdf->Cell(30, 5, $row1['importe'], 0, 0, 'L');
    $pdf->Cell(30, 5, $row1['interes'], 0, 0, 'L');
    $pdf->Cell(30, 5, $row1['total'], 0, 0, 'L');
    $pdf->Cell(28, 5, $row1['usuario'], 0, 0, 'L');
    $pdf->Cell(45, 5, $row1['mediodepago'], 0, 1, 'L');
}
$pdf->Output("cuotas.pdf", "I");


?>