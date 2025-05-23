<?php
// Incluir la clase y la conexión
require("ProfesorBusqueda.php");
require("../conexion.php");

if (isset($_POST['mi_busqueda_profesor']) && isset($_POST['sede'])) {
    // Crear una instancia de la clase ProfesorBusqueda
    $profesorBusqueda = new ProfesorBusqueda($conexion);

    // Obtener los profesores según la búsqueda
    $mi_busqueda_profesor = $_POST['mi_busqueda_profesor'];
    $sede = $_POST['sede'];
    $resultados = $profesorBusqueda->buscarProfesores($mi_busqueda_profesor, $sede);

    // Generar las filas de la tabla y devolverlas
    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idprofesor'] . "</td>";
        echo "<td>" . $consulta['dni'] . "</td>";
        echo "<td>" . $consulta['nombre'] . "</td>";
        echo "<td>" . $consulta['apellido'] . "</td>";
        echo "<td>" . $consulta['sede'] . "</td>"; // Modificado a 'sede' en lugar de 'apellido'
        echo "<td><button class='btn btn-primary' onclick='seleccionarProfesor(" . $consulta['idprofesor'] . ")'>Seleccionar</button></td>";
        echo "</tr>";
    }
}
