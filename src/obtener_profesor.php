<?php
// Incluir la conexión
require("../conexion.php");

if (isset($_POST['idprofesor'])) {
    $idprofesor = $_POST['idprofesor'];

    // Consultar los detalles del profesor
    $query = "SELECT * FROM profesor WHERE idprofesor = '$idprofesor'";
    $resultado = mysqli_query($conexion, $query);

    if ($row = mysqli_fetch_array($resultado)) {
        // Devolver los datos en formato JSON
        echo json_encode(array(
            'nombre' => $row['nombre'],
            'dni' => $row['dni'],
            'apellido' => $row['apellido'],
            'sede' => $row['idsede'],
            'idprofesor' => $row['idprofesor']
            // Otros campos que necesites
        ));
    }
}
