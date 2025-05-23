<?php
require("../conexion.php");

if (isset($_POST['mi_busqueda'])) {
	echo '
        <table id="tblAlumno" class="table table-hover">
            <tr>
                <th scope="col">Cédula</th>
                <th scope="col">Apellido</th>
                <th scope="col">Nombre</th>
                <th scope="col">Acción</th>
            </tr>
    ';

	$curso = $_POST['mi_busqueda'];
	$sede = $_POST['sede'];
	$rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
	while ($row = mysqli_fetch_array($rs)) {
		$idsede = $row['idsede'];
	}

	if ($sede == "GENERAL") {
		$resultados = mysqli_query($conexion, "SELECT idinscripcion, activo, alumno.dni, alumno.nombre, alumno.apellido, alumno.idalumno 
            FROM inscripcion 
            INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno 
            INNER JOIN curso ON inscripcion.idcurso = curso.idcurso 
            WHERE inscripcion.idcurso='$curso'");
	} else {
		$resultados = mysqli_query($conexion, "SELECT idinscripcion, activo, alumno.dni, alumno.nombre, alumno.apellido, alumno.idalumno 
            FROM inscripcion 
            INNER JOIN alumno ON inscripcion.idalumno = alumno.idalumno 
            INNER JOIN curso ON inscripcion.idcurso = curso.idcurso 
            WHERE inscripcion.idcurso='$curso' AND inscripcion.idsede='$idsede'");
	}

	while ($consulta = mysqli_fetch_array($resultados)) {
		echo "<tr>";
		echo "<td>" . $consulta['dni'] . "</td>";
		echo "<td>" . $consulta['apellido'] . "</td>";
		echo "<td>" . $consulta['nombre'] . "</td>";

		echo "<td>";
		if ($consulta['activo'] == 1) { // Si está activo, mostramos el botón de eliminar
			echo "<button onclick='eliminar(" . $consulta['idinscripcion'] . ")' 
					class='btn btn-danger btn-sm' 
					data-toggle='tooltip' 
					data-placement='top' 
					title='Remover Alumno'>
					<i class='fas fa-user-times'></i>
				  </button>";
		} else { // Si no está activo, mostramos el botón de reinscribir
			echo "<button onclick='reinscribir(" . $consulta['idinscripcion'] . ")' 
					class='btn btn-primary btn-sm' 
					data-toggle='tooltip' 
					data-placement='top' 
					title='Reinscribir Alumno'>
					<i class='fas fa-redo-alt'></i> 
				  </button>";
		}
		echo "</td>";

		echo "</tr>";
	}
	echo "</table>";
}


if (isset($_POST['total_alumnos'])) {
	$curso = $_POST['total_alumnos'];
	$valores = array();
	$resultados = mysqli_query($conexion, "SELECT COUNT(idinscripcion)'total' FROM inscripcion INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno 
		INNER JOIN curso on inscripcion.idcurso=curso.idcurso WHERE inscripcion.idcurso='$curso'");
	while ($consulta = mysqli_fetch_array($resultados)) {
		$valores['total'] = $consulta['total'];
	}
	sleep(1);
	$valores = json_encode($valores);
	echo $valores;
}

if (isset($_POST['mi_busqueda2'])) {
	echo
	'   
			
			<table id="tblAlumno" class="table table-hover">
			<tr>
			  <th scope="col">CÉDULA</th>
			  <th scope="col">Apellido</th>
			  <th scope="col">Nombre</th>
			  
			</tr>
			
		';
	$sede = $_POST['mi_busqueda2'];

	//funcion para extraer texto de variables 
	//$curso_nombre = substr($curso,-30,-19);

	//echo $curso;
	$resultados = mysqli_query($conexion, "SELECT alumno.dni, alumno.nombre, alumno.apellido FROM alumno INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.idsede='$sede'");
	while ($consulta = mysqli_fetch_array($resultados)) {
		echo "<tr>";
		echo "<td>" . $consulta['dni'] . "</td>";

		echo "<td>" . $consulta['apellido'] . "</td>";
		echo "<td>" . $consulta['nombre'] . "</td>";

		//echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
		//echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
		//echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
		echo "</tr>";
	}
}
if (isset($_POST['total_alumnos_sede'])) {

	$sedes = $_POST['total_alumnos_sede'];
	$valores = array();
	$resultados = mysqli_query($conexion, "SELECT COUNT(idalumno)'total' FROM alumno INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.idsede='$sedes'");
	while ($consulta = mysqli_fetch_array($resultados)) {
		$valores['total'] = $consulta['total'];
	}
	sleep(1);
	$valores = json_encode($valores);
	echo $valores;
}
