<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

// Configuración para ticket (tamaño reducido)
$pdf = new FPDF('P', 'mm', array(80, 200)); // Tamaño ticket (80mm de ancho x 200mm de alto)
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5); // Márgenes reducidos
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 8); // Tamaño de fuente más pequeño

// Imagen reducida
$pdf->Image("../../assets/img/agua.jpg", 15, 90, 50, 0, 'JPG');

// Obtener datos de configuración
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

// Configurar zona horaria y fecha actual
date_default_timezone_set('America/Argentina/Buenos_Aires');
$fecha_actual = date("d-m-Y H:i:s");
$fecha = date("d-m-Y");
$hora = date("H:i:s");

// Obtener parámetros de la URL
$dni = $_GET['dni'];
$idcurso = $_GET['idcurso'];
$sede = $_GET['sede'];
$idcuotas = $_GET['idcuotas'];

// Obtener datos del alumno
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

// Obtener ID de la inscripción
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
    // Obtener datos de la factura
    $factura = mysqli_query($conexion, "
                            SELECT 
                                idcuotas, 
                                alumno.apellido, 
                                alumno.nombre, 
                                alumno.dni, 
                                curso.nombre AS curso, 
                                cuota, 
                                cuotas.nroFactura, 
                                cuotas.mes, 
                                cuotas.año, 
                                cuotas.fecha, 
                                cuotas.importe, 
                                interes, 
                                total, 
                                usuario.nombre AS usuario, 
                                sedes.nombre AS sede, 
                                cuotas.mediodepago 
                            FROM cuotas 
                            INNER JOIN inscripcion ON cuotas.idinscripcion = inscripcion.idinscripcion
                            INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
                            INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
                            INNER JOIN usuario ON inscripcion.idusuario = usuario.idusuario
                            INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
                            WHERE cuotas.idcuotas = '$idcuotas'
                            LIMIT 1
                        ");


    $facturaData = mysqli_fetch_assoc($factura);

    if ($facturaData) {
        // Número de factura
        $nroFactura = $facturaData['nroFactura'] ?? 'N/A';

        // Encabezado del ticket
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 5, utf8_decode("Factura N°: " . $nroFactura), 0, 1, 'L');
        $pdf->Ln(1);

        // Fecha y hora
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(35, 4, utf8_decode("Fecha: $fecha"), 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode("Hora: $hora"), 0, 1, 'L');
        $pdf->Ln(1);

        // Logo (ajustado para ticket)
        $pdf->Image("../../assets/img/academia1.png", 30, $pdf->GetY(), 20, 20, 'PNG');
        $pdf->Ln(20); // Espacio después de la imagen   

        // Nombre de la empresa
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, utf8_decode($datos['nombre']), 0, 1, 'C');
        $pdf->Ln(3);

        // Información de contacto
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(15, 4, utf8_decode("Teléfono: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(40, 4, $datos['telefono'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(15, 4, utf8_decode("Dirección: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(40, 4, utf8_decode($datos['direccion']), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(15, 4, "Correo: ", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(40, 4, utf8_decode($datos['email']), 0, 1, 'L');
        $pdf->Ln(5);

        // Sección "Datos del Alumno"
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0, 0, 0); // Fondo negro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(70, 6, "Datos del Alumno", 0, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0); // Restaurar color del texto

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(20, 4, 'Nombre:', 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode($nombre), 0, 1, 'L');
        $pdf->Cell(20, 4, 'Apellido:', 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode($apellido), 0, 1, 'L');
        $pdf->Ln(1);

        // Sección "Detalle de Pago"
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0, 0, 0); // Fondo negro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(70, 6, "Detalle de Pago", 0, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0); // Restaurar color del texto

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(35, 4, 'Curso:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['curso']), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Pago:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['cuota']), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Concepto:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['mes'] . ' ' . $facturaData['año']), 0, 1, 'L');
        $pdf->Ln(5);

        // Detalles adicionales
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(70, 6, "Detalles Adicionales", 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(35, 4, 'Fecha y Hora:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['fecha']), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Monto:', 0, 0, 'L');
        $pdf->Cell(35, 4, "$" . number_format($facturaData['importe'], 2), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Mora:', 0, 0, 'L');
        $pdf->Cell(35, 4, "$" . number_format($facturaData['interes'], 2), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Total:', 0, 0, 'L');
        $pdf->Cell(35, 4, "$" . number_format($facturaData['total'], 2), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Operador:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['usuario']), 0, 1, 'L');
        $pdf->Cell(35, 4, 'Medio de Pago:', 0, 0, 'L');
        $pdf->Cell(35, 4, utf8_decode($facturaData['mediodepago']), 0, 1, 'L');
        $pdf->Ln(5);

        // Detalle adicional (si existe)
        if (!empty($facturaData['detalle'])) {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(70, 6, "Detalle Adicional:", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->MultiCell(70, 4, utf8_decode($facturaData['detalle']), 0, 'L');
        }

        // Generar el PDF
        $pdf->Output("cuotas.pdf", "I");
    }
}
