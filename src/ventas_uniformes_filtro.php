<?php
require_once '../conexion.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : 0; // 0 = todos
$talla = isset($_GET['talla']) ? $_GET['talla'] : '';

// Armamos condiciones dinámicas
$condiciones = [];

if ($mes > 0) {
    $condiciones[] = "MONTH(v.fecha_venta) = $mes";
}

if (!empty($talla)) {
    $condiciones[] = "u.talla = '" . mysqli_real_escape_string($conexion, $talla) . "'";
}

// Convertimos condiciones en string SQL
$where = count($condiciones) > 0 ? 'WHERE ' . implode(' AND ', $condiciones) : '';

$query = "SELECT v.id_venta, 
                 CONCAT(a.nombre, ' ', a.apellido) AS alumno, 
                 u.nombre AS uniforme, 
                 u.talla,
                 v.cantidad, 
                 v.precio_unitario, 
                 v.total, 
                 v.fecha_venta, 
                 v.medio_pago,
                 v.numero_factura,
                 us.nombre AS usuario
          FROM ventas_uniformes v
          INNER JOIN alumno a ON v.id_alumno = a.idalumno
          INNER JOIN uniformes u ON v.id_uniforme = u.id_uniforme
          INNER JOIN usuario us ON v.id_usuario = us.idusuario
          $where
          ORDER BY v.fecha_venta DESC";

$result = mysqli_query($conexion, $query);

$ventas = [];
$totalUnidades = 0;
$totalGanancia = 0.0;

while ($row = mysqli_fetch_assoc($result)) {
    $ventas[] = $row;
    $totalUnidades += $row['cantidad'];
    $totalGanancia += $row['total'];
}

echo json_encode([
    'ventas' => $ventas,
    'total_unidades' => $totalUnidades,
    'total_ganancia' => round($totalGanancia, 2)
]);
