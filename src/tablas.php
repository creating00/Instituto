<?php
	require("../conexion.php");

  if(isset($_POST['listaExamen']))
	{
    
    echo 
    '   
      <h5><center>Lista de Examenes Cobrados</center></h5>    
      <table id="tablaUsuario" class="table table-hover">
        <tr>
          <th scope="col">#</th>
          <th scope="col">DNI</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Curso</th>
          <th scope="col">Fecha</th>
          <th scope="col">Total</th>
          <th scope="col">Operador</th>
        </tr>
          
    ';
    $dni = $_POST['dni'];
    $sede = $_POST['sede'];
  
            if($sede=="GENERAL"){

              $resultados = mysqli_query($conexion, "SELECT idexamen, alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.fecha, total, usuario.usuario'usuario' FROM examen 
              INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
              INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
              INNER JOIN curso on inscripcion.idcurso=curso.idcurso
              INNER JOIN usuario on examen.idusuario=usuario.idusuario"); 

            }else{

            $resultados = mysqli_query($conexion, "SELECT idexamen, alumno.dni, alumno.nombre, alumno.apellido, curso.nombre'curso', examen.fecha, total, usuario.usuario'usuario' FROM examen 
            INNER JOIN inscripcion on examen.idinscripcion=inscripcion.idinscripcion
            INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
            INNER JOIN curso on inscripcion.idcurso=curso.idcurso
            INNER JOIN usuario on examen.idusuario=usuario.idusuario WHERE examen.sede='$sede' and alumno.dni='$dni'");

            }
            while($consulta = mysqli_fetch_array($resultados))
	          {
            echo "<tr>";
            echo "<td>" . $consulta['idexamen'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            echo "<td>" . $consulta['curso'] . "</td>";
            echo "<td>" . $consulta['fecha'] . "</td>";
            echo "<td>" . $consulta['total'] . "</td>";
            echo "<td>" . $consulta['usuario'] . "</td>";
            echo "<td><form action='eliminar_examen.php?id=".$consulta['idexamen']."' method='post' class='confirmar d-inline'>
                                    <button class='btn btn-danger' type='submit'><i class='fas fa-trash-alt'></i> </button>
                      </form></td>";
            //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
	          }

    

  }


  if(isset($_POST['mi_busqueda_usuario']))
	{
		echo 
	'   
        
		<table id="tablaUsuario" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Usuario</th>
	      <th scope="col">Nombre</th>
	      <th scope="col">Correo</th>
	    </tr>
        
	';
		$mi_busqueda_usuario = $_POST['mi_busqueda_usuario'];
    $sede = $_POST['sede'];

      //CONSULTAR
      $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
      while($row = mysqli_fetch_array($rs))
      {
          $idsede=$row['idsede'];
      
      }
    if($sede=="GENERAL"){
     
      $resultados = mysqli_query($conexion,"SELECT * FROM usuario WHERE usuario LIKE '%$mi_busqueda_usuario%' LIMIT 5");


    }else{

      $resultados = mysqli_query($conexion,"SELECT * FROM usuario WHERE usuario LIKE '%$mi_busqueda_usuario%' and idsede='$idsede' LIMIT 5");


    }   
	    while($consulta = mysqli_fetch_array($resultados))
	    {
            echo "<tr>";
            echo "<td>" . $consulta['idusuario'] . "</td>";
            echo "<td>" . $consulta['usuario'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['correo'] . "</td>";
            //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
	  }	
}

	if(isset($_POST['mi_busqueda_curso']))
	{
		echo 
	'   
        
		<table id="tablaCurso" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">CURSO</th>
	      <th scope="col">PRECIO</th>
	      <th scope="col">DURACION</th>
        <th scope="col">SEDE</th>
	    </tr>
        
	';
		$mi_busqueda_curso = $_POST['mi_busqueda_curso'];
    $sede = $_POST['sede'];
    $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            }
    if($sede=="GENERAL"){
		$resultados = mysqli_query($conexion,"SELECT idcurso, curso.nombre, precio, tipo, sedes.nombre'sede' FROM curso 
    inner join sedes on curso.idsede=sedes.idsede WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado=1 LIMIT 5");
	    while($consulta = mysqli_fetch_array($resultados))
	    {
            echo "<tr>";
            echo "<td>" . $consulta['idcurso'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['precio'] . "</td>";
            echo "<td>" . $consulta['tipo'] . "</td>";
            echo "<td>" . $consulta['sede'] . "</td>";
            //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
	  }
  }else{
     
    $resultados = mysqli_query($conexion,"SELECT idcurso, curso.nombre, precio, tipo, sedes.nombre'sede'  FROM curso
    inner join sedes on curso.idsede=sedes.idsede WHERE curso.nombre LIKE '%$mi_busqueda_curso%' AND curso.estado=1 and curso.idsede='$idsede' LIMIT 5");
    while($consulta = mysqli_fetch_array($resultados))
    {
          echo "<tr>";
          echo "<td>" . $consulta['idcurso'] . "</td>";
          echo "<td>" . $consulta['nombre'] . "</td>";
          echo "<td>" . $consulta['precio'] . "</td>";
          echo "<td>" . $consulta['tipo'] . "</td>";
          echo "<td>" . $consulta['sede'] . "</td>";
          //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
          //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
          //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
          echo "</tr>";

  }	
}
  }
	
   
	if(isset($_POST['mi_busqueda']))
	{
		echo 
	'   
        
		<table id="tablaAlumno" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">DNI</th>
	      <th scope="col">NOMBRE</th>
	      <th scope="col">APELLIDO</th>
        <th scope="col">SEDE</th>
	    </tr>
        
	';
		$mi_busqueda = $_POST['mi_busqueda'];
        $usuario = $_POST['usuario'];
        $sede = $_POST['sede'];
        //echo "Alumnos de La Sede: ",$sede;
        
        //sedes
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            }
        if($sede=="GENERAL"){
		$resultados = mysqli_query($conexion,"SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.nombre LIKE '%$mi_busqueda%' LIMIT 5");
	    while($consulta = mysqli_fetch_array($resultados))
	    {
            echo "<tr>";
            echo "<td>" . $consulta['idalumno'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            echo "<td>" . $consulta['sede'] . "</td>";
            //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
	  }	

	} else{
        
        $resultados = mysqli_query($conexion,"SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.idsede='$idsede' AND alumno.nombre LIKE '%$mi_busqueda%' AND alumno.estado=1 LIMIT 5");
	    while($consulta = mysqli_fetch_array($resultados))
	    {
			echo "<tr>";
            echo "<td>" . $consulta['idalumno'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            echo "<td>" . $consulta['sede'] . "</td>";
            //echo "<td><a href='inscripcion.php?id=".$consulta['idalumno']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
 	    }	

    }
}
if(isset($_POST['mi_busquedaP']))
	{
		echo 
	'   
        
		<table id="tablaProfesor" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">DNI</th>
	      <th scope="col">NOMBRE</th>
	      <th scope="col">APELLIDO</th>
	    </tr>
        
	';
		    $mi_busquedaP = $_POST['mi_busquedaP'];
        $usuario = $_POST['usuario'];
        $sede = $_POST['sede'];
        //echo "Alumnos de La Sede: ",$sede;
        $rs = mysqli_query($conexion, "SELECT idsede FROM sedes WHERE nombre ='$sede'");
        while($row = mysqli_fetch_array($rs))
            {
                $idsede=$row['idsede'];
            
            }
        if($sede=="GENERAL"){
		$resultados = mysqli_query($conexion,"SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
		INNER JOIN sedes on profesor.idsede=sedes.idsede WHERE profesor.nombre LIKE '%$mi_busquedaP%' OR dni LIKE '%$mi_busquedaP%' AND profesor.estado=1 LIMIT 5");
	    while($consulta = mysqli_fetch_array($resultados))
	    {
			
            echo "<tr>";
            echo "<td>" . $consulta['idprofesor'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            //echo "<td><a href='inscripcion.php?idP=".$consulta['idprofesor']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>";
	  }	
			

	} else{
        
        $resultados = mysqli_query($conexion,"SELECT idprofesor, dni, profesor.nombre, apellido, profesor.direccion, celular, profesor.email, sedes.nombre'sede', profesor.estado  FROM profesor
		INNER JOIN sedes on profesor.idsede=sedes.idsede WHERE profesor.nombre LIKE '%$mi_busquedaP%' AND profesor.idsede='$idsede' AND profesor.estado=1 LIMIT 5");
	    while($consulta = mysqli_fetch_array($resultados))
	    {
			echo "<tr>";
            echo "<td>" . $consulta['idprofesor'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            //echo "<td><a href='inscripcion.php?idP=".$consulta['idprofesor']."'><i class='fas fa-trash-alt'></i></a></td>";
            //echo "<td><a href='javascript: prueba();'> <img src='.' alt='Seleccionar'></a></td>";
            //echo "<td><input type='button' value='Seleccionar' class='btn btn-primary' name='btn_inscribir' onclick='prueba();'></td>";
            echo "</tr>"; 
	    }	

    }
}
	
  echo '</table>';
  
?>
<script type="text/javascript">
//click tabla usuario modal
$('#tablaUsuario tr').on('click', function(){
  var dato2 = $(this).find('td:nth-child(2)').html();
  var dato3 = $(this).find('td:nth-child(3)').html();
  var dato4 = $(this).find('td:nth-child(4)').html();
  $('#usuario1').val(dato2);
  $('#nombre').val(dato3);
  $('#correo').val(dato4);
});


//click en tabla funcion	
$('#tablaProfesor tr').on('click', function(){
  var dato = $(this).find('td:nth-child(2)').html();
  var dato3 = $(this).find('td:nth-child(3)').html();
  var dato4 = $(this).find('td:nth-child(4)').html();
  $('#dniP').val(dato);
  $('#nombreP').val(dato3);
  $('#apellidoP').val(dato4);
});

//click en tabla curso
$('#tablaCurso tr').on('click', function(){
  var dato = $(this).find('td:nth-child(2)').html();
  var dato3 = $(this).find('td:nth-child(3)').html();
  var dato4 = $(this).find('td:nth-child(4)').html();
  $('#curso').val(dato);
  $('#precio').val(dato3);
  $('#duracion').val(dato4);
});

$('#tablaAlumno tr').on('click', function(){

  var dato = $(this).find('td:nth-child(2)').html();
  var dato3 = $(this).find('td:nth-child(3)').html();
  var dato4 = $(this).find('td:nth-child(4)').html();
  var dato5 = $(this).find('td:nth-child(5)').html();
  $('#dni').val(dato);
  $('#nombre').val(dato3);
  $('#apellido').val(dato4);
  $('#sede').val(dato5);
  
  
});

</script>