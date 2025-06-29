@extends('layouts.app') {{-- O el layout que uses --}}

@section('content')
    <section class="container my-4">
        <div class="mb-4">
            <h1 class="h3 font-weight-bold">Historial de Cursos del Estudiante</h1>
        </div>

        {{-- Formulario del estudiante --}}
        <section>
            <form>
                {{-- Datos ocultos del usuario/sede --}}
                <div class="d-none">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="sede"><i class="fa fa-university"></i> Sede</label>
                            <input type="text" class="form-control text-uppercase text-danger" id="sede"
                                value="{{ $sede ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                            <input type="text" class="form-control text-uppercase text-danger" id="usuario"
                                value="{{ session('nombre') }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- Botón buscar estudiante --}}
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alumnoList">
                        <i class="fa fa-graduation-cap"></i> Buscar Estudiante
                    </button>
                </div>

                {{-- Cédula del estudiante --}}
                <div class="form-group">
                    <label><i class="fa fa-id-card"></i> Estudiante</label>
                    <input type="text" class="form-control w-25" id="dni" placeholder="CÉDULA"
                        onkeypress="buscar_datos_alumnos();">
                </div>

                {{-- Datos básicos del estudiante --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" readonly>
                    </div>
                </div>

                {{-- Detalles adicionales en acordeón --}}
                <div class="accordion" id="accordionDetalles">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-left w-100" type="button" data-toggle="collapse"
                                    data-target="#collapseDetalles" aria-expanded="true" aria-controls="collapseDetalles">
                                    Mostrar más detalles
                                </button>
                            </h2>
                        </div>

                        <div id="collapseDetalles" class="collapse" aria-labelledby="headingOne"
                            data-parent="#accordionDetalles">
                            <div class="card-body">
                                <div class="form-row mb-3">
                                    <div class="form-group col-md-6">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="celular">Celular</label>
                                        <input type="tel" class="form-control" id="celular" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" readonly>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="tutor">Tutor</label>
                                        <input type="text" class="form-control" id="tutor" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contacto">Celular de Contacto</label>
                                        <input type="tel" class="form-control" id="contacto" readonly>
                                    </div>
                                </div>
                                <div class="d-none">
                                    <label for="sedeAlumno">Sede</label>
                                    <input type="text" class="form-control" id="sedeAlumno" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Campos ocultos --}}
                <input type="hidden" id="alumno_id">
                <input type="hidden" id="sede_id">
            </form>
        </section>

        {{-- Tabla de cursos --}}
        <section class="mt-4">
            <div class="table-responsive-lg">
                <table id="historialTable" class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="display: none;">Id</th>
                            <th>#</th>
                            <th>Curso</th>
                            <th>Fecha Inscripción</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Pagos a Deber</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
@push('modals')
    @include('modals.modal_buscar_alumno')
@endpush
@push('scripts')
    <script>
        initDataTable('#historialTable', {
            order: [
                [0, 'asc']
            ]
        });
        initDataTable('#tablaAlumno', {
            order: [
                [2, 'asc']
            ]
        });
    </script>
@endpush
