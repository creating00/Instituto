@extends('layouts.app')

@section('title', 'Estadísticas')

@push('styles')
    <style>
        .custom-thead {
            background-color: #007BFF;
            /* azul Bootstrap */
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-3">
        <div class="row">
            <!-- Usuario -->
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Usuario</label>
                    <input type="text" class="form-control text-uppercase text-danger font-weight-bold"
                        value="{{ $nombre }}" id="usuario" readonly>
                </div>
            </div>

            <!-- Fecha -->
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fa fa-calendar"></i> Fecha</label>
                    <input type="text" id="fechainicio" name="comienzo" class="form-control" value="{{ $fecha_actual }}"
                        readonly>
                </div>
            </div>

            <!-- Sede -->
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fa fa-university"></i> Sede</label>
                    <input type="text" class="form-control text-uppercase text-danger font-weight-bold"
                        value="{{ $sede }}" id="sede" readonly>
                </div>
            </div>
        </div>

        <input type="hidden" id="idusuario" value="{{ $id_user }}">
    </div>

    <br>

    <div class="container mt-4">
        <h1 class="text-center font-weight-bold">PLATAFORMA DE CONTROL</h1>
        <hr>

        <div class="row justify-content-center text-center">
            <div class="col-md-4 mb-3">
                <input type="button" class="btn btn-info btn-lg w-100" data-toggle="modal" data-target="#modalCurso"
                    value="Total de Alumnos por Curso">
            </div>

            @if ($sede === 'GENERAL')
                <div class="col-md-4 mb-3">
                    <input type="button" class="btn btn-warning btn-lg w-100" data-toggle="modal" data-target="#sedes20"
                        value="Total de Alumnos por Sedes">
                </div>
            @endif
        </div>

        <!-- Total -->
        <div class="text-center mt-4">
            <label class="font-weight-bold h2 d-block">TOTAL</label>
            <input type="text" id="total" class="form-control mx-auto text-center font-weight-bold"
                style="width: 30%; font-size: 2.5rem;" readonly>
        </div>

        <hr>

        <!-- Listas Detalladas -->
        <h2 class="text-center font-weight-bold mt-4">LISTAS DETALLADAS</h2>


        <div class="table-responsive-lg">
            <table id="tblAlumno" class="table table-hover">
                <thead class="custom-thead">
                    <tr>
                        <th scope="col">Cédula</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('modals')
    @include('modals.modal_buscar_curso')
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (document.querySelector('#tblAlumno')) {
                initDataTable('#tblAlumno', {
                    order: [
                        [1, 'asc']
                    ]
                });
            }
            $('#modalCurso').on('show.bs.modal', function() {
                cargarTablaCursos('/cursos/lista', '#tablaCurso', '#modalCurso');
            });
        });
    </script>
@endpush
