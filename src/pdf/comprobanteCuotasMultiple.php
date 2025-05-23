<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

// Configuración para ticket (tamaño reducido)
$pdf = new FPDF('P', 'mm', array(80, 297)); // Tamaño ticket (80mm de ancho x 297mm de alto)
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5); // Márgenes reducidos
$pdf->SetTitle("Comprobante de Pago");
$pdf->SetFont('Arial', 'B', 8); // Tamaño de fuente más pequeño

// Imagen reducida
$pdf->Image("../../assets/img/agua.jpg", 15, 70, 50, 0, 'JPG');

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
$idcuotas = isset($_GET['idcuotas']) ? explode(',', $_GET['idcuotas']) : [];

// Obtener datos del alumno
$rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
$row = mysqli_fetch_assoc($rs);
$idsede = $row['idsede'] ?? '';

$rs = mysqli_query($conexion, "SELECT idalumno, nombre, apellido FROM alumno WHERE dni='$dni'");
$row = mysqli_fetch_assoc($rs);
$idalumno = $row['idalumno'] ?? '';
$nombre = $row['nombre'] ?? '';
$apellido = $row['apellido'] ?? '';

// Obtener ID de la inscripción
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
$query = "SELECT 
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
          WHERE cuotas.idcuotas IN ('$idcuotas_str')";
$factura = mysqli_query($conexion, $query);

if (mysqli_num_rows($factura) == 0) {
    echo "<h3> Comprobante null </h3>";
    exit;
}

// Tomar el primer resultado para el número de factura
$facturaData = mysqli_fetch_assoc($factura);
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

// Sección "Detalle de Pagos"
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0); // Fondo negro
$pdf->SetTextColor(255, 255, 255); // Texto blanco
$pdf->Cell(70, 6, "Concepto de Pagos", 0, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0); // Restaurar color del texto
$pdf->Ln(1);

// Encabezado de la tabla de pagos
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 4, 'Curso', 0, 0, 'L');
$pdf->Cell(15, 4, 'Pago', 0, 0, 'L');
$pdf->Cell(35, 4, 'Concepto', 0, 1, 'L');
$pdf->Ln(1);

// Reiniciar el puntero del resultado
mysqli_data_seek($factura, 0);


while ($row = mysqli_fetch_assoc($factura)) {
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(30, 4, utf8_decode(substr($row['curso'], 0, 15)), 0, 0, 'L'); // Limitar a 15 caracteres
    $pdf->Cell(15, 4, utf8_decode($row['cuota']), 0, 0, 'L');
    $pdf->Cell(35, 4, utf8_decode($row['mes'] . ' ' . $row['año']), 0, 1, 'L');
    //$totalFinal += $row['total'];
}

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(0, 0, 0); // Fondo negro
$pdf->SetTextColor(255, 255, 255); // Texto blanco
$pdf->Cell(70, 6, "Detalle de Pagos", 0, 1, 'C', 1); // Encabezado de la sección
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0); // Restaurar color del texto

// Encabezado de la tabla de pagos
$pdf->SetFont('Arial', 'B', 5); // Fuente más pequeña para el encabezado
$pdf->Cell(10, 4, 'Fecha y Hora', 0, 0, 'L'); // Columna reducida
$pdf->Cell(12, 4, 'Monto', 0, 0, 'R'); // Columna reducida
$pdf->Cell(10, 4, 'Mora', 0, 0, 'R'); // Columna reducida
$pdf->Cell(12, 4, 'Total', 0, 0, 'R'); // Columna reducida
$pdf->Cell(13, 4, 'Operador', 0, 0, 'L'); // Columna reducida
$pdf->Cell(16, 4, 'Medio Pago', 0, 1, 'L'); // Columna reducida
$pdf->Ln(1);

// Datos de las cuotas
$pdf->SetFont('Arial', '', 5); // Fuente más pequeña para los datos
$totalFinal = 0; // Inicializar suma total
mysqli_data_seek($factura, 0); // Reiniciar el puntero del resultado

while ($row = mysqli_fetch_assoc($factura)) {
    $pdf->Cell(10, 4, utf8_decode(substr($row['fecha'], 0, 16)), 0, 0, 'L'); // Fecha y hora limitada a 16 caracteres
    $pdf->Cell(12, 4, "$" . number_format($row['importe'], 2), 0, 0, 'R'); // Monto
    $pdf->Cell(10, 4, "$" . number_format($row['interes'], 2), 0, 0, 'R'); // Mora
    $pdf->Cell(12, 4, "$" . number_format($row['total'], 2), 0, 0, 'R'); // Total
    $pdf->Cell(13, 4, utf8_decode(substr($row['usuario'], 0, 10)), 0, 0, 'L'); // Operador limitado a 10 caracteres
    $pdf->Cell(16, 4, utf8_decode(substr($row['mediodepago'], 0, 12)), 0, 1, 'L'); // Medio de pago limitado a 12 caracteres
    $totalFinal += $row['total']; // Sumar al total final
}

$pdf->Ln(2);

// Total final
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(55, 4, 'TOTAL FINAL:', 0, 0, 'R');
$pdf->Cell(10, 4, "$" . number_format($totalFinal, 2), 0, 1, 'R');

// Generar el PDF
$pdf->Output("cuotas.pdf", "I");
