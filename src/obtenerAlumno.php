<?php
// Incluir la conexión
require("../conexion.php");

// Verificar si se envió el id del alumno
if (isset($_POST['idalumno'])) {
    $idalumno = $_POST['idalumno'];

    // Consultar los detalles del alumno, incluyendo la sede
    $query = "SELECT alumno.idalumno, alumno.dni, alumno.nombre, alumno.apellido, alumno.direccion, alumno.celular, alumno.email, alumno.tutor, alumno.contacto, sedes.nombre AS sede, alumno.estado 
              FROM alumno
              INNER JOIN sedes ON alumno.idsede = sedes.idsede
              WHERE alumno.idalumno = '$idalumno'";

    $resultado = mysqli_query($conexion, $query);

    if ($row = mysqli_fetch_array($resultado)) {
        // Devolver los datos en formato JSON
        echo json_encode(array(
            'nombre' => $row['nombre'],
            'dni' => $row['dni'],
            'apellido' => $row['apellido'],
            'direccion' => $row['direccion'],
            'celular' => $row['celular'],
            'email' => $row['email'],
            'tutor' => $row['tutor'],
            'contacto' => $row['contacto'],
            'sede' => $row['sede'], // Incluimos el nombre de la sede
            'estado' => $row['estado']
        ));
    } else {
        // Si no se encontró, devolver un mensaje de error
        echo json_encode(array('error' => 'Alumno no encontrado.'));
    }
} else {
    // Si no se envió el id, devolver un mensaje de error
    echo json_encode(array('error' => 'No se envió el ID del alumno.'));
}
