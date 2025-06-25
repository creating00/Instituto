@extends('layouts.app')

@section('title', 'Gestión de Cursos')

@section('content')
    <div class="container-fluid">
        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#nuevo_curso">
            Nuevo Curso <i class="fas fa-plus"></i>
        </button>
    </div>

    @if (session('alert'))
        <div class="alert alert-info mt-3">{{ session('alert') }}</div>
    @endif

    <div class="table-responsive-lg mt-3">
        <table class="table table-hover table-striped table-bordered" id="cursoTable">
            <thead class="thead-dark">
                <tr>
                    <th>Curso</th>
                    <th>Precio</th>
                    <th>Duración</th>
                    <th hidden>Sede</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cursos as $curso)
                    <tr>
                        <td>{{ $curso->nombre }}</td>
                        <td>${{ number_format($curso->precio, 2, '.', ',') }}</td>
                        <td>{{ $curso->tipo }}</td>
                        <td hidden>{{ $curso->sede->nombre ?? 'N/A' }}</td>
                        <td>
                            @if ($curso->estado == 1)
                                <span class="badge badge-pill badge-success">Activo</span>
                            @else
                                <span class="badge badge-pill badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($curso->estado == 1)
                                <a href="{{ route('cursos.edit', $curso->idcurso) }}" class="btn btn-success">
                                    <i class='fas fa-edit'></i>
                                </a>
                                <button type="button" class="btn btn-calendario"
                                    onclick="abrirModalFecha('{{ $curso->idcurso }}')">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </button>
                                <form action="{{ route('cursos.destroy', $curso->idcurso) }}" method="POST"
                                    class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('cursos.alta', $curso->idcurso) }}" class="btn btn-warning">
                                    <i class='fa fa-user-plus' aria-hidden='true'></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay cursos disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('modals')
    @include('modals.modal_curso_nuevo')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = document.querySelector('#cursoTable');
            const filas = table.querySelectorAll('tbody tr');

            if (filas.length === 1 && filas[0].querySelectorAll('td').length === 1) {
                return;
            }

            initDataTable('#cursoTable', {
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endpush
