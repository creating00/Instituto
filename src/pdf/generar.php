<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante Inscripcion");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 'JPG');

$idinscripcion = $_GET['id'];

  $rs = mysqli_query($conexion, "SELECT idalumno FROM inscripcion WHERE idinscripcion='$idinscripcion'");
  while($row = mysqli_fetch_array($rs))
  {
      $idalumno=$row['idalumno'];
  
  }
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
//$idalumno = mysqli_query($conexion, "SELECT idalumno FROM inscripcion WHERE idinscripcion='$idinscripcion'");
$alumno = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno ='$idalumno' ");
$datosA = mysqli_fetch_assoc($alumno);
$inscripcion = mysqli_query($conexion, "SELECT curso.nombre'curso', fechacomienzo, sedes.nombre'sede', importe, mediodepago,usuario.nombre'usuario' FROM inscripcion
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN sedes on inscripcion.idsede = sedes.idsede
INNER JOIN usuario on inscripcion.idusuario = usuario.idusuario
WHERE idinscripcion='$idinscripcion'");
$pdf->Cell(20, 5, utf8_decode("Comprobante de Inscripcion"), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 8);
date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y H:i:s");
$pdf->Cell(3, 5, utf8_decode("Fecha           Hora"), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(3, 5, utf8_decode("$feha_actual"), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(180, 6, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Image("../../assets/img/logo.jpeg", 150, 10, 35, 35, 'JPEG');
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
$pdf->Cell(196, 5, "Datos del Alumno", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->Cell(85, 5, utf8_decode('DNI'), 0, 0, 'L');
$pdf->Cell(65, 5, utf8_decode('Apellido'), 0, 0, 'L');
$pdf->Cell(56, 5, utf8_decode('Nombre'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(85, 5, utf8_decode($datosA['dni']), 0, 0, 'L');
$pdf->Cell(65, 5, utf8_decode($datosA['apellido']), 0, 0, 'L');
$pdf->Cell(56, 5, utf8_decode($datosA['nombre']), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Detalle de Inscripcion", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);
//$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(85, 5, utf8_decode('curso'), 0, 0, 'L');
$pdf->Cell(65, 5, 'fecha de Inicio', 0, 0, 'L');
$pdf->Cell(56, 5, 'Sede', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);
$contador = 1;
while ($row = mysqli_fetch_assoc($inscripcion)) {
    //$pdf->Cell(14, 5, $contador, 0, 0, 'L');
    $pdf->Cell(85, 5, $row['curso'], 0, 0, 'L');
    $pdf->Cell(65, 5, $row['fechacomienzo'], 0, 0, 'L');
    $pdf->Cell(56, 5, $row['sede'], 0, 1, 'L');
    //$contador++;

}

$inscripcion1 = mysqli_query($conexion, "SELECT curso.nombre'curso', fechacomienzo, sedes.nombre'sede', importe, mediodepago,usuario.nombre'usuario' FROM inscripcion
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN sedes on inscripcion.idsede = sedes.idsede
INNER JOIN usuario on inscripcion.idusuario = usuario.idusuario
WHERE idinscripcion='$idinscripcion'");
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Detalle de Pago", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 5, 'Importe Total', 0, 0, 'L');
$pdf->Cell(65, 5, 'Medio de Pago.', 0, 0, 'L');
$pdf->Cell(56, 5, 'Operador', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($inscripcion1)) {
    $pdf->Cell(85, 5, "$".$row1['importe'], 0, 0, 'L');
    $pdf->Cell(65, 5, $row1['mediodepago'], 0, 0, 'L');
    $pdf->Cell(56, 5, $row1['usuario'], 0, 1, 'L');
}
$pdf->Output("inscripcion.pdf", "I");


?>
