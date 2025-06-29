@extends('layouts.app')

@section('content')
    <h3 class="text-center">Inscribir Estudiantes</h3><br>

    <div class="container">
        {{-- Información básica --}}
        <div class="row">
            <div class="col-6">
                <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                <input style="font-size: 16px; text-transform: uppercase; color: red;" value="{{ $usuario }}"
                    id="usuario" readonly class="form-control">
            </div>
            <div class="col-6">
                <label for="fechainicio"><i class="fa fa-calendar"></i> Fecha:</label>
                <input type="text" id="fechainicio" value="{{ $fecha_actual }}" name="comienzo" class="form-control"
                    readonly>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="nroFactura"><i class="fas fa-file-invoice"></i> N° de Factura</label>
                <input style="font-size: 16px; text-transform: uppercase; color: blue;" value="{{ $numeroFactura }}"
                    id="nroFactura" name="nroFactura" readonly class="form-control">
            </div>
        </div>

        {{-- Botones para buscar o crear estudiante --}}
        <br>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#alumnoList">
                <i class="fa fa-graduation-cap"></i> Buscar Estudiante
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo_alumno">
                <i class="fa fa-user-plus"></i> Añadir Estudiante
            </button>
        </div>

        {{-- Formulario Estudiante --}}
        <form>
            @csrf
            <br><label><i class="fa fa-graduation-cap"></i> Estudiante</label>
            <br>
            <div class="row">
                <div class="col" style="width:5%;">
                    <input type="text" class="form-control" placeholder="CÉDULA" id="dni"
                        onkeydown="buscar_datos_alumnos(event)">
                    <small class="form-text text-muted">Presiona <b>Enter</b> para buscar.</small>
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre" id="nombre" readonly>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Apellido" id="apellido" readonly>
                </div>
            </div>
            <input type="hidden" id="alumno_id">
            <input type="hidden" id="sede_id">
        </form>

        {{-- Formulario Inscripción --}}
        <div class="container mt-5">
            <div class="card w-100 mx-auto shadow">
                <div class="card-header bg-primary text-white text-center">
                    Datos Inscripción
                </div>
                <div class="card-body">

                    {{-- Sede --}}
                    <div class="row mb-4">
                        <div class="col" hidden>
                            <label for="sede"><i class="fa fa-university"></i> SEDE</label>
                            <input style="font-size: 16px; text-transform: uppercase; color: red;"
                                value="{{ $sede }}" id="sede" readonly class="form-control">
                        </div>
                    </div>

                    {{-- Buscar curso --}}
                    <div class="row mb-4">
                        <div class="col text-center">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCurso">
                                <i class="fa fa-search-minus"></i> Buscar Curso
                            </button>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <label for="fechaComienzo"><i class="fa fa-calendar"></i> Fecha de Inicio</label>
                            <input type="date" id="fechaComienzo" name="comienzo" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 text-center">
                            <label for="fechaTermino"><i class="fa fa-calendar"></i> Fecha de Término</label>
                            <input type="date" id="fechaTermino" name="termino" class="form-control" readonly>
                        </div>
                    </div>

                    {{-- Curso --}}
                    <div class="row mb-4">
                        <div class="col">
                            <input type="hidden" id="idCurso" readonly>
                            <input type="text" class="form-control" placeholder="Nombre del Curso" id="curso"
                                onkeypress="buscar_datos_cursos();">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Duración" id="duracion" readonly>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Precio" id="precio" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Profesor --}}
                    <label class="mb-2"><i class="fa fa-users"></i> Profesor</label>
                    <div class="row mb-4">
                        <div class="col"><input type="text" class="form-control" placeholder="CÉDULA"
                                id="dniP" readonly></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Nombre"
                                id="nombreP" readonly></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Apellido"
                                id="apellidoP" readonly></div>
                    </div>

                    {{-- Detalle opcional --}}
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="detalleCheckbox">
                                <label class="form-check-label ms-2" for="detalleCheckbox">Añadir Detalle</label>
                            </div>
                            <textarea id="detalleTextarea" name="detalle" class="form-control mt-2" rows="4"
                                placeholder="Escribe el detalle aquí..." style="display: none;"></textarea>
                        </div>
                    </div>

                    {{-- Uniforme --}}
                    <div class="row mb-4 d-none">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="uniformeCheckbox" checked>
                                <label class="form-check-label" for="uniformeCheckbox">Uniforme:</label>
                            </div>
                        </div>
                    </div>

                    {{-- Selección de Uniforme --}}
                    <div class="row mb-4" id="uniformeContainer" style="display: none;">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $uniforme['nombre'] ?? '' }}"
                                    id="uniformeSeleccionado" disabled>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalUniformes">Buscar</button>
                            </div>
                            <input type="hidden" name="id_uniforme" id="id_uniforme"
                                value="{{ $uniforme['id_uniforme'] ?? '' }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Selección de Sala y Medio de Pago --}}
    <div class="container">
        <br>
        <div class="row">
            <div class="col">
                <label for="salas">Salas Disponibles</label>
                <select name="sala" id="salas" class="form-control">
                    @foreach ($salas as $sala)
                        <option value="{{ $sala }}">{{ $sala }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col">
                <label for="medioPago">Medio de Pago</label>
                <select name="medioPago" id="medioPago" class="form-control">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="pagoFacil">Pago Fácil</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Total, Impresión, Botones --}}
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <br>
                <label for="total" class="h4 d-block">Total Inscripción:</label>
                <div class="input-group mx-auto" style="width: 30%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="font-size: 30px;">$</span>
                    </div>
                    <input type="text" class="form-control text-center" id="total" readonly
                        style="font-size: 30px;">
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-auto d-flex align-items-center">
                <label for="tipo-impresion" class="form-label mb-0 mr-2 h5">Tipo de Impresión:</label>
                <select id="tipo-impresion" name="tipo-impresion" class="form-control w-auto">
                    <option value="A4">A4</option>
                    <option value="Ticket">Ticket</option>
                </select>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-auto">
                <input type="button" value="Inscribir Estudiante" class="btn btn-primary" onclick="guardar();">
            </div>
            <div class="col-auto">
                <input type="button" value="Limpiar" class="btn btn-danger" onclick="limpiar();">
            </div>
        </div>

        <div class="resultados"></div>
    </div>

    {{-- Tabla de inscripciones --}}
    <div class="table-responsive-lg mt-4">
        <h2 class="text-center">Lista De Inscripciones</h2>
        <table class="table table-hover table-striped table-bordered mt-2" id="inscripcionTable">
            <thead class="thead-dark">
                <tr>
                    <th>CÉDULA</th>
                    <th>N° Factura</th>
                    <th>Nombre</th>
                    <th>Curso</th>
                    <th>Profesor</th>
                    <th>Fecha y Hora de Inscripción</th>
                    <th>Fecha de Inicio</th>
                    {{-- <th>Sede</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscripciones as $ins)
                    <tr>
                        <td>{{ $ins->dni }}</td>
                        <td>{{ $ins->nroFactura }}</td>
                        <td>{{ $ins->alumno }}</td>
                        <td>{{ $ins->curso }}</td>
                        <td>{{ $ins->profesor ?? 'Sin asignar' }}</td>
                        <td>{{ $ins->fecha }}</td>
                        <td>{{ $ins->fechacomienzo }}</td>
                        {{-- <td>{{ $ins->sede }}</td> --}}
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-warning" data-toggle="modal" data-target="#impresionModal"
                                    onclick="capturarIdInscripcion(this)" data-id="{{ $ins->id }}">
                                    <i class="fa fa-file"></i>
                                </a>
                                @if ($userRole === 'admin')
                                    <button class="btn btn-danger" data-toggle="modal"
                                        data-target="#confirmarEliminacionModal"
                                        onclick="setInscripcionId({{ $ins->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @else
                                    <button class="btn btn-danger" data-toggle="modal"
                                        data-target="#adminConfirmacionModal"
                                        onclick="setInscripcionId({{ $ins->id }})">
                                        <i class="fas fa-trash-alt"></i> Solicitar Admin
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('modals')
    @include('modals.modal_alumno_nuevo')
    @include('modals.modal_buscar_alumno')
    @include('modals.modal_buscar_curso')
@endpush

@push('scripts')
    {{-- <script src="{{ asset('assets/js/pages/inscripcion-index.js') }}"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const detalleCheckbox = document.getElementById("detalleCheckbox");
            const detalleTextarea = document.getElementById("detalleTextarea");

            if (detalleCheckbox && detalleTextarea) {
                detalleCheckbox.addEventListener("change", function() {
                    if (this.checked) {
                        detalleTextarea.style.display = "block"; // Mostrar el textarea primero

                        // cargarDetalleFac()
                        //     .then((detalleFac) => {
                        //         detalleTextarea.value = detalleFac || "Sin detalle disponible.";
                        //     })
                        //     .catch((error) => {
                        //         console.error("Error al cargar el detalle:", error);
                        //         detalleTextarea.value = "Error al cargar el detalle.";
                        //     });
                    } else {
                        detalleTextarea.value = "";
                        detalleTextarea.style.display = "none";
                    }
                });
            }

            // Inicializar DataTables si los elementos existen
            if (document.querySelector('#tablaAlumno')) {
                initDataTable('#tablaAlumno', {
                    order: [
                        [3, 'asc']
                    ]
                });
            }

            if (document.querySelector('#inscripcionTable')) {
                initDataTable('#inscripcionTable', {
                    order: [
                        [2, 'asc']
                    ]
                });
            }

            $('#modalCurso').on('show.bs.modal', function() {
                cargarTablaCursos('/cursos/lista', '#tablaCurso', '#modalCurso');
            });
        });

        function seleccionarCurso(id, nombre) {
            alert(`Curso seleccionado: ${nombre} (ID: ${id})`);
            $('#modalCurso').modal('hide');
        }
    </script>

    <script>
        //let tablaCursoDT;

        // Ejemplo de función que podrías usar para seleccionar curso
    </script>
@endpush
