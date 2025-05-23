 <?php include_once "includes/header.php";
    include "../conexion.php";
    include_once 'modal_profesor.php';
    include_once 'modal_curso.php';
    $id_user = $_SESSION['idUser'];
    $permiso = "gastos";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        header("Location: permisos.php");
    }

    //buscar sede segun usuario
    $rs = mysqli_query($conexion, "SELECT sedes.nombre FROM usuario INNER JOIN sedes on usuario.idsede=sedes.idsede WHERE usuario.idusuario ='$id_user'");
    while ($row = mysqli_fetch_array($rs)) {
        $sede = $row['nombre'];
    }

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $feha_actual = date("Y-m-d H:i:s");

    if (!empty($_POST)) {
        //
        $total = $_POST['total'];
        $fechaComoEntero = strtotime($feha_actual);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
        $fechaPago = $_POST['fechaPago'];
        $idsede = $_POST['sedes'];
        //traer servicio y producto
        $servicioProducto = $_POST['sp2'];
        $rs = mysqli_query($conexion, "SELECT idservicioproducto FROM servicioproducto WHERE descripcion ='$servicioProducto'");
        while ($row = mysqli_fetch_array($rs)) {
            $idservicioproducto = $row['idservicioproducto'];
        }
        $alert = "";
        if (empty($total)) {
            $alert = '<div class="alert alert-danger" role="alert">
                los campos son obligatorios
              </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO gastos(idservicioproducto, fecha, total, mes, año, idsede, idusuario) values ('$idservicioproducto', '$fechaPago', '$total', '$mes', '$anio', '$idsede', '$id_user')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                Gastos Registrado
              </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar Gastos
              </div>';
            }
        }
    }
    ?>
 <!-- Botones agrupados en un contenedor -->
 <div class="btn-group mb-4" role="group">
     <!-- Botón Nueva Venta -->
     <button class="btn btn-outline-danger" type="button" onclick="window.location.href='venta.php'">
         <i class="fas fa-plus"></i> Nueva Venta
     </button>

     <!-- Botón Nuevo Uniforme -->
     <button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#nuevo_uniforme">
         <i class="fas fa-plus"></i> Nuevo Uniforme
     </button>

     <!-- Botón Ganancias -->
     <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#modalUniformesVendidos">
         <i class="fa fa-money-bill-wave" aria-hidden="true"></i> Ver Ganancias
     </button>
 </div>

 <!-- Alerta opcional -->
 <?php echo isset($alert) ? $alert : ''; ?>

 <!-- Título de la lista de uniformes -->
 <div class="mt-0 text-center">
     <h4 class="font-weight-bold text-info">
         <i class="fas fa-tshirt"></i> Lista de Uniformes
     </h4>
 </div>

 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tblUniformes">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Nombre</th>
                 <th>Descripción</th>
                 <th>Talla</th>
                 <th hidden>Género</th>
                 <th>Precio</th>
                 <th>Stock</th>
                 <th>Acción</th>
             </tr>
         </thead>
         <tbody>
             <!-- Aquí la tabla está vacía inicialmente y se llenará con AJAX -->
         </tbody>
     </table>
 </div>

 <!-- SECCIÓN DE VENTAS -->
 <div class="mt-5 text-center">
     <h4 class="font-weight-bold text-info">
         <i class="fas fa-tshirt"></i> Ventas de Uniformes
     </h4>
 </div>

 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tblVentasUniformes">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Numero de Venta</th>
                 <th>Alumno</th>
                 <th>Uniforme</th>
                 <th>Cantidad</th>
                 <th>Precio Unitario</th>
                 <th>Total</th>
                 <th>Fecha de Venta</th>
                 <th>Medio de Pago</th>
                 <th>De Inscripción</th>
                 <th>Operador</th>
                 <th>Acción</th>
             </tr>
         </thead>
         <tbody>
             <!-- Aquí la tabla está vacía inicialmente y se llenará con AJAX -->
         </tbody>
     </table>
 </div>

 <!-- Modal Imprimir-->
 <div class="modal" tabindex="-1" id="imprimir">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Imprimir resumen de Gastos</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <p>Selecciona un Mes.</p>
                 <div class="col order-5">
                     <label for="textInput" style="font-size: 30px"><i class="fa fa-th " aria-hidden="true"></i>Mes:</label>
                     <select name="mes" class="form-control" id="mes" style="width:25%;">
                         <option value="1">Enero</option>
                         <option value="2">Febrero</option>
                         <option value="3">Marzo</option>
                         <option value="4">Abril</option>
                         <option value="5">Mayo</option>
                         <option value="6">Junio</option>
                         <option value="7">Julio</option>
                         <option value="8">Agosto</option>
                         <option value="9">Septiembre</option>
                         <option value="10">Octubre</option>
                         <option value="11">Noviembre</option>
                         <option value="12">Diciembre</option>
                     </select>
                 </div>
                 <div class="form-group">
                     <label for="sedes">Sede</label>
                     <select name="sedes" class="form-control" id='sede2'>
                         <?php
                            //traer sedes
                            include "../conexion.php";
                            if ($sede == "GENERAL") {

                                $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
                                $result = mysqli_num_rows($query);
                            } else {

                                $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes WHERE sedes.nombre='$sede'");
                                $result = mysqli_num_rows($query);
                            }


                            while ($row = mysqli_fetch_assoc($query)) {
                                $idsed = $row['idsede'];
                                $prov = $row['nombre'];

                            ?>

                             <option value="<?php echo $prov; ?>"><?php echo $prov; ?></option>

                         <?php
                            }

                            ?>
                     </select>
                 </div>
                 <br>
                 <button type="button" class="btn btn-outline-success" onclick="imprimir_gastos();"><i class='fa fa-print' aria-hidden='true'></i>Generar PDF</button>
             </div>
             <div class="modal-footer">

             </div>
         </div>
     </div>
 </div>

 <div id="nuevo_gastos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Gastos</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="codigo">Servicio o Producto</label><br>
                         <select name="servicio" class="form-control" id="sp" style="width:40%;" onchange="seleccionar();">
                             <option value="Select">Seleccionar</option>

                             <?php


                                //traer sedes
                                include "../conexion.php";
                                $query = mysqli_query($conexion, "SELECT descripcion FROM servicioproducto");
                                $result = mysqli_num_rows($query);

                                while ($row = mysqli_fetch_assoc($query)) {

                                    $serpro = $row['descripcion'];

                                ?>

                                 <option value="<?php echo $serpro; ?>"><?php echo $serpro; ?></option>

                             <?php
                                }

                                ?>
                         </select>

                     </div>
                     <div class="form-group">
                         <label for="producto">Monto</label>
                         <input type="number" placeholder="Ingrese su Monto" name="importe3" id="importe3" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="fecha">Fecha de Pago</label>
                         <input type="text" id="fechaPago" value="<?php echo $feha_actual; ?>" name="fechaPago" class="sm-form-control">
                     </div>
                     <div class="form-group">
                         <label for="total">total</label>
                         <input type="number" placeholder="Ingrese Total" class="form-control" name="total" id="total">
                         <input id="sp2" name="sp2" style="visibility:hidden">
                     </div>
                     <div class="form-group">
                         <label for="sedes">Sede</label>
                         <select name="sedes" class="form-control" id='cmbsede'>
                             <?php
                                //traer sedes
                                include "../conexion.php";
                                if ($sede == "GENERAL") {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
                                    $result = mysqli_num_rows($query);
                                } else {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes WHERE sedes.nombre='$sede'");
                                    $result = mysqli_num_rows($query);
                                }


                                while ($row = mysqli_fetch_assoc($query)) {
                                    $idsed = $row['idsede'];
                                    $prov = $row['nombre'];

                                ?>

                                 <option value="<?php echo $idsed; ?>"><?php echo $prov; ?></option>

                             <?php
                                }

                                ?>
                         </select>
                     </div>
                     <input type="submit" value="Guardar" class="btn btn-primary"><br>

                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal Uniformes -->
 <div id="nuevo_uniforme" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-uniforme-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="modal-uniforme-title">Nuevo Uniforme</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <!-- Área para mostrar el mensaje de éxito o error -->
                 <div id="respuesta" style="margin-top: 20px;"></div>

                 <form id="form-uniforme" action="api_insertar_uniforme.php" method="post" autocomplete="off">
                     <div class="form-group">
                         <label for="nombre">Nombre</label>
                         <input type="text" name="nombre" id="nombre" class="form-control" required>
                     </div>

                     <div class="form-group">
                         <label for="descripcion">Descripción</label>
                         <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                     </div>

                     <div class="form-group">
                         <label for="talla">Talla</label>
                         <select name="talla" id="talla" class="form-control" required>
                             <option value="">Seleccione una talla</option>
                             <option value="16">16</option>
                             <option value="S">S</option>
                             <option value="M">M</option>
                             <option value="L">L</option>
                             <option value="XL">XL</option>
                         </select>
                     </div>

                     <div class="form-group" style="display: none;">
                         <label for="genero">Género</label>
                         <select name="genero" id="genero" class="form-control" required>
                             <option value="Masculino">Masculino</option>
                             <option value="Femenino">Femenino</option>
                             <option value="Unisex" selected>Unisex</option>
                         </select>
                     </div>

                     <div class="form-group">
                         <label for="precio">Precio</label>
                         <div class="input-group">
                             <div class="input-group-prepend">
                                 <span class="input-group-text">$</span>
                             </div>
                             <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
                         </div>
                     </div>


                     <div class="form-group">
                         <label for="stock">Stock</label>
                         <input type="number" name="stock" id="stock" class="form-control" required>
                     </div>

                     <input type="submit" value="Guardar Uniforme" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal para mostrar uniformes vendidos -->
 <div class="modal fade" id="modalUniformesVendidos" tabindex="-1" role="dialog" aria-labelledby="uniformesVendidosLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">

             <div class="modal-header">
                 <h5 class="modal-title" id="uniformesVendidosLabel">
                     <i class="fa fa-tshirt"></i> Uniformes Vendidos
                 </h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>

             <div class="modal-body">

                 <div class="row mb-3">
                     <div class="col-md-6">
                         <label><strong>Filtrar por Mes:</strong></label>
                         <select class="form-control" id="mesSelect">
                             <option value="0">Todos</option>
                             <option value="1">Enero</option>
                             <option value="2">Febrero</option>
                             <option value="3">Marzo</option>
                             <option value="4">Abril</option>
                             <option value="5">Mayo</option>
                             <option value="6">Junio</option>
                             <option value="7">Julio</option>
                             <option value="8">Agosto</option>
                             <option value="9">Septiembre</option>
                             <option value="10">Octubre</option>
                             <option value="11">Noviembre</option>
                             <option value="12">Diciembre</option>
                         </select>
                     </div>
                     <div class="col-md-6">
                         <label><strong>Filtrar por Talla:</strong></label>
                         <select class="form-control" id="tallaSelect">
                             <option value="">Todas las tallas</option>
                             <option value="16">16</option>
                             <option value="S">S</option>
                             <option value="M">M</option>
                             <option value="L">L</option>
                             <option value="XL">XL</option>
                         </select>
                     </div>
                 </div>

                 <hr>

                 <div class="form-group">
                     <label style="font-size: 20px"><i class="fa fa-money-bill-wave"></i> Total Ganancia por Uniformes:</label>
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <span class="input-group-text">$</span>
                         </div>
                         <input type="text" id="totalGananciaUniformes" class="form-control text-center" readonly value="0.00">
                     </div>
                 </div>

                 <div class="form-group">
                     <label style="font-size: 20px"><i class="fa fa-boxes"></i> Total Uniformes Vendidos:</label>
                     <input type="text" id="totalUnidadesVendidas" class="form-control text-center" readonly value="0">
                 </div>

                 <!-- Aquí podrías insertar una tabla con detalle por talla si deseas más adelante -->

             </div>

             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
             </div>

         </div>
     </div>
 </div>

 <!--Modal Servivio o Producto -->
 <div id="nuevo_servicio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Servivio, Producto o Alquiler</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="servicioProducto.php" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="descripcion">Descripcion</label>
                         <input type="text" placeholder="Ingrese Servicio o Producto" name="descripcion" id="descripcion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="tipo">Tipo</label>
                         <select name="tipo" class="form-control" id="tipo" style="width:40%;">
                             <option value="Servicio">Servicio</option>
                             <option value="Producto">Producto</option>
                             <option value="Alquiler">Alquiler</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <label for="telefono">Proveedor o Arrendatario</label>
                         <select name="proveedor" class="form-control" id="proveedor" style="width:40%;">
                             <option value="Select">Select Proveedor</option>
                             <?php


                                //traer proveedor
                                include "../conexion.php";
                                $query = mysqli_query($conexion, "SELECT nombre FROM proveedores");
                                $result = mysqli_num_rows($query);

                                while ($row = mysqli_fetch_assoc($query)) {
                                    //$idrol = $row['idrol'];
                                    $prov = $row['nombre'];

                                ?>

                                 <option value="<?php echo $prov; ?>"><?php echo $prov; ?></option>

                             <?php
                                }

                                ?>
                         </select>
                     </div>
                     <div class="form-group">
                         <label for="importe">Monto</label>
                         <input type="number" placeholder="Ingrese el Monto" class="form-control" name="importe" id="importe">
                     </div>
                     <div class="form-group">
                         <label for="sedes">Sede</label>
                         <select name="sedes" class="form-control">
                             <?php
                                //traer sedes
                                include "../conexion.php";
                                if ($sede == "GENERAL") {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
                                    $result = mysqli_num_rows($query);
                                } else {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes WHERE sedes.nombre='$sede'");
                                    $result = mysqli_num_rows($query);
                                }


                                while ($row = mysqli_fetch_assoc($query)) {
                                    $idsed = $row['idsede'];
                                    $prov = $row['nombre'];

                                ?>

                                 <option value="<?php echo $idsed; ?>"><?php echo $prov; ?></option>

                             <?php
                                }

                                ?>
                         </select>
                     </div>
                     <input type="submit" value="Guardar" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!--Modal Pagos Profesor -->
 <div id="profesor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">

     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Pagos de Docentes</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="pagos_profesores.php" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="fecha">Fecha de Pago</label>
                         <input type="text" id="fechaPagoD" class="form-control" value="<?php echo $feha_actual; ?>" name="fechaPago" class="sm-form-control">
                     </div>

                     <div class="form-group">
                         <!-- Botones Buscar -->
                         <div class="d-flex mb-3">
                             <!-- Botón Buscar Profesor -->
                             <button type="button" class="btn btn-primary mr-2" id="buscarProfesor" data-toggle="modal" data-target="#modalProfesor">
                                 Buscar Profesor
                             </button>

                             <!-- Botón Buscar Cursos -->
                             <button type="button" class="btn btn-primary mr-2" id="buscarCursos" data-toggle="modal" data-target="#modalCursoProfesor">
                                 Buscar Cursos Relacionados
                             </button>
                         </div>

                         <div class="row">
                             <!-- Columna izquierda: CÉDULA -->
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="dni" class="d-none">CÉDULA</label>
                                     <input type="text" name="dni" id="dni" class="form-control d-none" readonly>
                                 </div>
                             </div>

                             <!-- Columna derecha: Nombre del Profesor y ID -->
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="nombreProfesor" class="d-none">Nombre del Profesor</label>
                                     <input type="text" name="nombreProfesor" id="nombreProfesor" class="form-control d-none" readonly>
                                 </div>
                                 <div class="form-group">
                                     <label for="idProfesor" class="d-none">ID del Profesor</label>
                                     <input type="text" name="idProfesor" id="idProfesor" class="form-control d-none"> <!-- Campo oculto para el ID -->
                                 </div>
                             </div>
                         </div>

                         <!-- Importe y Checkbox -->
                         <div class="d-flex align-items-center">
                             <div class="form-group mb-0 mr-3">
                                 <label for="importeD">Monto</label>
                                 <input type="number" placeholder="Ingrese Monto" class="form-control" name="importeD" id="importeD" readonly>
                             </div>
                             <div class="form-check d-flex align-items-center">
                                 <input type="checkbox" class="form-check-input align-self-center" id="toggleReadonly">
                                 <label class="form-check-label ml-2" for="toggleReadonly">Monto Manual</label>
                             </div>
                         </div>
                     </div>
                     <div class="form-group">
                         <label for="sedes">Sede</label>
                         <select name="sedes" class="form-control">
                             <?php
                                //traer sedes
                                include "../conexion.php";
                                if ($sede == "GENERAL") {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes");
                                    $result = mysqli_num_rows($query);
                                } else {

                                    $query = mysqli_query($conexion, "SELECT idsede, nombre FROM sedes WHERE sedes.nombre='$sede'");
                                    $result = mysqli_num_rows($query);
                                }


                                while ($row = mysqli_fetch_assoc($query)) {
                                    $idsed = $row['idsede'];
                                    $prov = $row['nombre'];

                                ?>

                                 <option value="<?php echo $idsed; ?>"><?php echo $prov; ?></option>

                             <?php
                                }

                                ?>
                         </select>
                     </div>
                     <div class="form-group">
                         <label for="telefono">Descripcion</label>
                         <input type="text" placeholder="Ingrese la descripcion" class="form-control" name="descripcion" id="descripcion">
                     </div>
                     <input type="submit" value="Guardar" class="btn btn-primary">
                     <div class="table-responsive">
                         <h5>
                             <center>Lista Pagos Profesores</center>
                         </h5>
                         <input class="form-control col-md-3 light-table-filter" data-table="order-table" type="text" placeholder="Buscar">
                         <table class="table table-bordered order-table ">
                             <thead class="thead-dark">
                                 <tr>
                                     <th>Nombre</th>
                                     <th>Apellido</th>
                                     <th>Fecha de Pago</th>
                                     <th>Monto</th>
                                     <th>mes</th>
                                     <th>año</th>
                                     <th>Sede</th>
                                     <th>Descripcion</th>
                                     <th></th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php ?>
                                 <?php
                                    include "../conexion.php";
                                    if ($sede == "GENERAL") {

                                        $query = mysqli_query($conexion, "SELECT idpagosprofesores, profesor.nombre, profesor.apellido, fecha, importe, mes, año, sedes.nombre'sede', descripcion FROM pagosprofesores
            INNER JOIN profesor on pagosprofesores.idprofesor=profesor.idprofesor
            INNER JOIN sedes on pagosprofesores.idsede=sedes.idsede");
                                    } else {

                                        $query = mysqli_query($conexion, "SELECT idpagosprofesores, profesor.nombre, profesor.apellido, fecha, importe, mes, año, sedes.nombre'sede', descripcion FROM pagosprofesores
              INNER JOIN profesor on pagosprofesores.idprofesor=profesor.idprofesor
              INNER JOIN sedes on pagosprofesores.idsede=sedes.idsede WHERE sedes.nombre='$sede'");
                                    }
                                    $result = mysqli_num_rows($query);
                                    if ($result > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) {

                                    ?>
                                         <tr>

                                             <?php $data['idpagosprofesores'] ?>
                                             <td><?php echo $data['nombre']; ?></td>
                                             <td><?php echo $data['apellido']; ?></td>
                                             <td><?php echo $data['fecha']; ?></td>
                                             <td><?php echo $data['importe']; ?></td>
                                             <td><?php echo $data['mes']; ?></td>
                                             <td><?php echo $data['año']; ?></td>
                                             <td><?php echo $data['sede']; ?></td>
                                             <td><?php echo $data['descripcion']; ?></td>
                                             <td>
                                                 <form action="eliminar_pagos_profesores.php?id=<?php echo $data['idpagosprofesores']; ?>" method="post" class="confirmar d-inline">
                                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                                 </form>

                                             </td>
                                         </tr>
                                 <?php }
                                    } ?>
                             </tbody>
                         </table>
                     </div>
                 </form>
             </div>
         </div>
     </div>
     <style>
         /* Asegurar que los modales secundarios estén por encima del primer modal */
         .modal {
             overflow-y: auto !important;
             /* Permitir scroll dentro del modal */
         }

         .modal-backdrop.show {
             opacity: 0.5;
             /* Ajustar la opacidad del backdrop */
         }
     </style>
 </div>

 <!-- Modal de Resultados -->
 <div class="modal fade" id="modalResultado" tabindex="-1" role="dialog" aria-labelledby="modalResultadoLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header" id="modalResultadoHeader">
                 <h5 class="modal-title" id="modalResultadoLabel">Resultado</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <!-- El mensaje se insertará aquí por AJAX -->
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal de Confirmación -->
 <div class="modal fade" id="modalConfirmarEliminacion" tabindex="-1" aria-labelledby="modalConfirmarEliminacionLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalConfirmarEliminacionLabel">Confirmar Eliminación</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span> <!-- La X que cierra el modal -->
                 </button>
             </div>
             <div class="modal-body">
                 ¿Estás seguro de que deseas eliminar el uniforme "<span id="nombreUniforme"></span>"?
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal de Confirmación de Eliminación de Venta -->
 <div class="modal fade" id="modalConfirmarEliminacionVenta" tabindex="-1" aria-labelledby="modalConfirmarEliminacionVentaLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalConfirmarEliminacionVentaLabel">Confirmar Eliminación de Venta</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 ¿Estás seguro de que deseas eliminar esta venta con el numero de factura "<span id="nombreVenta"></span>"?
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-danger" id="btnEliminarVenta">Eliminar</button>
             </div>
         </div>
     </div>
 </div>

 <!-- Modal de Impresión -->
 <div class="modal fade" id="impresionModal" tabindex="-1" role="dialog" aria-labelledby="impresionModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="impresionModalLabel">Seleccionar Tipo de Impresión</h5>
                 <input type="hidden" id="idVentaHidden">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="row justify-content-center mt-3">
                     <div class="col-auto">
                         <label for="tipo-impresion-modal" style="font-size: 20px;">Tipo de Impresión:</label>
                         <select id="tipo-impresion-modal" name="tipo-impresion" class="form-control" style="width: 200px; font-size: 18px; text-align: center;">
                             <option value="A4">A4</option>
                             <option value="Ticket">Ticket</option>
                         </select>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-primary" id="btnAceptarImpresion">Aceptar</button>
             </div>
         </div>
     </div>
 </div>

 <script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

 <script>
     // Función para generar la impresión
     function generarImpresion() {
         var idVenta = $("#idVentaHidden").val(); // Obtener la ID de la venta desde el campo oculto
         var tipoImpresion = $("#tipo-impresion-modal").val(); // Obtener el tipo de impresión

         console.log("ID de venta:", idVenta);
         var url;

         if (tipoImpresion === "A4") {
             url = "pdf/ventaUniformeA4.php?idVenta=" + idVenta;
         } else if (tipoImpresion === "Ticket") {
             url = "pdf/ventaUniforme.php?idVenta=" + idVenta;
         } else {
             console.error("Tipo de impresión no válido:", tipoImpresion);
             return;
         }

         console.log("URL generada:", url);
         window.open(url, "_blank"); // Abrir la URL generada en una nueva pestaña
         $("#impresionModal").modal("hide"); // Cerrar el modal
     }

     function abrirModalImpresion(idVenta) {
         // Asignar la ID de la venta al campo oculto dentro del modal
         $("#idVentaHidden").val(idVenta);

         // Abrir el modal
         $("#impresionModal").modal("show");
     }

     function cargarUniformes() {
         $.ajax({
             url: 'cargar_uniformes.php',
             method: 'GET',
             success: function(response) {
                 var uniformes = JSON.parse(response);
                 var tabla = $('#tblUniformes tbody');
                 tabla.empty(); // Limpiar la tabla antes de agregar los nuevos datos

                 // Obtener el rol del usuario
                 $.ajax({
                     url: 'validar_rol.php', // La ruta de tu API que valida el rol
                     method: 'GET',
                     success: function(rolResponse) {

                         console.log(rolResponse);
                         var rolData = rolResponse;
                         var userRole = rolData.role;

                         // Recorrer los datos y agregar las filas a la tabla
                         uniformes.forEach(function(uniforme) {
                             // Verifica si el usuario es admin antes de mostrar los botones
                             var botones = '';

                             if (rolData.role === 'admin') {
                                 // Si es admin, muestra los botones para editar y eliminar
                                 botones = `
                                <form action="editar_uniforme.php" method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="${uniforme.id}">
                                    <button class="btn btn-warning btn-modificar" type="submit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <button class="btn btn-danger btn-cambiar-estado" data-id="${uniforme.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                             } else {
                                 // Si no es admin, muestra solo el botón de edición o un mensaje
                                 botones = `
                                <button class="btn btn-warning btn-modificar" disabled>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-cambiar-estado" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                             }

                             tabla.append(`
                            <tr>
                                <td>${uniforme.id}</td>
                                <td>${uniforme.nombre}</td>
                                <td>${uniforme.descripcion}</td>
                                <td>${uniforme.talla}</td>
                                <td hidden>${uniforme.genero}</td>
                                <td>${uniforme.precio}</td>
                                <td>${uniforme.stock}</td>
                                <td>
                                    ${botones}
                                </td>
                            </tr>
                        `);
                         });

                         // Inicializar DataTable después de cargar los datos
                         $('#tblUniformes').DataTable({
                             "paging": true,
                             "lengthMenu": [5, 10, 25, 50],
                             "pageLength": 10,
                             "ordering": true,
                             "info": true,
                             "searching": true,
                             "language": {
                                 "lengthMenu": "Mostrar _MENU_ registros por página",
                                 "zeroRecords": "No se encontraron resultados",
                                 "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                 "infoEmpty": "No hay registros disponibles",
                                 "infoFiltered": "(filtrado de _MAX_ registros en total)",
                                 "search": "Buscar:",
                                 "paginate": {
                                     "first": "Primero",
                                     "last": "Último",
                                     "next": "Siguiente",
                                     "previous": "Anterior"
                                 }
                             }
                         });
                     },
                     error: function() {
                         alert('Error al obtener el rol del usuario.');
                     }
                 });
             },
             error: function() {
                 alert('Error al cargar los uniformes.');
             }
         });
     }

     function cargarVentas() {
         $.ajax({
             url: 'obtener_ventas_uniformes.php', // Archivo que devuelve los datos
             type: 'GET',
             dataType: 'json',
             success: function(response) {
                 let tbody = $("#tblVentasUniformes tbody");
                 tbody.empty();

                 $.ajax({
                     url: 'validar_rol.php', // Validar el rol del usuario
                     type: 'GET',
                     success: function(rolResponse) {
                         var rolData = rolResponse;
                         var userRole = rolData.role;

                         console.log("Rol del usuario:", userRole);

                         // Recorrer las ventas y agregar las filas a la tabla
                         $.each(response, function(index, venta) {
                             let fila = `
                        <tr>
                            <td>${venta.id_venta}</td>
                            <td>${venta.numero_factura}</td>
                            <td>${venta.alumno}</td>
                            <td>${venta.uniforme}</td>
                            <td>${venta.cantidad}</td>
                            <td>$${parseFloat(venta.precio_unitario).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                            <td>$${parseFloat(venta.total).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                            <td>${venta.fecha_venta}</td>
                            <td>${venta.medio_pago}</td>
                            <td>
                            ${venta.inscripcion == 1 
                                ? '<span class="badge badge-success" style="font-size: 16px; padding: 8px 12px;">Sí</span>' 
                                : '<span class="badge badge-secondary" style="font-size: 16px; padding: 8px 12px;">No</span>'}
                            </td>
                            <td>${venta.usuario}</td>
                            <td>
                                <!-- Botón para imprimir -->
                                <button class="btn btn-info btn-sm" onclick="abrirModalImpresion(${venta.id_venta})">
                                    <i class="fa fa-print"></i>
                                </button>
                            `;

                             // Verificar si el usuario es admin para mostrar el botón de eliminar
                             if (userRole === 'admin') {
                                 fila += `
                                <!-- Botón para eliminar -->
                                <button class="btn btn-danger btn-sm" onclick="eliminarVenta(${venta.id_venta}, '${venta.numero_factura}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            `;
                             } else {
                                 // Si no es admin, el botón de eliminar estará deshabilitado
                                 fila += `
                                <button class="btn btn-danger btn-sm" disabled>
                                    <i class="fa fa-trash"></i>
                                </button>
                            `;
                             }

                             fila += `</td></tr>`;
                             tbody.append(fila);
                         });

                         // Destruir DataTable si ya está inicializado
                         if ($.fn.DataTable.isDataTable("#tblVentasUniformes")) {
                             $("#tblVentasUniformes").DataTable().destroy();
                         }

                         // Inicializar DataTable después de cargar datos
                         $("#tblVentasUniformes").DataTable({
                             "paging": true,
                             "lengthMenu": [5, 10, 25, 50],
                             "pageLength": 5,
                             "ordering": true,
                             "info": true,
                             "searching": true,
                             "language": {
                                 "lengthMenu": "Mostrar _MENU_ registros por página",
                                 "zeroRecords": "No se encontraron resultados",
                                 "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                 "infoEmpty": "No hay registros disponibles",
                                 "infoFiltered": "(filtrado de _MAX_ registros en total)",
                                 "search": "Buscar:",
                                 "paginate": {
                                     "first": "Primero",
                                     "last": "Último",
                                     "next": "Siguiente",
                                     "previous": "Anterior"
                                 }
                             }
                         });
                     },
                     error: function() {
                         console.error("Error al obtener el rol del usuario.");
                     }
                 });
             },
             error: function() {
                 console.error("Error al obtener los datos.");
             }
         });
     }

     function eliminarVenta(idVenta, nombreVenta) {
         // Llenar el modal con el nombre de la venta o uniforme
         $("#nombreVenta").text(nombreVenta);

         // Cuando el usuario confirme la eliminación
         $("#btnEliminarVenta").off('click').on('click', function() {
             // Aquí puedes hacer la llamada a la API o el proceso para eliminar la venta
             console.log(idVenta);
             $.ajax({
                 url: 'eliminar_venta.php', // Archivo PHP que maneja la eliminación
                 type: 'POST',
                 data: {
                     id_venta: idVenta
                 }, // Enviar el ID de la venta
                 success: function(response) {
                     // Lógica para manejar la respuesta de la eliminación
                     if (response.success) {
                         // Actualiza la vista, por ejemplo recargando la tabla de ventas
                         cargarVentas(); // Llamamos a la función que recarga las ventas
                     } else {
                         alert('Hubo un problema al eliminar la venta: ' + response.message);
                     }

                     // Mostrar el mensaje de la respuesta en el modal
                     $('#modalResultado').modal('show');
                     $('#modalResultado .modal-body').html(response.message); // Usamos response.message

                     // Cerrar el modal de confirmación después de la eliminación
                     $('#modalConfirmarEliminacionVenta').modal('hide');
                 },
                 error: function() {
                     alert('Error al eliminar la venta. Intenta nuevamente.');
                 }
             });
         });

         // Mostrar el modal
         $('#modalConfirmarEliminacionVenta').modal('show');
     }

     $(document).ready(function() {

         cargarUniformes();
         // Cuando el formulario se envíe
         $("#form-uniforme").on("submit", function(event) {
             event.preventDefault(); // Evitar que el formulario se envíe de la forma tradicional

             // Mostrar un mensaje de carga
             $("#respuesta").html("<div class='alert alert-info'>Enviando datos...</div>");

             // Enviar los datos con AJAX
             $.ajax({
                 url: $(this).attr("action"),
                 method: "POST",
                 data: $(this).serialize(), // Serializar los datos del formulario
                 success: function(response) {
                     var data = JSON.parse(response); // Parsear la respuesta JSON
                     if (data.status == "success") {
                         $("#respuesta").html("<div class='alert alert-success'>" + data.message + "</div>");
                         // Limpiar el formulario después de insertar el uniforme
                         $("#form-uniforme")[0].reset();
                         // Recargar la tabla de uniformes
                         cargarUniformes();
                     } else {
                         $("#respuesta").html("<div class='alert alert-danger'>" + data.message + "</div>");
                     }
                 },
                 error: function() {
                     $("#respuesta").html("<div class='alert alert-danger'>Error al enviar los datos. Intente nuevamente.</div>");
                 }
             });
         });

         // Asignar la función de impresión al botón de aceptar dentro del modal
         $("#btnAceptarImpresion").on("click", generarImpresion);
     });

     $(document).on("click", ".btn-cambiar-estado", function() {
         var idUniforme = $(this).data("id"); // Obtiene el ID del uniforme desde el atributo 'data-id'

         // Buscar el nombre del uniforme en la fila correspondiente de la tabla
         var nombreUniforme = $(this).closest('tr').find('td:nth-child(2)').text(); // Suponiendo que el nombre está en la segunda columna

         // Mostrar el nombre del uniforme en el modal
         $('#nombreUniforme').text(nombreUniforme);

         // Guardar el ID del uniforme en el botón de eliminación
         $('#btnEliminar').data('id', idUniforme);

         // Mostrar el modal de confirmación
         $('#modalConfirmarEliminacion').modal('show');
     });

     // Acción para eliminar el uniforme al confirmar
     $(document).on("click", "#btnEliminar", function() {
         var idUniforme = $(this).data("id"); // Obtener el ID del uniforme desde el botón de eliminar

         // Hacer la solicitud AJAX para eliminar el uniforme
         $.ajax({
             url: 'eliminar_uniforme_hard.php', // El archivo PHP que realiza la eliminación
             type: 'GET',
             data: {
                 id: idUniforme
             }, // Pasar el ID del uniforme al servidor
             success: function(response) {
                 cargarUniformes();
                 // Mostrar el resultado de la eliminación (en el modal o en otro lugar)
                 $('#modalResultado').modal('show');
                 $('#modalResultado .modal-body').html(response);

                 // Cerrar el modal de confirmación
                 $('#modalConfirmarEliminacion').modal('hide');
             },
             error: function() {
                 alert('Hubo un error al eliminar el uniforme.');
             }
         });
     });


     $(document).ready(function() {
         cargarVentas();
     });
 </script>

 <script type="text/javascript">
     function seleccionar() {
         /* Para obtener el texto */
         var combo = document.getElementById("sp");
         var selected = combo.options[combo.selectedIndex].text;
         $('#sp2').val(selected);
         buscar_importe();

     }

     function buscar_importe() {
         sp2 = $("#sp2").val();


         var parametros = {
             "buscarSP": "1",
             "sp2": sp2

         };
         $.ajax({
             data: parametros,
             dataType: 'json',
             url: 'datosInscripcion.php',
             type: 'POST',

             error: function() {
                 alert("Error");
             },

             success: function(valores) {
                 $("#importe3").val(valores.importe3);
                 $("#total").val(valores.importe3);

             }
         })
     }

     function imprimir_gastos() {
         alert("Se genero el reporte");
         mes = $("#mes").val();
         sede2 = $("#sede2").val();

         var parametros = {
             "mes": mes,
             "sede2": sede2

         };
         $.ajax({
             data: parametros,
             error: function() {
                 alert("Error");
             },

             success: function() {
                 url = 'pdf/reporteGastos.php?mes=' + mes + '&sede2=' + sede2;
                 window.open(url, '_blank')
             }
         })


     }
 </script>

 <script type="text/javascript">
     (function(document) {
         'use strict';

         var LightTableFilter = (function(Arr) {

             var _input;

             function _onInputEvent(e) {
                 _input = e.target;
                 var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
                 Arr.forEach.call(tables, function(table) {
                     Arr.forEach.call(table.tBodies, function(tbody) {
                         Arr.forEach.call(tbody.rows, _filter);
                     });
                 });
             }

             function _filter(row) {
                 var text = row.textContent.toLowerCase(),
                     val = _input.value.toLowerCase();
                 row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
             }

             return {
                 init: function() {
                     var inputs = document.getElementsByClassName('light-table-filter');
                     Arr.forEach.call(inputs, function(input) {
                         input.oninput = _onInputEvent;
                     });
                 }
             };
         })(Array.prototype);

         document.addEventListener('readystatechange', function() {
             if (document.readyState === 'complete') {
                 LightTableFilter.init();
             }
         });

     })(document);
 </script>

 <script src="js/profesor_modal.js"></script>

 <script>
     $(document).ready(function() {
         console.log('Document ready!');

         // Ajustar el z-index al abrir el modal Profesor
         $('#modalProfesor').on('show.bs.modal', function() {
             console.log('Opening modalProfesor...');
             var mainModalZIndex = parseInt($('#profesor').css('z-index')) || 1050; // Valor predeterminado si no existe
             var secondaryModalZIndex = mainModalZIndex + 10;
             $(this).css('z-index', secondaryModalZIndex);

             // Crear un backdrop adicional y ajustar su z-index
             $('<div class="modal-backdrop fade show"></div>')
                 .appendTo('body')
                 .css('z-index', secondaryModalZIndex - 1);
         });

         // Limpiar el backdrop adicional al cerrar el modal Profesor
         $('#modalProfesor').on('hidden.bs.modal', function() {
             console.log('Closing modalProfesor...');
             $('.modal-backdrop').not(':first').remove();
         });

         // Ajustar el z-index al abrir el modal Curso
         $('#modalCursoProfesor').on('show.bs.modal', function() {
             console.log('Opening modalCurso...');
             var mainModalZIndex = parseInt($('#profesor').css('z-index')) || 1050; // Valor predeterminado si no existe
             var secondaryModalZIndex = mainModalZIndex + 10;
             $(this).css('z-index', secondaryModalZIndex);

             // Crear un backdrop adicional y ajustar su z-index
             $('<div class="modal-backdrop fade show"></div>')
                 .appendTo('body')
                 .css('z-index', secondaryModalZIndex - 1);
         });

         // Limpiar el backdrop adicional al cerrar el modal Curso
         $('#modalCursoProfesor').on('hidden.bs.modal', function() {
             console.log('Closing modalCurso...');
             $('.modal-backdrop').not(':first').remove();
         });

         // Abrir el modal Profesor manualmente (si es necesario)
         $('#buscarProfesor').on('click', function() {
             console.log('Button buscarProfesor clicked');
             $('#modalProfesor').modal('show');
         });

         $('#buscarCursos').on('click', function() {
             // Obtener el valor de idProfesor
             var idProfesor = $('#idProfesor').val();
             if (idProfesor) {
                 $('#modalCursoProfesor').data('idProfesor', idProfesor);
             } else {
                 console.log('No se encontró el ID del Profesor.');
             }
             console.log('Button buscarCursos clicked');
             $('#modalCursoProfesor').modal('show');
         });
     });
 </script>

 <script>
     // Al cambiar el estado del checkbox, alternamos el estado de solo lectura del input
     document.getElementById("toggleReadonly").addEventListener("change", function() {
         var importeInput = document.getElementById("importeD");

         // Si el checkbox está marcado, quitamos el readonly, si no, lo ponemos
         if (this.checked) {
             importeInput.removeAttribute("readonly");
         } else {
             importeInput.setAttribute("readonly", "true");
         }
     });
 </script>

 <script>
     function obtenerVentasUniformes(mes, talla) {
         $.ajax({
             url: "ventas_uniformes_filtro.php",
             method: "GET",
             data: {
                 mes: mes,
                 talla: talla,
             },
             dataType: "json",
             success: function(response) {
                 $("#totalGananciaUniformes").val(formatoMoneda(response.total_ganancia));
                 $("#totalUnidadesVendidas").val(response.total_unidades);
             },
             error: function(xhr, status, error) {
                 console.error("Error al obtener ventas:", error);
             },
         });
     }

     $(document).on("click", "[data-target='#modalUniformesVendidos']", function() {
         actualizarVentasUniformes();
     });

     // Función para obtener valores y llamar a la API
     function actualizarVentasUniformes() {
         const mes = parseInt($("#mesSelect").val());
         const talla = $("#tallaSelect").val();
         obtenerVentasUniformes(mes, talla);
     }

     // Cuando se abre el modal
     $('#modalUniformesVendidos').on('shown.bs.modal', function() {
         actualizarVentasUniformes();
     });

     // Cuando cambia el filtro de mes o talla
     $('#mesSelect, #tallaSelect').on('change', function() {
         actualizarVentasUniformes();
     });

     // Función para formato moneda si no la tenés aún
     function formatoMoneda(valor) {
         return "$" + parseFloat(valor).toLocaleString("en-US", {
             minimumFractionDigits: 2,
             maximumFractionDigits: 2,
         });
     }
 </script>

 <!-- Funcion buscar curso mejorada-->
 <script src="js/curso_modal_scripts.js"></script>

 <?php include_once "includes/footer.php"; ?>