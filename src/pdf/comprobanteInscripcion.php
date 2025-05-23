<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', array(80, 200)); // Tamaño ticket
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Image("../../assets/img/agua.jpg", 15, 50, 50, 0, 'JPG');

$idinscripcion = $_GET['id'];

$rs = mysqli_query($conexion, "SELECT idalumno FROM inscripcion WHERE idinscripcion='$idinscripcion'");
while ($row = mysqli_fetch_array($rs)) {
    $idalumno = $row['idalumno'];
}
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
//$idalumno = mysqli_query($conexion, "SELECT idalumno FROM inscripcion WHERE idinscripcion='$idinscripcion'");
$alumno = mysqli_query($conexion, "SELECT * FROM alumno WHERE idalumno ='$idalumno' ");
$datosA = mysqli_fetch_assoc($alumno);
$inscripcion = mysqli_query($conexion, "SELECT 
    curso.nombre AS curso, 
    inscripcion.fechacomienzo,
    nrofactura, 
    sedes.nombre AS sede, 
    inscripcion.importe, 
    inscripcion.mediodepago, 
    usuario.nombre AS usuario 
FROM inscripcion
INNER JOIN curso on inscripcion.idcurso=curso.idcurso
INNER JOIN sedes on inscripcion.idsede = sedes.idsede
INNER JOIN usuario on inscripcion.idusuario = usuario.idusuario
WHERE idinscripcion='$idinscripcion'");

$datosInscripcion = mysqli_fetch_assoc($inscripcion);
$nroFactura = $datosInscripcion['nrofactura'];

$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(0, 0, utf8_decode("Comprobante de Inscripción - Nro. Factura: $nroFactura"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 5);

date_default_timezone_set('America/Argentina/Buenos_Aires');

$fecha_actual = date("d-m-Y H:i:s");
$fecha = date("d-m-Y");
$hora = date("H:i:s");

$pdf->Cell(35, 4, utf8_decode("Fecha: $fecha"), 0, 0, 'L');
$pdf->Cell(45, 4, utf8_decode("Hora: $hora"), 0, 1, 'L');

$pdf->Ln(2);  // Espacio antes del nombre

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, utf8_decode($datos['nombre']), 0, 1, 'C');  // Nombre centrado

$pdf->Ln(1);  // Espacio antes de la imagen

// Imagen ajustada para no ocupar mucho espacio
$pdf->Image("../../assets/img/academia1.png", 60, $pdf->GetY() / 3, 15, 15, 'PNG');  // Ajuste de la imagen
$pdf->Ln(5);  // Espacio después de la imagen

$pdf->SetFont('Arial', 'B', 4);  // Fuente más pequeña
$pdf->Cell(15, 3, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);  // Fuente más pequeña
$pdf->Cell(15, 3, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['direccion']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 4);  // Fuente más pequeña
$pdf->Cell(15, 3, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(40, 3, utf8_decode($datos['email']), 0, 1, 'L');

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 5);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Datos del Alumno", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(2);

$pdf->Cell(17, 5, utf8_decode('Cedula'), 0, 0, 'L');
$pdf->Cell(17, 5, 'Nombre', 0, 0, 'L');
$pdf->Cell(17, 5, 'Apellido', 0, 1, 'L');

$pdf->SetFont('Arial', '', 5);
$pdf->Cell(17, 5, utf8_decode($datosA['dni']), 0, 0, 'L');
$pdf->Cell(17, 5, utf8_decode($datosA['nombre']), 0, 0, 'L');
$pdf->Cell(17, 5, utf8_decode($datosA['apellido']), 0, 0, 'L');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 5);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Inscripcion", 1, 1, 'C', 1);
$pdf->Ln(2);
$pdf->SetTextColor(0, 0, 0);


//$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(25, 5, utf8_decode('curso'), 0, 0, 'L');
$pdf->Cell(25, 5, 'fecha de Inicio', 0, 0, 'L');
//$pdf->Cell(17, 5, 'Sede', 0, 1, 'L');

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 5);
$contador = 1;
while ($row = mysqli_fetch_assoc($inscripcion)) {
    //$pdf->Cell(14, 5, $contador, 0, 0, 'L');
    $pdf->Cell(25, 5, $row['curso'], 0, 0, 'L');
    $pdf->Cell(25, 5, $row['fechacomienzo'], 0, 0, 'L');
    //$pdf->Cell(17, 5, $row['sede'], 0, 1, 'L');
    //$contador++;
}

$inscripcion1 = mysqli_query($conexion, "
    SELECT 
        curso.nombre AS curso, 
        inscripcion.fechacomienzo, 
        sedes.nombre AS sede, 
        inscripcion.importe, 
        inscripcion.mediodepago, 
        usuario.nombre AS usuario, 
        inscripcion.detalle 
    FROM inscripcion
    INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
    INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
    INNER JOIN usuario ON inscripcion.idusuario = usuario.idusuario
    WHERE inscripcion.idinscripcion = '$idinscripcion'
");

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Pago", 1, 1, 'C', 1);
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(27, 5, 'Monto Total', 0, 0, 'L');
$pdf->Cell(25, 5, 'Medio de Pago.', 0, 0, 'L');
$pdf->Cell(17, 5, 'Operador', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 5);

while ($row1 = mysqli_fetch_assoc($inscripcion1)) {
    $pdf->Cell(27, 5, "$" . $row1['importe'], 0, 0, 'L');
    $pdf->Cell(25, 5, $row1['mediodepago'], 0, 0, 'L');
    $pdf->Cell(17, 5, $row1['usuario'], 0, 1, 'L');
    $detalle = $row1['detalle'];
}

if (!empty($detalle)) {
    $pdf->Ln(5); // Espacio adicional para separar del contenido principal
    $pdf->SetFont('Arial', 'B', 6); // Cambiar estilo para el título del detalle
    $pdf->Cell(0, 5, 'DETALLE ADICIONAL:', 0, 1, 'L');

    $pdf->SetFont('Arial', '', 5); // Volver al estilo regular para el contenido
    $pdf->MultiCell(0, 5, $detalle, 0, 'L');
}

$pdf->Output("inscripcion.pdf", "I");
