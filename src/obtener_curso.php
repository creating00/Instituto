<?php
// Conectar a la base de datos
require("../conexion.php");

// Obtener el ID del curso desde el parámetro POST
$idcurso = $_POST['idcurso'];

// Realizar la consulta para obtener los detalles del curso
$query = "SELECT nombre, duracion, precio, idprofesor, monto, inscripcion, fechaComienzo, fechaTermino, idcurso FROM curso WHERE idcurso = '$idcurso'";
$resultado = mysqli_query($conexion, $query);

// Comprobar si se encontró el curso
if (mysqli_num_rows($resultado) > 0) {
    $curso = mysqli_fetch_assoc($resultado);
    // Enviar los datos del curso como respuesta JSON
    echo json_encode($curso);
} else {
    // Si no se encuentra el curso, enviar un error
    echo json_encode(["error" => "Curso no encontrado"]);
}
