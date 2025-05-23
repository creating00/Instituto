<?php
// obtener_fecha_termino.php

include "../conexion.php";

// Obtenemos el idcurso de la URL
$idcurso = $_GET['idcurso'];

// Hacemos la consulta para obtener la fecha de término y fecha de comienzo
$query = "SELECT fechaTermino, fechaComienzo, idcurso FROM curso WHERE idcurso = '$idcurso'";
$result = mysqli_query($conexion, $query);

// Verificamos si la consulta devolvió un resultado
if ($row = mysqli_fetch_assoc($result)) {
    // Devolvemos los datos en formato JSON
    echo json_encode([
        'fechaTermino' => $row['fechaTermino'],
        'fechaComienzo' => $row['fechaComienzo'],
        'idcurso' => $row['idcurso']
    ]);
} else {
    // En caso de error, devolvemos una respuesta vacía en JSON
    echo json_encode(['fechaTermino' => '', 'fechaComienzo' => '', 'idcurso' => '']);
}
