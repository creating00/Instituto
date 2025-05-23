<?php
// obtenerMoraStatus.php

// Asegúrate de incluir tu conexión a la base de datos
include('../conexion.php');

// Obtener el ID de la cuota desde la solicitud POST
if (isset($_POST['cuotaId'])) {
    $cuotaId = $_POST['cuotaId'];

    // Realizar la consulta para obtener si tiene mora
    $query = "SELECT tieneMora FROM cuotas WHERE idcuotas = '$cuotaId'";
    $resultado = mysqli_query($conexion, $query);

    if ($row = mysqli_fetch_assoc($resultado)) {
        // Devuelve 'true' si tiene mora, 'false' si no
        echo $row['tieneMora'] ? 'true' : 'false';
    } else {
        // Si no se encuentra la cuota, devolver 'false' por defecto
        echo 'false';
    }
}
