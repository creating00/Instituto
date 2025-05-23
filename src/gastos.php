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
 <button class="btn btn-outline-danger" type="button" data-toggle="modal" data-target="#nuevo_gastos"><i class="fas fa-plus"></i>Nuevo Gastos</button>
 <button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#nuevo_proveedor"><i class="fas fa-plus"></i>Nuevo Proveedor</button>
 <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#nuevo_servicio"><i class="fas fa-plus"></i>Nuevo Producto o Servicio</button><br><br>
 <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#profesor"><i class="fas fa-plus"></i>Pagos docentes</button><br><br>

 <?php echo isset($alert) ? $alert : ''; ?>
 <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#imprimir"><i class='fa fa-print' aria-hidden='true'></i>.Imprimir</button><br><br>

 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Servicio o Producto</th>
                 <th>Fecha y Hora</th>
                 <th>Total</th>
                 <th>Mes</th>
                 <th>Año</th>
                 <th hidden>Sede</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";
                if ($sede == "GENERAL") {

                    $query = mysqli_query($conexion, "SELECT idgastos, descripcion'sp', fecha,total,mes,año, sedes.nombre'sede' FROM gastos 
                INNER JOIN servicioproducto on gastos.idservicioproducto=servicioproducto.idservicioproducto
                INNER JOIN sedes on gastos.idsede=sedes.idsede");
                    $result = mysqli_num_rows($query);
                } else {
                    $query = mysqli_query($conexion, "SELECT idgastos, descripcion'sp', fecha,total,mes,año, sedes.nombre'sede' FROM gastos 
                    INNER JOIN servicioproducto on gastos.idservicioproducto=servicioproducto.idservicioproducto
                    INNER JOIN sedes on gastos.idsede=sedes.idsede WHERE sedes.nombre='$sede'");
                    $result = mysqli_num_rows($query);
                }

                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                ?>
                     <tr>
                         <td><?php echo $data['idgastos']; ?></td>
                         <td><?php echo $data['sp']; ?></td>
                         <td><?php echo $data['fecha']; ?></td>
                         <td><?php echo '$' . number_format($data['total'], 2, '.', ','); ?></td>
                         <td><?php echo $data['mes']; ?></td>
                         <td><?php echo $data['año']; ?></td>
                         <td hidden><?php echo $data['sede']; ?></td>
                         <td>

                             <!--<a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>-->

                             <!--<a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>-->

                             <form action="eliminar_gastos.php?id=<?php echo $data['idgastos']; ?>" method="post" class="confirmar d-inline">
                                 <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                             </form>

                         </td>
                     </tr>
             <?php }
                } ?>
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
                 <div class="form-group" hidden>
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
                         <div class="input-group">
                             <div class="input-group-prepend">
                                 <span class="input-group-text">$</span>
                             </div>
                             <input type="number" placeholder="Ingrese su Monto" name="importe3" id="importe3" class="form-control">
                         </div>
                     </div>

                     <div class="form-group">
                         <label for="fecha">Fecha de Pago</label>
                         <input type="text" id="fechaPago" value="<?php echo $feha_actual; ?>" name="fechaPago" class="sm-form-control">
                     </div>
                     <div class="form-group">
                         <label for="total">Total</label>
                         <div class="input-group">
                             <div class="input-group-prepend">
                                 <span class="input-group-text">$</span>
                             </div>
                             <input type="number" placeholder="Ingrese Total" class="form-control" name="total" id="total">
                         </div>
                         <input id="sp2" name="sp2" style="visibility:hidden">
                     </div>

                     <div class="form-group" hidden>
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

 <!--Modal Proveedor -->
 <div id="nuevo_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Proveedor o Arrendatario</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="proveedores.php" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="proveedor">Nombre</label>
                         <input type="text" placeholder="Ingrese Nombre" name="proveedor" id="proveedor" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="direccion">Direccion</label>
                         <input type="text" placeholder="Ingrese direccion" name="direccion" id="direccion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="telefono">Telefono</label>
                         <input type="text" placeholder="Ingrese telefono" class="form-control" name="telefono" id="telefono">
                     </div>
                     <div class="form-group">
                         <label for="correo">Correo</label>
                         <input type="email" placeholder="Ingrese el correo" class="form-control" name="correo" id="correo">
                     </div>
                     <div class="form-group" hidden>
                         <label for="sedes">Sede</label>
                         <select name="sedes" class="form-control">
                             <?php                            //traer sedes
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
                     <input type="submit" value="Guardar Proveedor" class="btn btn-primary">
                 </form>
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
                     <div class="form-group" hidden>
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
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text">$</span>
                                     </div>
                                     <input type="number" placeholder="Ingrese Monto" class="form-control" name="importeD" id="importeD" readonly>
                                 </div>
                             </div>


                             <div class="d-flex align-items-center">
                                 <input type="checkbox" class="form-check-input" id="toggleReadonly">
                                 <label class="form-check-label ml-2" for="toggleReadonly">Monto Modal</label>
                             </div>
                         </div>
                     </div>

                     <div class="form-group" hidden>
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
                                     <th hidden>Sede</th>
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
                                             <td>$<?php echo number_format($data['importe'], 2); ?></td>
                                             <td><?php echo $data['mes']; ?></td>
                                             <td><?php echo $data['año']; ?></td>
                                             <td hidden><?php echo $data['sede']; ?></td>
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

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
 <!-- Funcion buscar curso mejorada-->
 <script src="js/curso_modal_scripts.js"></script>

 <?php include_once "includes/footer.php"; ?>