<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 12);
//$pdf->Image("../../assets/img/agua.jpg", 30, 30, 170, 'JPG');

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y ");
//echo $feha_actual;
$fechaComoEntero = strtotime($feha_actual);
$anio = date("Y", $fechaComoEntero);
//$mes = date("m", $fechaComoEntero);

$pdf->Cell(20, 5, utf8_decode("Reporte de Gastos"), 0, 0, 'L');
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

$mes=$_GET['mes'];
$sede2 = $_GET['sede2'];
$gastos = mysqli_query($conexion, "SELECT servicioproducto.descripcion'sp', proveedores.nombre'proveedor', total, fecha  FROM gastos
INNER JOIN servicioproducto on gastos.idservicioproducto=servicioproducto.idservicioproducto
INNER JOIN proveedores on servicioproducto.idproveedor=proveedores.idproveedor
INNER JOIN sedes on gastos.idsede=sedes.idsede  WHERE mes='$mes' and año='$anio' and sedes.nombre='$sede2'");

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(53, 5, 'Servicio o Producto', 0, 0, 'L');
$pdf->Cell(30, 5, 'Proveedor', 0, 0, 'L');
$pdf->Cell(30, 5, 'importe', 0, 0, 'L');
$pdf->Cell(45, 5, 'fecha', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($gastos)) {
    $pdf->Cell(53, 5, $row1['sp'], 0, 0, 'L');
    $pdf->Cell(30, 5, $row1['proveedor'], 0, 0, 'L');
    $pdf->Cell(30, 5, "$".$row1['total'], 0, 0, 'L');
    $pdf->Cell(45, 5, $row1['fecha'], 0, 1, 'L');
    
}
$pdf->Ln(2);
$sumar = mysqli_query($conexion, "SELECT SUM(total)'suma' FROM gastos 
INNER JOIN servicioproducto on gastos.idservicioproducto=servicioproducto.idservicioproducto
INNER JOIN proveedores on servicioproducto.idproveedor=proveedores.idproveedor
INNER JOIN sedes on gastos.idsede=sedes.idsede  WHERE mes='$mes' and año='$anio' and sedes.nombre='$sede2'");
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 5, 'Total Gastos', 0, 0, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 11);

while ($row1 = mysqli_fetch_assoc($sumar)) {
    $pdf->Ln(4);
    $pdf->Cell(45, 5, "$".$row1['suma'], 0, 0, 'L');
    
}

$pdf->Output("reporteGastos.pdf", "I");


?>