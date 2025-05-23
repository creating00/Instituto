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
$idcuotas = $_GET['idcuotas'];

$nombre = "";
$apellido = "";

$rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
while ($row = mysqli_fetch_array($rs)) {
    $idsede = $row['idsede'];
}

$rs = mysqli_query($conexion, "SELECT idalumno, nombre, apellido FROM alumno WHERE dni='$dni'");
while ($row = mysqli_fetch_array($rs)) {
    $idalumno = $row['idalumno'];
    $nombre = $row['nombre'];
    $apellido = $row['apellido'];
}

$rs = mysqli_query($conexion, "SELECT idinscripcion FROM inscripcion 
  INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
  INNER JOIN curso on inscripcion.idcurso=curso.idcurso 
  WHERE alumno.idalumno='$idalumno' and curso.idcurso='$idcurso'");
while ($row = mysqli_fetch_array($rs)) {
    $idinscripcion = $row['idinscripcion'];
}

if (empty($idinscripcion)) {
    echo "<h3> Comprobante null </h3>";
} else {
    // Ejecutar la consulta una sola vez y almacenar los resultados en un array
    $factura = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuota, cuotas.nroFactura, cuotas.mes, cuotas.año, cuotas.fecha, cuotas.importe, interes, total, usuario.nombre'usuario', sedes.nombre'sede', cuotas.mediodepago 
        FROM cuotas 
        INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
        INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
        INNER JOIN curso on inscripcion.idcurso=curso.idcurso
        INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
        INNER JOIN sedes on inscripcion.idsede=sedes.idsede
        WHERE cuotas.idcuotas = '$idcuotas' limit 1");

    // Almacenar los resultados en un array
    $facturaData = mysqli_fetch_assoc($factura);

    if ($facturaData) {
        // Obtener el número de factura desde el resultado de la consulta
        $nroFactura = $facturaData['nroFactura'] ?? 'N/A'; // Si no hay número de factura, usar 'N/A'

        // Agregar el número de factura al encabezado
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 0, utf8_decode("Factura N°: " . $nroFactura), 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 8);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
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

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(0, 0, 0); // Fondo negro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(196, 8, "Datos del Alumno", 0, 1, 'C', 1); // Celda sin borde con fondo negro
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0); // Restaurar color del texto a negro

        // Títulos de las columnas
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Nombre', 0, 0, 'L');
        $pdf->Cell(50, 6, 'Apellido', 0, 1, 'L');

        // Datos del alumno
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, utf8_decode($nombre), 0, 0, 'L');
        $pdf->Cell(50, 6, utf8_decode($apellido), 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(0, 0, 0); // Fondo negro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(196, 8, "Detalle de Pago", 0, 1, 'C', 1); // Celda sin borde
        $pdf->Ln(5); // Espacio después del encabezado

        // Restablecer colores
        $pdf->SetFillColor(255, 255, 255); // Fondo blanco
        $pdf->SetTextColor(0, 0, 0); // Texto negro

        // Títulos de las columnas
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Cursando', 0, 0, 'C'); // Título "Cursando"
        $pdf->Cell(30, 6, 'Pago', 0, 0, 'C'); // Título "Pago"
        $pdf->Cell(56, 6, 'Concepto', 0, 1, 'C'); // Título "Concepto"

        // Datos del detalle de pago
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, utf8_decode($facturaData['curso']), 0, 0, 'C'); // Curso
        $pdf->Cell(30, 6, utf8_decode($facturaData['cuota']), 0, 0, 'C'); // Pago
        $pdf->Cell(56, 6, utf8_decode($facturaData['mes'] . ' ' . $facturaData['año']), 0, 1, 'C'); // Concepto

        $pdf->Ln(8); // Espacio después de la sección


        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Cell(53, 5, 'Fecha y Hora de Pago', 0, 0, 'L');
        $pdf->Cell(30, 5, 'Monto', 0, 0, 'L');
        $pdf->Cell(30, 5, 'Mora', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Total', 0, 0, 'L');
        $pdf->Cell(28, 5, 'Operador', 0, 0, 'L');
        $pdf->Cell(45, 5, 'Medio de Pago', 0, 1, 'L');

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 11);

        $pdf->Cell(53, 5, utf8_decode($facturaData['fecha']), 0, 0, 'L');
        $pdf->Cell(30, 5, "$" . number_format($facturaData['importe'], 2), 0, 0, 'L');
        $pdf->Cell(30, 5, "$" . number_format($facturaData['interes'], 2), 0, 0, 'L');
        $pdf->Cell(25, 5, "$" . number_format($facturaData['total'], 2), 0, 0, 'L');
        $pdf->Cell(28, 5, utf8_decode($facturaData['usuario']), 0, 0, 'L');
        $pdf->Cell(45, 5, utf8_decode($facturaData['mediodepago']), 0, 1, 'L');
    }

    $pdf->Output("cuotas.pdf", "I");
}
