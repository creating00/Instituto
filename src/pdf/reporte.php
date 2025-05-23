<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

// Crear el PDF
$pdf = new FPDF('P', 'mm', array(80, 200)); // Tamaño ticket
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetTitle("Comprobante de Inscripcion");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Image("../../assets/img/agua.jpg", 15, 45, 50, 0, 'JPG');

// Obtener el ID de inscripción desde la URL
$idinscripcion = $_GET['idInscripcion'];

if (empty($idinscripcion)) {
    echo "<h3>Comprobante no encontrado, ID de inscripción inválido.</h3>";
} else {
    $config = mysqli_query($conexion, "SELECT * FROM configuracion");
    $datos = mysqli_fetch_assoc($config);
    $detalle_fac = $datos['detalle_fac'] ?? '';

    // Obtener los datos del alumno y la inscripción
    $inscripcion = mysqli_query(
        $conexion,
        "SELECT 
            curso.nombre AS 'curso', 
            inscripcion.fechacomienzo, 
            nrofactura, 
            sedes.nombre AS 'sede', 
            importe, 
            mediodepago, 
            usuario.nombre AS 'usuario', 
            usuario.usuario AS 'usuario_usuario',
            alumno.dni AS alumno_dni,
            alumno.nombre AS alumno_nombre,
            alumno.apellido AS alumno_apellido
        FROM inscripcion
        INNER JOIN curso ON inscripcion.idcurso = curso.idcurso
        INNER JOIN sedes ON inscripcion.idsede = sedes.idsede
        INNER JOIN usuario ON inscripcion.idusuario = usuario.idusuario
        INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno
        WHERE inscripcion.idinscripcion = '$idinscripcion'"
    );
    $datosInscripcion = mysqli_fetch_assoc($inscripcion);

    $nroFactura = $datosInscripcion['nrofactura'];
    $alumnoDni = $datosInscripcion['alumno_dni'];
    $alumnoNombre = $datosInscripcion['alumno_nombre'];
    $alumnoApellido = $datosInscripcion['alumno_apellido'];
    $curso = $datosInscripcion['curso'];
    $fechacomienzo = $datosInscripcion['fechacomienzo'];
    $importe = $datosInscripcion['importe'];
    $mediodepago = $datosInscripcion['mediodepago'];
    $usuarioNombre = $datosInscripcion['usuario'];

    // Encabezado del ticket
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetX(5);  // Asegura que esté alineado al borde izquierdo (márgenes establecidos a 5)
    $pdf->Cell(0, 5, utf8_decode("Nro. Factura: $nroFactura"), 0, 1, 'L');
    $pdf->Ln(5);


    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_actual = date("d-m-Y H:i:s");
    $fecha = date("d-m-Y");
    $hora = date("H:i:s");

    $pdf->SetFont('Arial', '', 5);
    $pdf->Cell(35, 4, utf8_decode("Fecha: $fecha"), 0, 0, 'L');
    $pdf->Cell(45, 4, utf8_decode("Hora: $hora"), 0, 1, 'L');
    $pdf->Ln(2);  // Espacio antes del nombre

    // Nombre de la academia
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, utf8_decode($datos['nombre']), 0, 1, 'C');  // Nombre centrado
    $pdf->Ln(1);  // Espacio antes de la imagen

    // Imagen ajustada
    $pdf->Image("../../assets/img/academia1.png", 60, $pdf->GetY() / 3, 15, 15, 'PNG');
    $pdf->Ln(1);  // Espacio después de la imagen

    // Datos de contacto de la academia
    $pdf->SetFont('Arial', 'B', 4);
    $pdf->Cell(15, 3, utf8_decode("Teléfono: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 4);
    $pdf->Cell(40, 3, $datos['telefono'], 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 4);
    $pdf->Cell(15, 3, utf8_decode("Dirección: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 4);
    $pdf->Cell(40, 3, utf8_decode($datos['direccion']), 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 4);
    $pdf->Cell(15, 3, "Correo: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 4);
    $pdf->Cell(40, 3, utf8_decode($datos['email']), 0, 1, 'L');
    $pdf->Ln();

    // Datos del alumno
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(70, 5, "Datos del Alumno", 1, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(1);

    $pdf->Cell(17, 5, utf8_decode('Cedula'), 0, 0, 'L');
    $pdf->Cell(17, 5, 'Nombre', 0, 0, 'L');
    $pdf->Cell(17, 5, 'Apellido', 0, 1, 'L');

    $pdf->SetFont('Arial', '', 5);
    $pdf->Cell(17, 1, utf8_decode($alumnoDni), 0, 0, 'L');
    $pdf->Cell(17, 1, utf8_decode($alumnoNombre), 0, 0, 'L');
    $pdf->Cell(17, 1, utf8_decode($alumnoApellido), 0, 0, 'L');
    $pdf->Ln(3);

    // Detalles de inscripción
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(70, 5, "Detalle de Inscripcion", 1, 1, 'C', 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(1);
    $pdf->Cell(25, 5, utf8_decode('Curso'), 0, 0, 'L');
    $pdf->Cell(25, 5, 'Fecha Inicio', 0, 0, 'L');
    $pdf->Ln(3);
    $pdf->SetFont('Arial', '', 5);
    $pdf->Cell(25, 5, $curso, 0, 0, 'L');
    $pdf->Cell(25, 5, $fechacomienzo, 0, 0, 'L');
    $pdf->Ln(5);

    // Detalle de pago
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
    $pdf->Cell(27, 1, "$" . $importe, 0, 0, 'L');
    $pdf->Cell(25, 1, $mediodepago, 0, 0, 'L');
    $pdf->Cell(17, 1, $usuarioNombre, 0, 1, 'L');

    // Ver detalles adicionales si existen
    /*if (!empty($detalle)) {
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 5, 'DETALLE ADICIONAL:', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 5);
        $pdf->MultiCell(0, 5, $detalle, 0, 'L');
    }*/

    if (!empty($nroFactura)) {
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
    }

    if ($uniformeData && mysqli_num_rows($uniformeData) > 0) {
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(70, 5, "Uniforme", 1, 1, 'C', 1);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(15, 5, 'Nombre', 0, 0, 'L');
        $pdf->Cell(20, 5, 'Descripcion', 0, 0, 'L');
        $pdf->Cell(20, 5, 'Talla', 0, 0, 'L');
        $pdf->Cell(15, 5, 'Precio', 0, 1, 'L');
        $pdf->Ln(1);

        while ($rowUniforme = mysqli_fetch_assoc($uniformeData)) {
            $pdf->SetFont('Arial', '', 5);
            $pdf->Cell(15, 1, utf8_decode($rowUniforme['uniforme_nombre']), 0, 0, 'L');
            $pdf->Cell(20, 1, utf8_decode($rowUniforme['uniforme_descripcion']), 0, 0, 'L');
            $pdf->Cell(20, 1, utf8_decode($rowUniforme['uniforme_talla']), 0, 0, 'L');
            $pdf->Cell(15, 1, utf8_decode("$" . number_format($rowUniforme['uniforme_precio'], 2)), 0, 1, 'L');
        }
        $pdf->Ln(5);
    }

    if (!empty($detalle_fac)) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 0, utf8_decode($detalle_fac), 0, 'C');
    }

    // Mostrar el PDF
    $pdf->Output("inscripcion.pdf", "I");
}
