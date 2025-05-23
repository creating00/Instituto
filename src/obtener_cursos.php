<?php
require_once "../conexion.php"; // Asegúrate de conectar a la base de datos

$query = mysqli_query($conexion, "SELECT idcurso, nombre FROM curso WHERE estado = 1");
$cursos = [];

while ($row = mysqli_fetch_assoc($query)) {
    $cursos[] = $row;
}

echo json_encode($cursos);
