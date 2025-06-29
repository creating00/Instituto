@extends('layouts.app')

@section('title', 'Cobrar Pagos Estudiantes')


@push('styles')
    <style>
        .custom-multiple-select {
            height: 100px;
            border-radius: 5px;
            padding: 5px;
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <h3 class="text-center">Cobrar Pagos Estudiantes</h3>
    <br>

    <div class="container">
        <div class="row">
            <!-- Usuario -->
            <div class="col-md-4">
                <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                <input id="usuario" class="form-control text-uppercase text-danger font-weight" value="{{ $nombre_user }}"
                    readonly aria-label="Usuario">
            </div>

            <!-- Fecha -->
            <div class="col-md-4">
                <label for="fechainicio"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha</label>
                <input type="text" id="fechainicio" name="comienzo" class="form-control" value="{{ $fecha_actual }}"
                    readonly aria-label="Fecha actual">
            </div>

            <!-- Sede (oculto) -->
            <div class="col-md-4" hidden>
                <label for="sede"><i class="fa fa-university" aria-hidden="true"></i> Sede</label>
                <input id="sede" class="form-control text-uppercase text-danger font-weight"
                    value="{{ $sede }}" readonly aria-label="Sede">
            </div>
        </div>

        <!-- ID Usuario oculto -->
        <input id="idusuario" type="hidden" value="{{ $id_user }}">

        <!-- N° de Factura -->
        <div class="row mt-3">
            <div class="col-12">
                <label for="nroFactura"><i class="fas fa-file-invoice"></i> N° de Factura</label>
                <input id="nroFactura" name="nroFactura" class="form-control text-uppercase text-primary font-weight"
                    value="{{ $numeroFactura }}" readonly aria-label="Número de factura">
            </div>
        </div>

        <!-- Botón Buscar Alumno -->
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alumnoList"
                aria-label="Buscar Estudiante">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i> Buscar Estudiante
            </button>
        </div>

        <form>
            <br>
            <div class="row">
                <div class="col" style="width:5%;">
                    <input type="text" class="form-control" style="width:49%;" placeholder="CÉDULA" aria-label="Cédula"
                        id="dni" onmouseover="mi_busqueda_inscripcion();listaExamenes();">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre" aria-label="Nombre" id="nombre"
                        readonly>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Apellido" aria-label="Apellido" id="apellido"
                        readonly>
                </div>
            </div>
        </form>
    </div>

    <br>

    <div class="table-responsive-lg">
        <table id="tablaInscripcion" class="table table-hover">
            <thead class="micolor" style="background-color: #F5DEB3;">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Curso</th>
                    <th scope="col">Fecha Inscripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Fecha de Inicio</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="texcurso">
                        <i class="fa fa-book" aria-hidden="true"></i> Curso:
                        <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip"
                            title="Presionar ENTER para buscar las cuotas" style="cursor: pointer;"></i>
                    </label>
                    <input id="texcurso" class="form-control custom" onkeypress="mi_busqueda_cuotas();">
                </div>
            </div>
            <input id="idinscripcion" type="hidden">
            <div class="col-md-6">
                <div class="form-group" hidden>
                    <label for="cuota">
                        <i class="fa fa-book" aria-hidden="true"></i> PAGO:
                    </label>
                    <input id="cuota" class="form-control">
                </div>
            </div>
        </div>

        <!-- Nueva fila para los botones centrados -->
        <div class="row mt-3">
            <div class="col-12 text-center">
                <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#cuotaModal">
                    <i class="fa fa-usd" aria-hidden="true"></i> Cobrar Pago
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalexamen">
                    <i class="fa fa-usd" aria-hidden="true"></i> Cobrar Examen Final
                </button>
            </div>
        </div>

        <input id="idcuotas" type="hidden">

        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive-lg">
                    <table id="tablaCuotas" class="table table-hover">
                        <thead class="micolor" style="background-color: #87CEEB;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fecha de Pago</th>
                                <th scope="col">Pago</th>
                                <th scope="col">Mes</th>
                                <th scope="col">Año</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Mora</th>
                                <th scope="col">Total</th>
                                <th scope="col">Condición</th>
                                <th scope="col">Operador</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('modals.modal_cuotas')
    @include('modals.modal_buscar_alumno')
@endpush


@push('scripts')
    <script>
        initDataTable('#tablaCuotas', {
            order: [
                [0, 'asc']
            ]
        });

        initDataTable('#tablaAlumno', {
            order: [
                [3, 'asc']
            ]
        });
        $('#detalleCheckbox').change(function() {
            if ($(this).is(':checked')) {
                $('#detalleTextarea').show();
            } else {
                $('#detalleTextarea').hide();
            }
        });
    </script>
@endpush
