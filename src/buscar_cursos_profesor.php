<?php
// Incluir la clase y la conexión
require("CursoBusqueda.php");
require("../conexion.php");

if (isset($_POST['mi_busqueda_curso']) && isset($_POST['sede'])) {
    // Crear una instancia de la clase CursoBusqueda
    $cursoBusqueda = new CursoBusqueda($conexion);

    // Obtener los cursos según la búsqueda
    $mi_busqueda_curso = $_POST['mi_busqueda_curso'];
    $sede = $_POST['sede'];
    $idprofesor = $_POST['idprofesor'];
    $resultados = $cursoBusqueda->buscarCursosProfesor($mi_busqueda_curso, $sede, $idprofesor);

    // Generar las filas de la tabla y devolverlas
    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idcurso'] . "</td>";
        echo "<td>" . $consulta['nombre'] . "</td>";
        echo "<td>" . $consulta['precio'] . "</td>";
        echo "<td>" . $consulta['tipo'] . "</td>";
        echo "<td>" . $consulta['sede'] . "</td>";
        echo "<td>" . $consulta['inscripcion'] . "</td>";
        echo "<td><button class='btn btn-primary' onclick='seleccionarCursoProfesor(" . $consulta['idcurso'] . ")'>Seleccionar</button></td>";
        echo "</tr>";
    }
}
