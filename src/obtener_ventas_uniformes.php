<?php
require_once '../conexion.php';

$query = "SELECT v.id_venta, 
                 CONCAT(a.nombre, ' ', a.apellido) AS alumno, 
                 u.nombre AS uniforme, 
                 v.cantidad, 
                 v.precio_unitario, 
                 v.total, 
                 v.fecha_venta, 
                 v.medio_pago,
                 v.numero_factura,
                 v.vino_de_inscripcion AS inscripcion,
                 us.nombre AS usuario
          FROM ventas_uniformes v
          INNER JOIN alumno a ON v.id_alumno = a.idalumno
          INNER JOIN uniformes u ON v.id_uniforme = u.id_uniforme
          INNER JOIN usuario us ON v.id_usuario = us.idusuario
          ORDER BY v.fecha_venta DESC";

$result = mysqli_query($conexion, $query);
$ventas = [];

while ($row = mysqli_fetch_assoc($result)) {
    $ventas[] = $row;
}

echo json_encode($ventas);
