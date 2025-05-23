<?php
session_start();
require("../conexion.php");
require_once("RolesHandler.php");

if (isset($_POST['examen'])) {

    $idinscripcion = $_POST['idinscripcion'];
    $sed = $_POST['sede'];
    $sedeF = strtoupper($sed);
    $interesF = $_POST['interesF'];
    $totalF = $_POST['totalF'];
    $mediodePagoF = $_POST['mediodePagoF'];
    $usuarioF = $_POST['usuario'];
    $idcuotaE = $_POST['idcuotaE'];
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE nombre ='$usuarioF' or usuario='$usuarioF'");
    while ($row = mysqli_fetch_array($rs)) {
        $idusuario3 = $row['idusuario'];
    }
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fechaExamen = date("Y-m-d");
    $alert = "";
    if (empty($totalF) || empty($idinscripcion)) {
        echo '<script language="javascript">';
        echo 'alert("Todos los datos son Obligatorios");';
        echo '</script>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM examen WHERE idinscripcion = '$idinscripcion' and sede = '$sedeF' ");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            echo '<script language="javascript">';
            echo 'alert("El alumno ya Pago el Examen en ese Curso");';
            echo '</script>';
        } else {


            $fechaComoEntero = strtotime($fechaExamen);
            $anioe = date("Y", $fechaComoEntero);
            $mes3 = $_POST['mes'];
            $query_insert = mysqli_query($conexion, "INSERT INTO examen(idinscripcion, interes, total, fecha, mediodepago, sede, idusuario, idcuota, mes, año) values ('$idinscripcion', '$interesF', '$totalF', '$fechaExamen', '$mediodePagoF', '$sedeF', '$idusuario3', '$idcuotaE', '$mes3', '$anioe')");
            if ($query_insert) {
                echo '<script language="javascript">';
                echo 'alert("Se Cobro el Examen Final");';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("Error al Cobrar Examen Final");';
                echo '</script>';
            }
        }

        //insertar el examen en cuotas
        $rs = mysqli_query($conexion, "SELECT idexamen FROM examen WHERE idinscripcion='$idinscripcion' ORDER BY idexamen DESC limit 1");
        while ($row = mysqli_fetch_array($rs)) {
            $idexamen = $row['idexamen'];
        }

        $rs = mysqli_query($conexion, "UPDATE cuotas SET idexamen='$idexamen' WHERE idcuotas='$idcuotaE'");
        if ($rs) {
            echo '<script language="javascript">';

            echo '</script>';
        } else {
            echo '<script language="javascript">';

            echo '</script>';
        }
    }
}

if (isset($_POST['mostrar'])) {
    $mes1 = $_POST['mes1'];
    $anio = $_POST['anio'];
    $valores = array();
    $resultados = mysqli_query($conexion, "SELECT SUM(importe)'total' FROM cuotas where mes='$mes1' and año='$anio'");
    while ($consulta = mysqli_fetch_array($resultados)) {

        $valores['total'] = $consulta['total'];
    }

    sleep(1);
    $valores = json_encode($valores);
    echo $valores;
}

if (isset($_POST['cobrar_cuota'])) {
    $idcuotas1 = $_POST['idcuotas1']; // Array de IDs
    $mediodePago = $_POST['mediodePago'];
    $idusuario1 = $_POST['idusuario'];
    $isDetalle = isset($_POST['isDetalle']) && $_POST['isDetalle'] === 'true' ? 1 : 0;
    $detalle = $_POST['detalle'];
    $nroFactura = $_POST['nroFactura'];

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fechacuota = date("Y-m-d");

    if (empty($idcuotas1)) {
        echo '<script>alert("No hay cuotas seleccionadas.");</script>';
    } else {
        foreach ($idcuotas1 as $idcuota) {
            // Consultamos los valores de cada cuota
            $query = "SELECT importe, interes FROM cuotas WHERE idcuotas = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $idcuota);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();

            $importe = $row['importe'] ?? 0;
            $interes = $row['interes'] ?? 0;
            $total = $importe + $interes;

            // Actualizamos la cuota individual
            $updateQuery = "UPDATE cuotas SET 
                fecha='$fechacuota', 
                interes='$interes', 
                total='$total', 
                condicion='PAGADO', 
                mediodepago='$mediodePago', 
                idusuario='$idusuario1', 
                detalle='$detalle',
                nrofactura='$nroFactura'
                WHERE idcuotas = ?";

            $stmt = $conexion->prepare($updateQuery);
            $stmt->bind_param("i", $idcuota);
            $stmt->execute();
        }

        //echo '<script>alert("Se cobró el pago correctamente.");</script>';
    }
}


if (isset($_POST['mi_busqueda_inscripcion'])) {
    echo
    '   
        
		<table id="tablaInscripcion" class="table table-hover" >
	    <tr class="micolor" bgcolor="#F5DEB3">
        <th scope="col">#</th>
	      <th scope="col">Curso</th>
	      <th scope="col">Fecha Inscripcion</th>
	      <th scope="col">Precio</th>
          <th scope="col">Duracion</th>
          <th scope="col">fecha de Inicio</th>
	    </tr>
        
	';

    $mi_busqueda = $_POST['mi_busqueda_inscripcion'];

    $sede = $_POST['sede'];
    $dni = $_POST['dni'];
    $usuario = $_POST['usuario'];
    //traer id Alumno
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE nombre ='$usuario' or usuario='$usuario'");
    while ($row = mysqli_fetch_array($rs)) {
        $idusuario = $row['idusuario'];
    }


    $idin = 0;
    if ($sede == "GENERAL") {
        $query = "
        SELECT 
            idinscripcion, 
            alumno.dni, 
            alumno.nombre AS alumno, 
            inscripcion.idcurso, 
            curso.nombre AS curso, 
            curso.precio, 
            inscripcion.fecha, 
            inscripcion.fechacomienzo, 
            inscripcion.estado, 
            curso.duracion, 
            curso.tipo 
        FROM inscripcion
        LEFT JOIN alumno ON inscripcion.idalumno = alumno.idalumno
        LEFT JOIN curso ON inscripcion.idcurso = curso.idcurso
        LEFT JOIN sedes ON inscripcion.idsede = sedes.idsede
        LEFT JOIN usuario ON inscripcion.idusuario = usuario.idusuario
        WHERE alumno.dni = '$dni'
        ORDER BY idinscripcion DESC
    ";
        $resultados = mysqli_query($conexion, $query);
    } else {

        $resultados = mysqli_query($conexion, "SELECT idinscripcion, alumno.dni, alumno.nombre'alumno', curso.nombre'curso',curso.precio, fecha, fechacomienzo, inscripcion.estado, curso.duracion, curso.tipo FROM inscripcion
            INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
            INNER JOIN curso on inscripcion.idcurso=curso.idcurso
            INNER JOIN sedes on inscripcion.idsede=sedes.idsede
            INNER JOIN usuario on inscripcion.idusuario=usuario.idusuario
            WHERE alumno.dni='$dni' AND sedes.nombre='$sede' ORDER BY idinscripcion DESC");
    }

    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idinscripcion'] . "</td>";
        echo "<td>" . $consulta['curso'] . "</td>";
        echo "<td>" . $consulta['fecha'] . "</td>";
        echo "<td>$" . number_format($consulta['precio'], 2, '.', ',') . "</td>";
        echo "<td>" . $consulta['tipo'] . "</td>";
        echo "<td>" . $consulta['fechacomienzo'] . "</td>";
        echo "<td hidden>" . $consulta['idcurso'] . "</td>";

        echo "</tr>";
    }
}

if (isset($_POST['mi_busqueda'])) {
    echo
    '   
        
		<table id="tablaAlumno" class="table table-hover">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">NOMBRE</th>
	      <th scope="col">APELLIDO</th>
	    </tr>
        
	';
    $mi_busqueda = $_POST['mi_busqueda'];
    $usuario = $_POST['usuario'];
    $sede = $_POST['sede'];
    //echo "Alumnos de La Sede: ",$sede;

    if ($sede == "GENERAL") {
        $resultados = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE alumno.nombre LIKE '%$mi_busqueda%' OR dni LIKE '%$mi_busqueda%' LIMIT 5");
        while ($consulta = mysqli_fetch_array($resultados)) {
            echo "<tr>";
            echo "<td>" . $consulta['idalumno'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";

            echo "</tr>";
        }
    } else {

        $resultados = mysqli_query($conexion, "SELECT idalumno, dni, alumno.nombre, apellido, alumno.direccion, celular, alumno.email, tutor, contacto, sedes.nombre'sede', alumno.estado  FROM alumno
        INNER JOIN sedes on alumno.idsede=sedes.idsede WHERE sedes.nombre='$sede' AND alumno.nombre LIKE '%$mi_busqueda%' LIMIT 5");
        while ($consulta = mysqli_fetch_array($resultados)) {
            echo "<tr>";
            echo "<td>" . $consulta['idalumno'] . "</td>";
            echo "<td>" . $consulta['dni'] . "</td>";
            echo "<td>" . $consulta['nombre'] . "</td>";
            echo "<td>" . $consulta['apellido'] . "</td>";
            echo "</tr>";
        }
    }
}

if (isset($_POST['mi_busqueda_cuotas'])) {
    echo
    '   
        
		<table id="tablaCuotas" class="table table-hover">
	    <tr class="micolor" bgcolor="#87CEEB">
        <th scope="col">#</th>
	      <th scope="col">fecha de Pago</th>
	      <th scope="col">Pago</th>
	      <th scope="col">Mes</th>
          <th scope="col">Año</th>
          <th scope="col">Monto</th>
          <th scope="col">Mora</th>
          <th scope="col">Total</th>
          <th scope="col">Condicion</th>
          <th scope="col">Operador</th>
	    </tr>
        
	';

    $mi_busqueda = $_POST['mi_busqueda_cuotas'];

    $texcurso = $_POST['texcurso'];
    $idinscripcion = $_POST['idinscripcion'];

    //traer cuotas
    $query = "
    SELECT 
        c.idcuotas, 
        c.fecha, 
        c.cuota, 
        c.mes, 
        c.año, 
        c.importe, 
        c.interes, 
        c.total, 
        c.condicion, 
        u.usuario, 
        c.idexamen, 
        cr.idcurso AS id_curso 
    FROM cuotas AS c
    INNER JOIN usuario AS u ON c.idusuario = u.idusuario
    INNER JOIN inscripcion AS i ON c.idinscripcion = i.idinscripcion
    INNER JOIN curso AS cr ON i.idcurso = cr.idcurso
    WHERE i.idinscripcion = ?
    ";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idinscripcion); // "i" indica que el parámetro es un entero
    $stmt->execute();
    $resultados = $stmt->get_result();

    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idcuotas'] . "</td>";
        echo "<td>" . $consulta['fecha'] . "</td>";
        echo "<td bgcolor='#FAFAD2'>" . $consulta['cuota'] . "</td>";

        if ($consulta['condicion'] == "PENDIENTE") {
            echo "<td bgcolor='#F08080'>" . $consulta['mes'] . "</td>";
        } else {
            echo "<td bgcolor='#90EE90'>" . $consulta['mes'] . "</td>";
        }

        echo "<td>" . $consulta['año'] . "</td>";
        echo "<td>$ " . number_format($consulta['importe'], 2, '.', ',') . "</td>";
        echo "<td>$ " . number_format($consulta['interes'], 2, '.', ',') . "</td>";
        echo "<td>$ " . number_format($consulta['total'], 2, '.', ',') . "</td>";


        if ($consulta['condicion'] == "PENDIENTE") {
            echo "<td bgcolor='#F08080'>" . $consulta['condicion'] . "</td>";
        } else {
            echo "<td bgcolor='#90EE90'>" . $consulta['condicion'] . "</td>";
        }

        echo "<td>" . $consulta['usuario'] . "</td>";
        echo '<td>
                    <a href="#" class="btn btn-link imprimir-btn" data-toggle="modal" data-target="#impresionModal" onclick="guardarIdCuota(' . $consulta['idcuotas'] . ')">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </a>
                </td>';

        // Verificación de rol y generación del botón de eliminar
        $rolesHandler = new RolesHandler($conexion);
        $userRole = $rolesHandler->validarRol($_SESSION['idUser']);

        if ($userRole === 'admin') {
            // Botón para abrir el modal de confirmación de eliminación (administrador)
            echo '<td>
                    <button class="btn btn-danger openAdminModal" data-id="' . $consulta['idcuotas'] . '">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>';
        } else {
            // Botón para abrir el modal de confirmación para usuarios sin permisos
            echo '<td>
                    <button class="btn btn-danger openUserModal" data-id="' . $consulta['idcuotas'] . '">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>';
        }

        echo "<td hidden>" . $consulta['id_curso'] . "</td>";


        // Validación para examen
        if ($consulta['idexamen'] != 0) {
            echo "<td><a href='pdf/reporteExamenDirecto.php?id=" . $consulta['idexamen'] . "' target='_blank'>Examen</a></td>";
        }

        echo "</tr>";
    }
}

if (isset($_POST['mostrar_cobros'])) {
    echo
    '   
		<table id="tablaCobros" class="table table-hover">
	    <tr class="micolor" bgcolor="#ADD8E6">
        <th scope="col">#</th>
        <th scope="col">Apellido</th>
	      <th scope="col">Nombre</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">Curso</th>
          <th scope="col">Pago</th>
          <th scope="col">fecha de Pago</th>
          <th scope="col">Monto</th>
          <th scope="col">Mora</th>
          <th scope="col">Total</th>
          <th scope="col">Usuario</th>
          <th scope="col">Total Examen</th>
	    </tr>
        
	';

    $mostrar_cobros = $_POST['mostrar_cobros'];
    $usuario2 = $_POST['usuario1'];
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE usuario='$usuario2'");
    while ($row = mysqli_fetch_array($rs)) {
        $idusuario2 = $row['idusuario'];
    }



    $mes2 = $_POST['mes'];
    $año2 = $_POST['año'];
    $idinscripcionn = 0;
    $cantidad = 0;
    $retotal = 0;
    $superTotal = 0;
    $totalexamen = 0;
    $Supertotalexamen = 0;
    //examen
    $totalexamen = 0;

    $resultados2 = mysqli_query($conexion, "SELECT total'totalexamen' FROM examen WHERE idusuario='$idusuario2'");
    while ($consulta2 = mysqli_fetch_array($resultados2)) {
        $totalexamen = $consulta2['totalexamen'];

        //echo $totalexamen;
    }


    //traer cuotas
    $resultados = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuota, cuotas.fecha, cuotas.importe, cuotas.interes, cuotas.total, usuario.usuario, idexamen, cuotas.idinscripcion FROM cuotas 
        INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
        INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
        INNER JOIN curso on inscripcion.idcurso=curso.idcurso
        INNER JOIN usuario on cuotas.idusuario=usuario.idusuario
        WHERE cuotas.idusuario='$idusuario2' and cuotas.mes='$mes2' and cuotas.año='$año2' and condicion='Pagado'");


    $i = 0;
    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idcuotas'] . "</td>";
        echo "<td >" . $consulta['apellido'] . "</td>";
        echo "<td>" . $consulta['nombre'] . "</td>";
        echo "<td>" . $consulta['dni'] . "</td>";
        echo "<td bgcolor='#E0FFFF'>" . $consulta['curso'] . "</td>";
        echo "<td bgcolor='#F0E68C'>" . $consulta['cuota'] . "</td>";
        echo "<td>" . $consulta['fecha'] . "</td>";
        echo "<td>" . $consulta['importe'] . "</td>";
        echo "<td>" . $consulta['interes'] . "</td>";
        echo "<td bgcolor='#90EE90'>" . $consulta['total'] . "</td>";
        echo "<td bgcolor='#DCDCDC'>" . $consulta['usuario'] . "</td>";
        $superTotal = $superTotal + $consulta['total'];
        $idinscripcionn = $consulta['idinscripcion'];


        if ($consulta['idexamen'] == 0) {
        } else {

            echo "<td bgcolor='#FFD700'>" . $totalexamen . "</td>";

            $Supertotalexamen = $Supertotalexamen + $totalexamen;
        }


        $i++;
        echo "</tr>";
    }
    $retotal = $superTotal + $Supertotalexamen;
    $resultados3 = mysqli_query($conexion, "SELECT SUM(importe)'totalins', COUNT(idinscripcion)'cantidad' FROM inscripcion WHERE idusuario='$idusuario2' and mes='$mes2' and año='$año2'");
    while ($consulta3 = mysqli_fetch_array($resultados3)) {

        $totalins = $consulta3['totalins'];
        $cantidad = $consulta3['cantidad'];
    }


    echo "<h5>Total recaudado Pagos mas Examen: $ $retotal </h5>";
    echo "<h6>Cantidad de Alumnos: $i </h6>";
    echo "<h5>Total recaudado Inscripcion: $ $totalins </h5>";
    echo "<h6>Cantidad de Inscripciones: $cantidad </h6>";
    echo "<h6><center>Lista de Alumnos Cobros Pagos mas Examen</center></h6>";
}

if (isset($_POST['mostrar_cobros_fecha'])) {
    echo
    '   
        
		<table id="tablaCobros" class="table table-hover">
	    <tr class="micolor" bgcolor="#ADD8E6">
        <th scope="col">#</th>
          <th scope="col">Apellido</th> 
	      <th scope="col">Nombre</th>
	      <th scope="col">CÉDULA</th>
	      <th scope="col">Curso</th>
          <th scope="col">Pago</th>
          <th scope="col">Mes</th>
          <th scope="col">fecha de Pago</th>
          <th scope="col">Monto</th>
          <th scope="col">Mora</th>
          <th scope="col">Total</th>
          <th scope="col">Usuario</th>
          <th scope="col">Examen</th>
	    </tr>
        
	';

    $mostrar_cobros_fecha = $_POST['mostrar_cobros_fecha'];
    $usuario2 = $_POST['usuario1'];
    $rs = mysqli_query($conexion, "SELECT idusuario FROM usuario WHERE usuario='$usuario2'");
    while ($row = mysqli_fetch_array($rs)) {
        $idusuario2 = $row['idusuario'];
    }

    $mes2 = $_POST['mes'];
    $año2 = $_POST['año'];
    $retotal = 0;
    $superTotal = 0;
    $totalexamen = 0;
    $Supertotalexamen = 0;

    $totalexamen = 0;
    $resultados2 = mysqli_query($conexion, "SELECT total'totalexamen' FROM examen WHERE idusuario='$idusuario2'");
    while ($consulta2 = mysqli_fetch_array($resultados2)) {

        $totalexamen = $consulta2['totalexamen'];
        //echo $totalexamen;

    }

    //traer cuotas
    $resultados = mysqli_query($conexion, "SELECT idcuotas, alumno.apellido, alumno.nombre, alumno.dni, curso.nombre'curso', cuota, cuotas.mes, cuotas.fecha, cuotas.importe, cuotas.interes, cuotas.total, usuario.usuario, idexamen FROM cuotas 
        INNER JOIN inscripcion on cuotas.idinscripcion=inscripcion.idinscripcion
        INNER JOIN alumno on inscripcion.idalumno=alumno.idalumno
        INNER JOIN curso on inscripcion.idcurso=curso.idcurso
        INNER JOIN usuario on cuotas.idusuario=usuario.idusuario WHERE cuotas.fecha='$mostrar_cobros_fecha' and cuotas.idusuario='$idusuario2' ");

    $i = 0;
    while ($consulta = mysqli_fetch_array($resultados)) {
        echo "<tr>";
        echo "<td>" . $consulta['idcuotas'] . "</td>";
        echo "<td>" . $consulta['apellido'] . "</td>";
        echo "<td>" . $consulta['nombre'] . "</td>";
        echo "<td>" . $consulta['dni'] . "</td>";
        echo "<td bgcolor='#E0FFFF'>" . $consulta['curso'] . "</td>";
        echo "<td bgcolor='#F0E68C'>" . $consulta['cuota'] . "</td>";
        echo "<td>" . $consulta['mes'] . "</td>";
        echo "<td>" . $consulta['fecha'] . "</td>";
        echo "<td>" . $consulta['importe'] . "</td>";
        echo "<td>" . $consulta['interes'] . "</td>";
        echo "<td bgcolor='#90EE90'>" . $consulta['total'] . "</td>";
        echo "<td bgcolor='#DCDCDC'>" . $consulta['usuario'] . "</td>";
        $superTotal = $superTotal + $consulta['total'];

        if ($consulta['idexamen'] == 0) {
        } else {

            echo "<td bgcolor='#FFD700'>" . $totalexamen . "</td>";
            $Supertotalexamen = $Supertotalexamen + $totalexamen;
        }



        echo "</tr>";
        $i++;
    }
    $retotal = $superTotal + $Supertotalexamen;
    $resultados3 = mysqli_query($conexion, "SELECT SUM(importe)'totalins', COUNT(idinscripcion)'cantidad' FROM inscripcion WHERE idusuario='$idusuario2' and mes='$mes2' and año='$año2'");
    while ($consulta3 = mysqli_fetch_array($resultados3)) {

        $totalins = $consulta3['totalins'];
        $cantidad = $consulta3['cantidad'];
    }


    echo "<h5>Total recaudado Pagos mas Examen: $ $retotal </h5>";
    echo "<h6>Cantidad de Alumnos: $i </h6>";
    echo "<h5>Total recaudado Inscripcion: $ $totalins </h5>";
    echo "<h6>Cantidad de Inscripciones: $cantidad </h6>";
    echo "<h6><center>Lista de Alumnos Cobros Pagos mas Examen</center></h6>";
}

?>
<script type="text/javascript">
    //click en tabla cuotas
    /*$('#tablaCuotas tr').on('click', function() {
        var dato1 = $(this).find('td:first').html();
        $('#idcuotas').val(dato1);
        var dato = $(this).find('td:nth-child(3)').html();
        $('#cuota').val(dato);
        var dato2 = $(this).find('td:nth-child(1)').html();
        $('#cuota1').val(dato2);
        var dato6 = $(this).find('td:nth-child(4)').html();
        $('#mes').val(dato6);
        var dato3 = $(this).find('td:nth-child(6)').html().replace('$ ', '').replace('.', '').replace(',', '.');
        $('#importe').val(dato3);
        var dato4 = $(this).find('td:nth-child(7)').html().replace('$ ', '').replace('.', '').replace(',', '.');
        $('#interes').val(dato4);
        var dato5 = $(this).find('td:first').html();
        $('#idcuotas1').val(dato5);
        var dato7 = $(this).find('td:first').html();
        $('#idcuotaE').val(dato7);
        var dato8 = $(this).find('td:nth-child(3)').html();
        $('#cuotaE').val(dato8);
    });*/

    //click en tabla insc	
    $('#tablaInscripcion tr').on('click', function() {
        var dato1 = $(this).find('td:first').html();
        $('#idinscripcion').val(dato1);
        var dato = $(this).find('td:nth-child(2)').html();
        $('#texcurso').val(dato);
        var dato2 = $(this).find('td:nth-child(2)').html();
        $('#texcurso1').val(dato2);
        var dato3 = $(this).find('td:nth-child(2)').html();
        $('#texcurso2').val(dato3);

        var idCurso = $(this).find('td:last').html();
        $('#idcursoHidden').val(idCurso);
    });

    $('#tablaAlumno tr').on('click', function() {
        var dato = $(this).find('td:nth-child(2)').html();
        var dato3 = $(this).find('td:nth-child(3)').html();
        var dato4 = $(this).find('td:nth-child(4)').html();
        var dato5 = $(this).find('td:nth-child(1)').html();
        $('#dni').val(dato);
        $('#nombre').val(dato3);
        $('#apellido').val(dato4);
        $('#search').val(dato5);
    });
</script>