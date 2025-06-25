@extends('layouts.app') {{-- O tu layout base --}}

@section('title', 'Gestión de Alumnos')

@section('content')
    <div class="container-fluid d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#nuevo_alumno">
            <i class="fas fa-plus"></i> Nuevo Alumno
        </button>
        <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#bajas" hidden>
            <i class="fas fa-minus"></i> Alumnos Dados de Baja
        </button>
    </div>

    @if (isset($alert))
        <div class="alert alert-info">{{ $alert }}</div>
    @endif

    <div class="table-responsive-lg">
        <table class="table table-hover table-striped table-bordered" id="alumnosTable">
            <thead class="thead-dark">
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th>Tutor</th>
                    <th>Contacto</th>
                    <th hidden>Sede</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alumnos as $alumno)
                    <tr>
                        <td>{{ $alumno->dni }}</td>
                        <td>{{ $alumno->nombre }}</td>
                        <td>{{ $alumno->apellido }}</td>
                        <td>{{ $alumno->direccion }}</td>
                        <td>{{ $alumno->celular }}</td>
                        <td>{{ $alumno->email }}</td>
                        <td>{{ $alumno->tutor }}</td>
                        <td>{{ $alumno->contacto }}</td>
                        <td hidden>{{ $alumno->sede ?? '' }}</td>
                        <td>
                            @if ($alumno->estado === 'Activo')
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">{{ $alumno->estado }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('alumnos.edit', $alumno->idalumno) }}"
                                    class="btn btn-success btn-sm mr-2" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('alumnos.destroy', $alumno->idalumno) }}" method="POST"
                                    class="confirmar d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No hay alumnos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        {{ $alumnos->links() }}
    </div>
@endsection

@push('modals')
    @include('modals.modal_alumno_nuevo')
    @include('modals.modal_alumno_bajas')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = document.querySelector('#alumnosTable');
            const filas = table.querySelectorAll('tbody tr');

            if (filas.length === 1 && filas[0].querySelectorAll('td').length === 1) {
                return;
            } else {
                console.log("Hola")
            }

            initDataTable('#alumnosTable', {
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
@endpush
