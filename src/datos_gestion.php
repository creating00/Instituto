<?php
require("../conexion.php");
if(isset($_POST['mostrar_inscripciones']))
	{
		echo 
	'   
        
		<table id="tablaCobros" class="table table-hover">
	    <tr class="micolor" bgcolor="#ADD8E6">
        <th scope="col">#</th>
          <th scope="col">Apellido</th> 
	      <th scope="col">Nombre</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">Curso</th>
          <th scope="col">Fecha</th>
          <th scope="col">Monto</th>
          <th scope="col">Usuario</th>
	    </tr>
        
	';

    $usuario2 = $_POST['usuario1'];
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE usuario='$usuario2'");
            while($row = mysqli_fetch_array($rs))
            {
                $idusuario2=$row['idusuario'];
            
            }
        
    $mes2 = $_POST['mes'];
    $año2 = $_POST['año'];


    $resultados = mysqli_query($conexion, "SELECT idinscripcion, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', inscripcion.fecha, inscripcion.importe, usuario.usuario FROM inscripcion 
    INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
    INNER JOIN curso on inscripcion.idcurso=curso.idcurso
    INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
    WHERE inscripcion.idusuario='$idusuario2' and inscripcion.mes='$mes2' and inscripcion.año='$año2'");
    while($consulta = mysqli_fetch_array($resultados))
        {
          
            echo "<tr>";
            echo "<td>" . $consulta['idinscripcion'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['curso'] . "</td>";
            echo "<td>" . $consulta['fecha'] . "</td>";
            echo "<td>" . $consulta['importe'] . "</td>";
            echo "<td>" . $consulta['usuario'] . "</td>";
            echo "</tr>";

        }

        $resultados3 = mysqli_query($conexion, "SELECT SUM(importe)'totalins', COUNT(idinscripcion)'cantidad' FROM inscripcion WHERE idusuario='$idusuario2' and mes='$mes2' and año='$año2'");
        while($consulta3 = mysqli_fetch_array($resultados3))
        {
            
             $totalins=$consulta3['totalins'];
             $cantidad=$consulta3['cantidad'];
             
        }
        echo "<h5>Total recaudado Inscripcion: $ $totalins </h5>";
        echo "<h6>Cantidad de Inscripciones: $cantidad </h6>";
        echo "<h6><center>Lista de Alumnos</center></h6>";
            
		
    
}
?>