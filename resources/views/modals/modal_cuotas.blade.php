<!-- Modal: Confirmar eliminación de cuota -->
<div class="modal fade" id="confirmarEliminacionCuotaModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmarEliminacionCuotaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación de Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar o actualizar esta cuota?</p>
                <input type="hidden" id="idcuota" value="{{ $cuotaId ?? '' }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminacionBtn">Eliminar/Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Confirmación administrador -->
<div class="modal fade" id="adminConfirmacionCuotaModal" tabindex="-1" role="dialog"
    aria-labelledby="adminConfirmacionCuotaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación de Administrador para Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idcuotaAdmin" value="{{ $cuotaId ?? '' }}">
                <div class="form-group">
                    <label for="password">Ingrese la contraseña del administrador:</label>
                    <input type="password" id="password" name="password" class="form-control" required
                        autocomplete="new-password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarAdminEliminacionBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<div id="cuotaModal" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="cuotaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Contenido del modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cuotaModalLabel">Cobrar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCobrarCuota">
                    <div class="container-fluid">
                        <input id="idcursoHidden" name="idcursoHidden" type="hidden">

                        <!-- Primera fila: Fecha y Cuota -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechacuota">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> Fecha:
                                </label>
                                <input type="text" id="fechacuota" name="comienzo" value="{{ $fecha_actual }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="cuota1">
                                    <i class="fa fa-square-o" aria-hidden="true"></i> Pago:
                                </label>
                                <div class="input-group">
                                    <select id="cuota1" name="cuota1" class="form-control">
                                        <option value="">Seleccione una cuota</option>
                                        <!-- Opciones dinámicas aquí -->
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" id="btnAgregarCuota"
                                            title="Agregar cuota">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda fila: Mes y Curso -->
                        <div class="row mb-3">
                            <!-- Mes -->
                            <div class="col-md-6">
                                <label for="mes">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> Mes:
                                    <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip"
                                        title="Para eliminar elemento dar doble clic" style="cursor: pointer;"></i>
                                </label>
                                <select id="mes" name="mes[]" class="form-control custom-multiple-select"
                                    multiple>
                                    <!-- Opciones agregadas dinámicamente aquí -->
                                </select>
                            </div>

                            <!-- Curso -->
                            <div class="col-md-6">
                                <label for="texcurso1">
                                    <i class="fa fa-book" aria-hidden="true"></i> Curso:
                                </label>
                                <input id="texcurso1" name="curso" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Tercera fila: Importe, Interés y Total -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="importe">Monto:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="importe" name="importe" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="interes">Mora:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" id="interes" name="interes" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="total">Total:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" id="total" name="total" class="form-control"
                                        aria-label="Total">
                                </div>
                            </div>
                        </div>

                        <!-- Cuarta fila: Medio de Pago y Tipo de Impresión -->
                        <div class="row mb-3">
                            <!-- Medio de Pago -->
                            <div class="col-md-6">
                                <label for="mediodePago">Medio de Pago:</label>
                                <select name="mediodePago" class="form-control" id="mediodePago">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="PagoFacil">Pago Fácil</option>
                                </select>
                            </div>

                            <!-- Tipo de Impresión -->
                            <div class="col-md-6">
                                <label for="tipo-impresion">Tipo de Impresión:</label>
                                <select name="tipo-impresion" class="form-control" id="tipo-impresion">
                                    <option value="A4">A4</option>
                                    <option value="Ticket">Ticket</option>
                                </select>
                            </div>
                        </div>

                        <!-- Quinta fila: Mora (oculto) -->
                        <div class="row mb-3" hidden>
                            <div class="col-md-12">
                                <label for="moraStatus">Mora está:</label>
                                <input type="text" id="moraStatus" name="moraStatus" class="form-control"
                                    readonly>
                            </div>
                        </div>

                        <!-- Sexta fila: Detalles -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="detalleCheckbox"
                                        name="detalleCheckbox">
                                    <label class="form-check-label align-middle ml-2" for="detalleCheckbox">
                                        Añadir Detalle
                                    </label>
                                </div>
                                <textarea id="detalleTextarea" name="detalle" class="form-control mt-2" rows="4"
                                    placeholder="Escribe el detalle aquí..." style="display: none;"></textarea>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <input id="idcuotas1" name="idcuotas1" type="hidden">
                                <button type="button" class="btn btn-outline-success mr-2"
                                    onclick="calcular_total(); cobrar_cuota();">Cobrar</button>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="calcular_total();">Calcular</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="resultados"></div>
                <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
