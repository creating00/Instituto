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
                    <label for="textInput" style="font-size: 30px"><i class="fa fa-th "
                            aria-hidden="true"></i>Mes:</label>
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
                    </select>
                </div>
                <br>
                <button type="button" class="btn btn-outline-success" onclick="imprimir_gastos();"><i
                        class='fa fa-print' aria-hidden='true'></i>Generar PDF</button>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<div id="nuevo_gastos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Gasto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    {{-- Mensaje de alerta --}}
                    {!! $alert ?? '' !!}

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sp">Servicio o Producto</label>
                            <select name="servicio" class="form-control" id="sp">
                                <option value="Select">Seleccionar</option>
                                {{-- Opciones dinámicas aquí --}}
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="importe3">Monto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="importe3" id="importe3" class="form-control"
                                    placeholder="Ingrese el monto">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fechaPago">Fecha de Pago</label>
                            <input type="text" id="fechaPago" name="fechaPago" class="form-control"
                                value="{{ $fecha_actual }}" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="total">Total</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="total" id="total" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Campo oculto para sp2 --}}
                    <input type="hidden" id="sp2" name="sp2">

                    {{-- Campo oculto para la sede (visible solo si necesario) --}}
                    <div class="form-group d-none">
                        <label for="cmbsede">Sede</label>
                        <select name="sedes" class="form-control" id="cmbsede"></select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal Proveedor -->
<div id="nuevo_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">
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
                        <input type="text" placeholder="Ingrese Nombre" name="proveedor" id="proveedor"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" placeholder="Ingrese direccion" name="direccion" id="direccion"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input type="text" placeholder="Ingrese telefono" class="form-control" name="telefono"
                            id="telefono">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" placeholder="Ingrese el correo" class="form-control" name="correo"
                            id="correo">
                    </div>
                    <div class="form-group" hidden>
                        <label for="sedes">Sede</label>
                        <select name="sedes" class="form-control">
                        </select>
                    </div>
                    <input type="submit" value="Guardar Proveedor" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<!--Modal Servivio o Producto -->
<div id="nuevo_servicio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">
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
                        <input type="text" placeholder="Ingrese Servicio o Producto" name="descripcion"
                            id="descripcion" class="form-control">
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="importe">Monto</label>
                        <input type="number" placeholder="Ingrese el Monto" class="form-control" name="importe"
                            id="importe">
                    </div>
                    <div class="form-group" hidden>
                        <label for="sedes">Sede</label>
                        <select name="sedes" class="form-control">
                        </select>
                    </div>
                    <input type="submit" value="Guardar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal Pagos Profesor -->
<div id="profesor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">

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
                        <input type="text" id="fechaPagoD" class="form-control" value="{{ $fecha_actual }}"
                            name="fechaPago" class="sm-form-control">
                    </div>

                    <div class="form-group">
                        <!-- Botones Buscar -->
                        <div class="d-flex mb-3">
                            <!-- Botón Buscar Profesor -->
                            <button type="button" class="btn btn-primary mr-2" id="buscarProfesor"
                                data-toggle="modal" data-target="#modalProfesor">
                                Buscar Profesor
                            </button>

                            <!-- Botón Buscar Cursos -->
                            <button type="button" class="btn btn-primary mr-2" id="buscarCursos"
                                data-toggle="modal" data-target="#modalCursoProfesor">
                                Buscar Cursos Relacionados
                            </button>
                        </div>

                        <div class="row">
                            <!-- Columna izquierda: CÉDULA -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dni" class="d-none">CÉDULA</label>
                                    <input type="text" name="dni" id="dni" class="form-control d-none"
                                        readonly>
                                </div>
                            </div>

                            <!-- Columna derecha: Nombre del Profesor y ID -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombreProfesor" class="d-none">Nombre del Profesor</label>
                                    <input type="text" name="nombreProfesor" id="nombreProfesor"
                                        class="form-control d-none" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="idProfesor" class="d-none">ID del Profesor</label>
                                    <input type="text" name="idProfesor" id="idProfesor"
                                        class="form-control d-none">
                                    <!-- Campo oculto para el ID -->
                                </div>
                            </div>
                        </div>

                        <!-- Importe y Checkbox -->
                        <div class="form-group mb-1">
                            <label for="importeD">Monto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" placeholder="Ingrese Monto" class="form-control"
                                    name="importeD" id="importeD" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text border-0 bg-transparent p-0">
                                        <label class="mb-0 px-2 py-1 d-flex align-items-center">
                                            <input type="checkbox" id="toggleReadonly" class="form-check-input m-0"
                                                style="transform: scale(0.9); margin-right: 4px;">
                                            <span class="small">Manual</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">Activa "Manual" para editar el campo.</small>
                        </div>
                    </div>

                    <div class="form-group" hidden>
                        <label for="sedes">Sede</label>
                        <select name="sedes" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Descripcion</label>
                        <input type="text" placeholder="Ingrese la descripcion" class="form-control"
                            name="descripcion" id="descripcion">
                    </div>
                    <input type="submit" value="Guardar" class="btn btn-primary">
                    <div class="table-responsive">
                        <h5>
                            <center>Lista Pagos Profesores</center>
                        </h5>
                        <input class="form-control col-md-3 light-table-filter" data-table="order-table"
                            type="text" placeholder="Buscar">
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
                                {{-- <?php ?>
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

                                    <?php $data['idpagosprofesores']; ?>
                                    <td><?php echo $data['nombre']; ?></td>
                                    <td><?php echo $data['apellido']; ?></td>
                                    <td><?php echo $data['fecha']; ?></td>
                                    <td>$<?php echo number_format($data['importe'], 2); ?></td>
                                    <td><?php echo $data['mes']; ?></td>
                                    <td><?php echo $data['año']; ?></td>
                                    <td hidden><?php echo $data['sede']; ?></td>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td>
                                        <form action="eliminar_pagos_profesores.php?id=<?php echo $data['idpagosprofesores']; ?>"
                                            method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i
                                                    class='fas fa-trash-alt'></i> </button>
                                        </form>

                                    </td>
                                </tr>
                                <?php }
                                } ?> --}}
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
